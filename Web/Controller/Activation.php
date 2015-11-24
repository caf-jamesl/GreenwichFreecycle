<?php

namespace GreenwichFreecycle\Web\Controller;

#error_reporting(0);

require_once (dirname(__DIR__). '/Utilities/Autoloader.php');

use GreenwichFreecycle\Web\Business\UserManagement;
use GreenwichFreecycle\Web\Model\TemplateParameter;
use GreenwichFreecycle\Web\Utilities\PageManagement;

main();

function main()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        $userManagement = new UserManagement();
        $result = $userManagement->activate($_POST['usernameInput'], $_POST['codeInput']);
        if($result->worked)
        {
            $userManagement->login($username, $password);
            header('Location: MyAccount.php');
            exit;
        } else
        {
            $usernameInputErrorMessage = $result->message;
            outputPage('','', $usernameInputErrorMessage);
            exit;
        }
    }
    outputPage($_GET['username'], $_GET['activationCode'], '');
}

function outputPage($username = '', $activationCode = '', $usernameInputErrorMessage = '')
{
    $templateParameters = array(new TemplateParameter(activationCode, $activationCode), new TemplateParameter(username, $username), new TemplateParameter(usernameInputErrorMessage, $usernameInputErrorMessage));
    $pageManagement = new PageManagement;
    echo $pageManagement->handlePage('activation.html', $templateParameters);
}

?>