<?php

namespace Web\Utilities;

class Security
{
    public function phpHash($string)
    {
        return password_hash($string, PASSWORD_DEFAULT);
    }

    public function phpVerify($string, $hashedString)
    {
        return password_verify($string, $hashedString);
    }

    public function md5Hash($string)
    {
        return md5($string);
    }
}

?>