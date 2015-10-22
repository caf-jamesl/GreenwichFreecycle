<?php

namespace GreenwichFreecycle\Web\Backend;

include_once (dirname(__DIR__). '/Business/UserManagement.php');

$userManagement = new UserManagement();
$result = $userManagement->login($_POST["usernameInput"], $_POST["passwordInput"]);
if($result)
{
    header("Location: Page/MyAccount.html");
    exit();
    //Set cookie and session?
} else
{
    print("Bad times!");
    // Go to error page!
}

?> 