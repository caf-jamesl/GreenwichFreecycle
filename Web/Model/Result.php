<?php

namespace GreenwichFreecycle\Web\Model;

class Result
{
    public $worked;
    public $message;

    public function __construct($inputWorked, $inputMessage = null)
    {
        $this->worked = $inputWorked;
        $this->message = $inputMessage;
    }
}

?>