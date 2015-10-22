<?php

namespace Web\Backend;

//include (dirname(__DIR__). '/Business/UserManagement.php');
include (dirname(__DIR__). '/Utilities/autoloader.php');

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