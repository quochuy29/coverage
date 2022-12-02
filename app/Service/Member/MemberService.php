<?php

namespace App\Service\Member;

use App\Models\MemberHistoryPassword;
use Illuminate\Support\Facades\DB;
use Throwable;

class MemberService
{
    
    public function saveMemberPasswordHistory($member)
    {
        if ($member && $member !== null) {
            $passwordHistory = new MemberHistoryPassword();
            $passwordHistory->member_code = $member->member_code;
            $passwordHistory->member_history_password = $member->member_password;
            $passwordHistory->member_history_password_update = now();
            $passwordHistory->save();
        }
    }

    public function getTablePdo($table, $selectField = ['*'], $condition = null)
    {
        try {
            $sql = DB::table($table)->select($selectField);
            if (!empty($condition)) {
                $sql->whereRaw($condition);
            }
            $sql = $sql->toSql();
            $data = DB::connection()->getpdo()->prepare($sql);
            $data->execute();
            return $data;
        } catch (Throwable $e) {
            $errorMsg = $e->getMessage();
        }
    }

}

?>