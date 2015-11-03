<?php

namespace GreenwichFreecycle\Web\Model;

class Result
{
    public $worked;
    public $message;
    public $errorCode;

    public function __construct($inputWorked, $inputMessage = null, $errorCode = null)
    {
        $this->worked = $inputWorked;
        $this->message = $inputMessage;
        $this->errorCode = $errorCode;
    }
}

?>