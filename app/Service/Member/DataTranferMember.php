<?php

namespace App\Service\Member;

use App\Models\Member;
use Illuminate\Support\Facades\DB;

class DataTranferMember
{
    const TEMP_TABLE = 'member_temp';

    public function getDataTempTable()
    {
        $importTempTable = resolve(MemberService::class);
        return $importTempTable->getTablePdo(self::TEMP_TABLE) ?? [];
    }

    public function getListMemberMail()
    {
        $data = Member::select(['member_email', 'member_login_name'])->whereNotNull('member_email')->where('member_email', '!=', '')->get()->toArray();
        $mail = array_column($data, 'member_email');
        $login = array_column($data, 'member_login_name');
        return array_combine($login, $mail);
    }

    public function getListUserCode()
    {
        $data = Member::selectRaw('LOWER(member_login_name) as member_login_name')->whereNotNull('member_login_name')->where('member_login_name', '!=', '')->get()->toArray();
        return array_column($data, 'member_login_name');
    }

    public function removeMemberPasswordHistoryTableOutLimit($limit)
    {
        $query = "DELETE history
            FROM `member_history_password` history
            JOIN
            (
                SELECT member_history_password_id
                FROM `member_history_password`
                GROUP BY member_code
                HAVING COUNT(member_code) > {$limit}
            ) count_history on count_history.member_history_password_id = history.member_history_password_id";
        return DB::select($query);
    }

    public function updateMemberFromTempTable($tempTable, $originTable, $isNullPassword = true)
    {
        $memberPasswordCondition = 'IS NULL';
        $updateRows = "origin.member_email           = temp.member_email,
                       origin.member_phone_mobile    = temp.member_phone_mobile,
                       origin.member_updater_id      = temp.member_updater_id";

        if (!$isNullPassword) {
            $memberPasswordCondition = 'IS NOT NULL';
            $updateRows .= ", origin.member_password = temp.member_password,
                            origin.member_reset_pwd_is_required = temp.member_reset_pwd_is_required,
                            origin.member_reset_pwd_datetime    = temp.member_reset_pwd_datetime";
        }

        $sqlUpdate = "UPDATE $originTable as origin LEFT JOIN $tempTable as temp
                ON origin.member_login_name = temp.member_login_name
                SET $updateRows
                WHERE temp.member_login_name IS NOT NULL AND origin.member_is_deleted = 0 AND temp.member_password {$memberPasswordCondition}";
        $pdo = DB::connection()->getPdo();
        $query = $pdo->prepare($sqlUpdate);
        $query->execute();
        return $query->rowCount();
    }

    public function addAdditionalDataTempTable($table, $updatedData)
    {
        $set = [];
        foreach ($updatedData as $k => $v) {
            $set[] = "$k='$v'";
        }
        $sql = "UPDATE $table SET " . implode(', ', $set);
        return DB::select($sql);
    }
    
    public function encodePasswordOnTempleTable($table)
    {
        $query = "UPDATE `{$table}` SET `member_password` = IF(`member_password` <> '', MD5(`member_password`), NULL)";
        return DB::select($query);
    }

    public function createMemberCodeByMemberLoginName($table)
    {
        $query = 'UPDATE `' . $table . '` SET member_code = replace(replace(replace(replace(replace(replace
        (replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace
        (replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace
        (replace(member_login_name, "!", ""), \'\"\', ""), "#", ""), "$", ""), "%", ""), "&", ""), "(", ""), ")", "")
        , "-", ""), "^", ""), "\\\\", ""), "@", ""), "[", ""), "]", ""), ";", ""), ":", ""), ",", ""), ".", "")
        , "/", ""), "=", ""), "~", ""), "|", ""), "`", ""), "{", ""), "}", ""), "+", ""), "\'", ""), "*", "")
        , "<", ""), ">", ""), "?", ""), "_", ""), "", "")';
        return DB::select($query);
    }

    public function setIsInsertRowTempTable($originTable, $tempTable)
    {
        $query = "UPDATE
                `{$tempTable}` AS temp
                    LEFT JOIN `{$originTable}` AS origin
                    ON `temp`.`member_login_name` = `origin`.`member_login_name`  AND `origin`.`member_is_deleted` = 0
                SET `temp`.`member_is_insert_row` = '1'
                WHERE
                 `origin`.`member_login_name` IS NULL";
        return DB::connection()->getPdo()->exec($query);
    }
    

    public function insertOriginTable($tempTable, $originTable)
    {
        $insertOriginFields = "member_login_name,
                            member_name,
                            member_code,
                            member_password,
                            member_email,
                            member_phone_mobile,
                            member_creator_id,
                            member_is_deleted,
                            member_reset_pwd_is_required,
                            member_reset_pwd_datetime";

        $tempCopyFields = "temp.member_login_name,
                            temp.member_name,
                            temp.member_code,
                            temp.member_password,
                            temp.member_email,
                            temp.member_phone_mobile,
                            temp.member_creator_id,
                            temp.member_is_deleted,
                            temp.member_reset_pwd_is_required,
                            temp.member_reset_pwd_datetime";

        $sql = "INSERT INTO  `{$originTable}` ({$insertOriginFields})
            SELECT
            {$tempCopyFields}
            FROM
                `{$tempTable}` AS temp
                LEFT JOIN `{$originTable}` AS origin
                ON `temp`.`member_login_name` = `origin`.`member_login_name`  AND `origin`.`member_is_deleted` = 0
            WHERE
                 `origin`.`member_login_name` IS NULL";
        $pdo = DB::connection()->getPdo();
        $query = $pdo->prepare($sql);
        $query->execute();
        return $query->rowCount();
    }

    public function getDuplicateMemberCodes($originTable, $tempTable, $isReplaceField = 1, $memberCode = '')
    {
        $memberCodeField = "member_code";
        if ($isReplaceField) {
            $memberCodeField = 'replace(replace(replace(replace(replace(replace (replace(replace(replace(replace(
            replace(replace(replace(replace(replace(replace(replace(replace(replace (replace(replace(replace(replace(
            replace(replace(replace(replace(replace(replace(replace(replace(replace (
            replace(member_login_name, "!", ""), \'\"\', ""), "#", ""), "$", ""), "%", ""), "&", ""), "(", ""), ")", "")
            , "-", ""), "^", ""), "\\\\", ""), "@", ""), "[", ""), "]", ""), ";", ""), ":", ""), ",", ""), ".", "")
            , "/", ""), "=", ""), "~", ""), "|", ""), "`", ""), "{", ""), "}", ""), "+", ""), "\'", ""), "*", "")
            , "<", ""), ">", ""), "?", ""), "_", ""), "", "")  as member_code';
        }
        $query = 'SELECT ' . $isReplaceField . ' as field_type,  union_member_code.member_code,
                COUNT(union_member_code.member_code) as count_all FROM
        (SELECT ' . $memberCodeField . ' FROM ' . $originTable . ' WHERE member_is_deleted = 0
        UNION ALL
        SELECT member_code FROM ' . $tempTable . ' WHERE member_is_insert_row = "1") as union_member_code';
        if ($memberCode !== '') {
            $query .= ' WHERE union_member_code.member_code = ' . "'{$memberCode}'";
        }
        $query .= ' GROUP BY union_member_code.member_code
        HAVING count_all > 1 ';

        return DB::select($query);
    }

    public function countMemberCodeOnlyDuplicateOnOriginTable($originTable, $memberCode = '')
    {

        $query = 'SELECT COUNT(member_code) as count_origin FROM ' . $originTable . ' WHERE member_is_deleted = 0 AND replace(replace(replace(replace(replace(replace
        (replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace
        (replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace
        (replace(member_login_name, "!", ""), \'\"\', ""), "#", ""), "$", ""), "%", ""), "&", ""), "(", ""), ")", "")
        , "-", ""), "^", ""), "\\\\", ""), "@", ""), "[", ""), "]", ""), ";", ""), ":", ""), ",", ""), ".", "")
        , "/", ""), "=", ""), "~", ""), "|", ""), "`", ""), "{", ""), "}", ""), "+", ""), "\'", ""), "*", "")
        , "<", ""), ">", ""), "?", ""), "_", ""), "", "") = "' . $memberCode . '"';
        $result = DB::select($query);
        return $result[0]->count_origin ?? 0;
    }

    public function updateDuplicateMemberCode($tempTable, $duplicateMemberCode)
    {
        $memberCode = $duplicateMemberCode->member_code;
        $countDuplicate = $duplicateMemberCode->count_all;
        $query = "SET @countDuplicate:={$countDuplicate};
        update {$tempTable}
        set member_code=CONCAT(member_code,@countDuplicate:=@countDuplicate-1)
        WHERE member_code = '{$memberCode}' AND member_is_insert_row = '1'";
        return DB::connection()->getPdo()->exec($query);
    }

}

?>