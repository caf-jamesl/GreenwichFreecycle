<?php

namespace GreenwichFreecycle\Web\Controller;

error_reporting(0);

require_once (dirname(__DIR__). '/Utilities/Autoloader.php');

use GreenwichFreecycle\Web\Business\UserManagement;
use GreenwichFreecycle\Web\Model\Result;
use GreenwichFreecycle\Web\Model\TemplateParameter;
use GreenwichFreecycle\Web\Model\Enum\ErrorCode;
use GreenwichFreecycle\Web\Utilities\PageManagement;
use GreenwichFreecycle\Web\Utilities\ReCaptchaApiManagement;

main();

function main()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        if (!$_POST['g-recaptcha-response'])
        {
         $errorMessage = 'Please complete the reCAPTCHA';
         outputPage($errorMessage);
         exit;
        }
        $reCaptchaApiManagement = new ReCaptchaApiManagement;
        if (!($reCaptchaApiManagement->IsUserOkay($_POST['g-recaptcha-response'])))
        {
         $errorMessage = 'Please complete the reCAPTCHA correctly';
         outputPage($errorMessage);
         exit;
        }
        $username = trim($_POST['usernameInput']);
        $password = trim($_POST['passwordInput']);
        $email = trim($_POST['emailInput']);
        $userManagement = new UserManagement();
        $result = $userManagement->register($username, $password, $email);
        if($result->worked)
        {
            header('Location: RegisterThankYou.php');
            exit;
        } else
        {
            switch ($result->errorCode) 
            {
                case ErrorCode::UsernameTaken:
                        $usernameInputErrorMessage = 'Sorry, that username has already been taken. Please choose another.';
                        break;
                outputPage($usernameInputErrorMessage);
                exit;
            }
        }
    }
    outputPage();
    exit;
}

function outputPage($registerErrorMessage = '')
{
    $templateParameters = array(new TemplateParameter('registerErrorMessage', $registerErrorMessage));
    $pageManagement = new PageManagement;
    echo $pageManagement->handlePage('register.html', $templateParameters);
}

?>