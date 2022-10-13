<?php

namespace App\Service\Member;

class ValidateFileCsv 
{
    public function validateFileCsv($path, &$errMsg = '')
    {
        $headerFile = $this->fieldFileHeader();
        $errCode = 0;
        $isValid = true;
        $countLine = 0;
        shell_exec("sed -i '1s/^\xEF\xBB\xBF//' " . $path);
        if (($handle = fopen($path, 'r')) !== false) {
            while(($line = fgetcsv($handle, null, ',', '"', '\\')) !== false) {
                $lineStr = implode(',', $line);
                
                if (empty($line) || (string)$lineStr === "") {
                    continue;
                }

                if (!mb_detect_encoding($lineStr, 'UTF-8', true)) {
                    $isValid = false;
                    $errMsg = "Chuyển file sang mã UTF-8";
                    $errCode = 1;
                    break;
                }
                
                if (empty($line)) {
                    continue;
                }

                $countLine++;

                if (!$this->validateCsvLine($line, $headerFile)) {
                    $isValid = false;
                    $errMsg = "Bị thừa hoặc thiếu một mục import";
                    $errCode = 1;
                    break;
                }

                if ($countLine === 1 && !empty(array_diff($line, $headerFile))) {
                    $isValid = false;
                    $errMsg = "Header không hợp lệ";
                    $errMsg = 1;
                }
            }
        }
        fclose($handle);

        if ($isValid && $countLine < 1) {
            $isValid = false;
            $errCode = 1;
            $errMsg = 'Bị thừa hoặc thiếu một mục import';
        }

        if ($isValid && $countLine < 2) {
            $isValid = false;
            $errCode = 1;
            $errMsg = 'Nội dung của tệp được nhập không hợp lệ và không thể nhập được';
        }

        return $errCode;
    }

    public function fieldFileHeader()
    {
        return [
            0 => "member code",
            1 => "member name",
            2 => "member login name",
            3 => "member email",
            4 => "member phone mobile"
          ];
    }

    public function validateCsvLine($lineContent, $headerFile)
    {
        if ((int)count($lineContent) != (int)count($headerFile)) {
            return false;
        }
        return true;
    }


}

?>