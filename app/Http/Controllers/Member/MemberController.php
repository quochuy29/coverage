<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Mail\SendMail;
use App\Models\Member;
use App\Service\Member\ImportMember;
use App\Service\Member\MemberService;
use App\Service\Member\ValidateFileCsv;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class MemberController extends Controller
{
    protected $memberService;
    protected $memberImport;

    public function __construct()
    {
        $this->memberService = new MemberService();
        $this->memberImport = new ImportMember();
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

    public function exportCSV()
    {
        $member = Member::select(
        'member_code',
        'member_name', 
        'member_login_name', 
        'member_email', 
        'member_phone_mobile')
        ->where('member_is_deleted', 0)
        ->where('member_reset_pwd_is_required', 0)
        ->limit(3)
        ->get();

        // $this->sendMail();

        return response()->json([
            'data' => $member
        ]);
    }

    public function sendMail()
    {
        $mailInfo = [
            'subject' => "【Super Duck】Look at me !!",
            'view' => 'template-export-user',
            'appName' => "Super Duck",
            'member_family_name' => "Phan",
            'member_first_name' => "Quốc Huy"
        ];

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

    public function importFile()
    {
        $path = Storage::path('csv/members.csv');

        $this->memberImport->excuteTempTable($path);
    }
}
