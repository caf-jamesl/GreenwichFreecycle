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
        $userManagement = new UserManagement();
        $result = $userManagement->register($_POST['usernameInput'], $_POST['passwordInput'], $_POST['emailInput']);
        if($result->worked)
        {
            $usernameInputErrorMessage = '';
            header('Location: RegisterThankYou.php');
            exit;
        } else
        {
            $usernameInputErrorMessage = $result->message;
            outputPage($usernameInputErrorMessage);
            exit;
        }
    }

    outputPage();
}

function outputPage($usernameInputErrorMessage = '')
{
    $templateParameters = array(new TemplateParameter(usernameInputErrorMessage, $usernameInputErrorMessage));
    $pageManagement = new PageManagement;
    echo $pageManagement->handlePage('register.html', $templateParameters);
}

?>