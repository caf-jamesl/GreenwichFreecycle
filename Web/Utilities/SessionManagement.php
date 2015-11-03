<?php

namespace GreenwichFreecycle\Web\Utilities;

class SessionManagement
{
    private static $instance;
    private $session;

    public static function instance() {
        if (self::$instance == null) {
            self::$instance = new SessionManagement;
        }
        return self::$instance;
    }

    private function __construct() {
        $this->session = array();
    }

    public function set($name, $value) {
        $this->session[$name] = $value;
    }

    public function get($name) {
        return $this->session[$name];
    }

    public function remove($name) {
        unset($this->session[$name]);
    }
}

?>