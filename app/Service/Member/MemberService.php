<?php

namespace App\Service\Member;

use App\Models\MemberHistoryPassword;

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

}

?>