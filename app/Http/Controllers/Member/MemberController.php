<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Mail\SendMail;
use App\Models\Config;
use App\Models\Member;
use App\Scopes\DeleteScope;
use App\Service\Member\DataTranferMember;
use App\Service\Member\ImportMember;
use App\Service\Member\MemberService;
use App\Service\Member\ValidateDataCsv;
use App\Service\Member\ValidateFileCsv;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class MemberController extends Controller
{
    protected $memberService;
    protected $memberImport;
    protected $configModules;

    public function __construct()
    {
        $this->memberService = new MemberService();
        $this->memberImport = new ImportMember();
        $this->configModules = Config::where('module', 'app')->get();
    }

    public function index(Request $request)
    {
        $query = Member::select('member_id', 'member_name', 'member_email', 'member_phone_mobile')
        ->orderBy($request->sortField ?? 'member_name', $request->sortType ?? 'asc');
        
        if ((string)$request->searchField !== '') {
            $query = $query->where('member_name', 'like', "%{$request->searchField}%");
        }

        return $query->paginate('10', ['*'], 'page', $request->page ?? 1);
    }

    public function getDetailMember($id)
    {
        return Member::select('member_avatar', 'member_name', 'member_phone_mobile', 'member_email', 'member_login_name')
        ->where('member_id', $id)
        ->first();
    }

    public function edit($id, Request $request)
    {
        $member = Member::findOrFail($id);
        if ($request->member_password !== null && (string)$request->member_password !== '') {
            $this->memberService->saveMemberPasswordHistory($member);
        }
        $member->fill($request->all());
        $member->member_reset_pwd_is_required = 1;
        $member->save();

        return $member;
    }

    public function delete($id)
    {
        try {
            $member = Member::findOrFail($id);
            $member->member_is_deleted = 1;
            $member->save();

            return response()->json([
                'status' => 200,
                'message' => "Delete user {$member->member_login_name} successfully!"
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 422,
                'message' => "Delete user {$member->member_login_name} not successfully!"
            ]);
        }
    }

    public function exportCSV(Request $request)
    {
        if(trim((string) $request->user_quantity) === '' || (int) $request->user_quantity < 1) {
            $request->user_quantity = 100;
        }
        $member = Member::select(
        'member_code',
        'member_name', 
        'member_login_name', 
        'member_password',
        'member_email', 
        'member_phone_mobile')
        ->where('member_reset_pwd_is_required', 0)
        ->withoutGlobalScope(DeleteScope::class)
        ->where(function ($query) use ($request) {
            if (!filter_var($request->user_deleted,FILTER_VALIDATE_BOOLEAN)) {
                $query->where('member_is_deleted', 0);
            }
        })
        ->limit($request->user_quantity)
        ->get();

        $member = $this->formatMemberData($member);
        $auth = auth()->user();

        $dataMail = [
            'subject' => "User {$auth->member_login_name} export member information data",
            'view' => 'template-export-user',
            'appName' => $this->configModules->where('type', 'app_name')->first()->value,
            'quantity' => $request->user_quantity,
            'memberName' => $auth->member_name
        ];

        $this->sendMail($dataMail);

        return response()->json([
            'data' => $member
        ]);
    }

    private function formatMemberData($members)
    {
        $data = [];
        if (empty($members)) {
            return [];
        }
        $members->makeVisible(['member_password']);
        foreach ($members->toArray() as $member) {
            $member['member_password'] = '';
            $data[] = $member;
        }
        return $data;
    }

    public function sendMail($mailInfo)
    {
        return Mail::to(auth()->user()->member_email)->send(new SendMail($mailInfo));
    }

    public function uploadFile(Request $request, ValidateFileCsv $validateFile)
    {
        if ($request->file('file')) {
            $errMsg = '';
            $path = $request->file('file')->getRealPath();
            $errCode = $validateFile->validateFileCsv($path, $errMsg);

            if ($errCode == 1) {
                return response()->json([
                    'error' => $errMsg
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            Storage::putFileAs('csv', $request->file('file'), 'members.csv');

            return response()->json([
                'success' => 'ok file'
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'error' => 'upload fileeeeeeeeeeeeee'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function writeImportLogFile($errorMsg)
    {
        $content = '';
        $logs = 'logs/';
        $fileName = date('Ymd', time()) . '_' . date('His', time()) . '.txt';
        foreach ($errorMsg as $msg) {
            $line = $msg . PHP_EOL;
            $content .= $line;
        }
        if (!Storage::disk('local')->exists($logs)) {
            Storage::disk('local')->makeDirectory($logs);
        }
        Storage::disk('local')->put($logs . $fileName, $content);

        return $fileName;
    }

    public function importFile(ValidateFileCsv $validate, ValidateDataCsv $validateDataCsv)
    {
        $path = Storage::path('csv/members.csv');

        $this->memberImport->excuteTempTable($path);
        $dataTranfer = resolve(DataTranferMember::class)->getDataTempTable();
        $intIsOk = $validate->validateFormatDataCsv($validateDataCsv, $dataTranfer, $aryError);

        if ($intIsOk === 0) {
            $fileName = $this->writeImportLogFile($aryError);

            return response()->json([
                'status' => 'Import failed',
                'file_name' => $fileName,
                'url_error' => "/storage/logs/{$fileName}"
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } else {
            $originTable = Member::TABLE_MEMBER;
            $tempTable = 'member_temp';

            $this->memberImport->executeTempTableData($tempTable);
            list($resultNumberUpdate, $resultNumberInsert) = $this->memberImport->updateMemberTable($originTable, $tempTable);
        }
    }
}
