<?php

namespace GreenwichFreecycle\Web\Utilities;

class SessionManagement
{
    private static $instance;
    private $session;

    public static function instance() {
        if (self::$instance == null) {
            $class = __CLASS__;
            self::$instance = new $class;
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
}

?>