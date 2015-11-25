<?php

namespace GreenwichFreecycle\Web\Controller;

error_reporting(0);

require_once (dirname(__DIR__). '/Utilities/Autoloader.php');

use GreenwichFreecycle\Web\Business\UserManagement;
use GreenwichFreecycle\Web\Model\Result;
use GreenwichFreecycle\Web\Model\TemplateParameter;
use GreenwichFreecycle\Web\Utilities\PageManagement;

main();

function main()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
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

function outputPage($usernameInputErrorMessage = '')
{
    $templateParameters = array(new TemplateParameter('usernameInputErrorMessage', $usernameInputErrorMessage));
    $pageManagement = new PageManagement;
    echo $pageManagement->handlePage('register.html', $templateParameters);
}

?>