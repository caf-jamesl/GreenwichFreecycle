<?php

namespace GreenwichFreecycle\Web\Utilities;

session_start();

class SessionManagement
{
    public function set($name, $value)
    {
        $_SESSION[$name] = $value;
    }

    public function get($name)
    {
        return $_SESSION[$name];
    }
}

?>