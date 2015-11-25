<?php

namespace GreenwichFreecycle\Web\Controller;

error_reporting(0);

require_once (dirname(__DIR__). '/Utilities/Autoloader.php');

use GreenwichFreecycle\Web\Business\UserManagement;
use GreenwichFreecycle\Web\Utilities\Validation;
use GreenwichFreecycle\Web\Model\Enum\ErrorCode;

main();

function main()
{
    $username = $_POST['topNavUsernameInput'];
    $password = $_POST['topNavPasswordInput'];

    $validation = new Validation;
    $validation->ValidateUsername($username);
    $validation->ValidatePassword($password);

    $userManagement = new UserManagement();
    $result = $userManagement->login($username, $password);
    if($result->worked)
    {
        header('Location: MyProfile.php');
        exit();
    } else
    {
        switch ($result->errorCode) {
            case ErrorCode::PasswordIncorrect:
                header('Location: Error.php?errorCode=' . ErrorCode::PasswordIncorrect);
                break;
            case ErrorCode::UserNotActivated:
                header("Location: Activation.php?username=$username");
                break;
        }
        exit();
    }
}

?> 