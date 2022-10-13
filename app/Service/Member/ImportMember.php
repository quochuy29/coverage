<?php

namespace App\Service\Member;

use Illuminate\Support\Facades\Storage;

class ImportMember
{
    protected $path;

    public function __construct()
    {
        
    }

    public function excuteTempTable($path)
    {
        $formatedFile =  Storage::path('csv/formatted.csv');
        shell_exec("tr -d '\r' < " . $path . " > " . $formatedFile);
        $item = $this->getItemTranfer();

        for($i = 0; $i <= count($item); $i++){
            if (isset($item[$i])) {
                
            }
        }
    }

    public function getItemTranfer()
    {
        return [
            0 => "member_code",
            1 => "member_name",
            2 => "member_login_name",
            3 => "member_password",
            4 => "member_email",
            5 => "member_phone_mobile"
        ];
    }
}

?>