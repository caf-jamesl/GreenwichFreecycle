<?php

namespace GreenwichFreecycle\Web\Backend;

require_once (dirname(__DIR__). '/Utilities/Autoloader.php');

use GreenwichFreecycle\Web\Business\UserManagement;

$userManagement = new UserManagement();
$result = $userManagement->register($_POST["usernameInput"], $_POST["passwordInput"], $_POST["emailInput"]);
if($result.worked)
{
    // print($result);
    header("Location: Page/MyAccount.html");
    exit();
    //Set cookie and session?
} else
{
    print($result.message);
    // Go to error page!
}

?>