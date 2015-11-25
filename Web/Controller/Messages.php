<?php

namespace GreenwichFreecycle\Web\Controller;

error_reporting(0);

require_once (dirname(__DIR__). '/Utilities/Autoloader.php');

use GreenwichFreecycle\Web\Business\UserManagement;
use GreenwichFreecycle\Web\Utilities\PageManagement;

main();

function main()
{
    $userManagement = new UserManagement;
    if ($userManagement->isLoggedIn())
    {
        outputPage();
        exit;
    }
    else
    {
        header('Location: Index.php');
        exit;
    }
}

function outputPage()
{
    $pageManagement = new PageManagement;
    echo $pageManagement->handlePage('messages.html', null);
}

?>