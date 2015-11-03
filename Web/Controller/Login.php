<?php

namespace GreenwichFreecycle\Web\Controller;

require_once (dirname(__DIR__). '/Utilities/Autoloader.php');

use GreenwichFreecycle\Web\Business\UserManagement;

$userManagement = new UserManagement();
$result = $userManagement->login($_POST['usernameInput'], $_POST['passwordInput']);
if($result)
{
    header('Location: MyAccount.php');
    exit();
    //Set cookie and session?
} else
{
    print('Bad times!');
    // Go to error page!
}

?> 