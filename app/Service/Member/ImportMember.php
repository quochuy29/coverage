<?php

namespace App\Service\Member;

use App\Models\Member;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ImportMember
{
    protected $path;
    protected $dataTranfer;
    CONST TABLE_TEMP_MEMBER = 'member_temp';

    public function __construct()
    {
        $this->dataTranfer = resolve(DataTranferMember::class);
    }

    public function dropTempTable($table)
    {
        $query = "DROP TABLE IF EXISTS {$table}";
        DB::connection()->getPdo()->exec($query);
    }


    public function createTempTable($table, $fields, $dataType)
    {
        $queryField = "id INT(10) unsigned PRIMARY KEY AUTO_INCREMENT,";
        foreach ($fields as $field) {
            $queryField .= "{$field} {$dataType},";
        }
        $queryField = rtrim($queryField, ',');
        $query = "CREATE TABLE {$table} ({$queryField}) DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci";
        DB::connection()->getPdo()->exec($query);

    }

    public function loadCsvTempTable($field, $formatedFile)
    {
            $fields = implode(',', $field);
            $query = "
            LOAD DATA LOCAL INFILE '{$formatedFile}' 
            INTO TABLE " . self::TABLE_TEMP_MEMBER . " 
            FIELDS TERMINATED BY ',' 
            ENCLOSED BY '\"' 
            ESCAPED BY '\\b'
            LINES TERMINATED BY '" . PHP_EOL . "'
            IGNORE 1 LINES 
            ($fields)";
            $pdo = DB::connection()->getpdo()->prepare($query);
            $pdo->execute();
            @unlink($formatedFile);
    }

    public function buildConditionEmptyRecord($fields)
    {
        $where = "TRUE";
        foreach ($fields as $field) {
            $where .= " AND ({$field} IS NULL OR {$field} = '')";
        }
        return $where;
    }

    public function removeEmptyRecordTempTable($tableName, $emptyColumn)
    {
        $sql = "DELETE FROM {$tableName} WHERE {$emptyColumn}";
        DB::connection()->getPdo()->exec($sql);
    }

    public function excuteTempTable($path)
    {
        $formatedFile =  Storage::path('csv/formatted.csv');
        shell_exec("tr -d '\r' < " . $path . " > " . $formatedFile);
        $item = $this->getItemTranfer();

        $this->dropTempTable(self::TABLE_TEMP_MEMBER);
        $this->createTempTable(self::TABLE_TEMP_MEMBER, $item, 'TEXT');
        $this->loadCsvTempTable($item, $formatedFile);
        $this->removeEmptyRecordTempTable(self::TABLE_TEMP_MEMBER, $this->buildConditionEmptyRecord($item));
    }

    public function getItemTranfer()
    {
        return [
            0 => "member_code",
            1 => "member_name",
            2 => "member_login_name",
            3 => "member_password",
            4 => "member_email",
            5 => "member_phone_mobile",
            6 => "member_is_insert_row",
            7 => "member_reset_pwd_datetime",
            8 => "member_is_deleted",
            9 => "member_updater_id",
            10 => "member_reset_pwd_is_required",
            11 => "member_creator_id"
        ];
    }

    public function updateMemberTable($originTable, $tempTable, $deleteUnmatchRecord)
    {
        if ($deleteUnmatchRecord == 1) {
            $listMemberDelete = $this->dataTranfer->getNotExistUserFromTempTable($tempTable, $originTable);
            if (!empty($listMemberDelete)) {
                $this->dataTranfer->deleteMember($listMemberDelete);
            }
        }
        $resultNumberUpdateNullPassword = $this->dataTranfer->updateMemberFromTempTable($tempTable, $originTable);
        $resultNumberUpdateNotNullPassword = $this->dataTranfer->updateMemberFromTempTable($tempTable, $originTable, false);
        $resultNumberInsert = $this->dataTranfer->insertOriginTable($tempTable, $originTable);
        
        return [(int)$resultNumberUpdateNullPassword + (int)$resultNumberUpdateNotNullPassword, $resultNumberInsert];
    }

    public function getAdditionalMemberData($currentUser = null, $isInsert = true)
    {
        if (!$currentUser) {
            $currentUser = auth('sanctum')->user();
        }

        $data = [
            'member_reset_pwd_datetime' => date('Y-m-d H:i:s', time()),
            'member_is_deleted' => 0,
            'member_updater_id' => $currentUser->member_id
        ];
        
        if ($isInsert) {
            $data['member_reset_pwd_is_required'] = 0;
            $data['member_creator_id'] = $currentUser->member_id;
        }

        return $data;
    }

    /**
     * update dữ liệu ở bảng tạm để insert hoặc update bảng chính
     * hàm getAdditionalMemberData dùng để đưa các thông tin người update, create
     * cờ xoá member và reset password vào bảng phụ
     * hàm addAdditionalDataTempTable được gọi từ class DataTranferMember
     * mục đích update dữ liệu vừa được hàm getAdditionalMemberData đưa vào 
     * để update bảng tạm
     * hàm encodePasswordOnTempleTable được gọi từ class DataTranferMember
     * mục đích để convert mật khẩu khi import vào sang dạng MD5
     * hàm createMemberCodeByMemberLoginName được gọi từ class DataTranferMember
     * mục đích của hàm này sẽ convert từ field member login name thành member code
     * khi convert sẽ xoá hết các kí tự đặc biệt của member login name
     * hàm setIsInsertRowTempTable được gọi từ class DataTranferMember
     * mục đích là gán cờ insert cho 1 data ở temp bằng cách so sánh với bảng members
     * để phân biệt khi nào update khi nào create
    **/

    public function executeTempTableData($tempTable)
    {
        $originTable = Member::TABLE_MEMBER;
        $transferMember = resolve(DataTranferMember::class);
        $transferMember->addAdditionalDataTempTable($tempTable, $this->getAdditionalMemberData());
        $transferMember->encodePasswordOnTempleTable($tempTable);
        $transferMember->createMemberCodeByMemberLoginName($tempTable);
        $transferMember->setIsInsertRowTempTable($originTable, $tempTable);
        $this->handleDuplicateMemberCodes($originTable, $tempTable);
    }

    /**
     * hàm getDuplicateMemberCodes dùng để lấy ra các user có member_code trùng nhau ở
     * 2 bảng members và member_temp được tạo để chứa data tạm thời
     * hàm removeMemberCodeOnlyDuplicateOnOriginTable truyền 2 tham số là duplicate member_code
     * và tên bảng member
     * sau đó tiếp tục lấy ra các user có member_code trùng nhau nhưng sẽ không replace kí tự đặc biệt
     * và sau merge 2 mảng lại bằng hàm mergeDuplicateMemberCode sau đó check lại mảng vừa được merge
     * nếu rỗng sẽ trả về false nếu true thì sẽ lặp mảng $duplicateMemberCodes và tiếp tục truyền vào hàm
     * getDuplicateMemberCodes việc này lấy ra giá trị có member_code được truyền vào 
     * hàm updateDuplicateMemberCode sau khi truyền vào member_code sẽ thêm giá trị là 1 ví dụ như
     * member_code trùng hiện tại là huypq thì update lên thành huypq1
    **/

    public function handleDuplicateMemberCodes($originTable, $tempTable)
    {
        $transferMember = resolve(DataTranferMember::class);
        $duplicateMemberCodeReplaceFields = $transferMember->getDuplicateMemberCodes($originTable, $tempTable);
        $this->removeMemberCodeOnlyDuplicateOnOriginTable($duplicateMemberCodeReplaceFields, $originTable);

        $duplicateMemberCodeNotReplaceFields = $transferMember->getDuplicateMemberCodes($originTable, $tempTable, 0);
        $duplicateMemberCodes = $this->mergeDuplicateMemberCode($duplicateMemberCodeReplaceFields, $duplicateMemberCodeNotReplaceFields);
        if (empty($duplicateMemberCodes)) {
            return false;
        }
        foreach ($duplicateMemberCodes as $duplicateMemberCode) {
            /*phải count lại vì khi chạy hàm updateDuplicateMemberCode có thể tạo ra member code bị trùng trong
                bảng temp */
            $updatedDuplicateMemberCodes = $transferMember->getDuplicateMemberCodes(
                $originTable,
                $tempTable,
                (int) $duplicateMemberCode->field_type,
                $duplicateMemberCode->member_code
            );
            $transferMember->updateDuplicateMemberCode($tempTable, $updatedDuplicateMemberCodes[0]);
        }

        $this->handleDuplicateMemberCodes($originTable, $tempTable);
    }

    /**
     * hàm removeMemberCodeOnlyDuplicateOnOriginTable xử lý việc xoá đi những phần tử member_code
     * trùng lặp bằng cách lặp dự liệu được truyền vào từ hàm handleDuplicateMemberCodes
     * sau đó mỗi vòng lặp sẽ đưa member_code vào hàm countMemberCodeOnlyDuplicateOnOriginTable
     * ở đây sẽ xử lý đếm tên member_code được truyền vào và trả về số lượng có trong bảng members
     * sau đó sẽ so sánh số lượng đc trả về với giá trị count_all được lấy phần tử lặp của biến 
     * duplicateMemberCodes nếu trùng nhau sẽ xoá khỏi mảng duplicateMemberCodes
    **/

    public function removeMemberCodeOnlyDuplicateOnOriginTable(&$duplicateMemberCodes, $originTable)
    {
        $transferMember = resolve(DataTranferMember::class);
        foreach ($duplicateMemberCodes as $key => $duplicateMemberCode) {
            $countDuplicateOnOriginTable = $transferMember->countMemberCodeOnlyDuplicateOnOriginTable($originTable, $duplicateMemberCode->member_code);
            if ((int)$countDuplicateOnOriginTable === (int) $duplicateMemberCode->count_all) {
                unset($duplicateMemberCodes[$key]);
            }
        }
    }

    /**
     * hàm mergeDuplicateMemberCode xử lý merge 2 mảng duplicate member_code
     * $memberCodeReplaceFields sẽ lấy ra các column member_code trong mảng duplicateMemberCodeReplaceFields
     * sau đó lặp $duplicateMemberCodeNotReplaceFields đưa giá trị member_code của mảng chưa replace kí tự
     * đặc biệt và mảng được tạo ra ở trên $memberCodeReplaceFields sau đó chạy vào hàm  inArrayRegardlessUpperCaseLowerCase
     * trả về true hoặc false nếu true thì sẽ thêm giá trị vào mảng $duplicateMemberCodeReplaceFields
    **/

    private function mergeDuplicateMemberCode($duplicateMemberCodeReplaceFields, $duplicateMemberCodeNotReplaceFields)
    {
        $memberCodeReplaceFields = array_column($duplicateMemberCodeReplaceFields, 'member_code');
        foreach ($duplicateMemberCodeNotReplaceFields as $duplicateMemberCodeNotReplaceField) {
            if (!$this->inArrayRegardlessUpperCaseLowerCase($duplicateMemberCodeNotReplaceField->member_code, $memberCodeReplaceFields)) {
                $duplicateMemberCodeReplaceFields[] = $duplicateMemberCodeNotReplaceField;
            }
        }
        return $duplicateMemberCodeReplaceFields;
    }

    /**
     * hàm inArrayRegardlessUpperCaseLowerCase xử lý check giá trị có khớp với 1 trong số phần tử trong
     * mảng truyền vào và dựa vào việc có giá trị hay không để trả về true hoặc false 
    **/

    private function inArrayRegardlessUpperCaseLowerCase($value, $array)
    {
        return !empty(preg_grep("/{$value}/i", $array));
    }

}

?>