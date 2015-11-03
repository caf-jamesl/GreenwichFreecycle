<?php

namespace GreenwichFreecycle\Web\Controller;

error_reporting(0);

require_once (dirname(__DIR__). '/Utilities/Autoloader.php');

use GreenwichFreecycle\Web\Model\TemplateParameter;
use GreenwichFreecycle\Web\Utilities\PageManagement;
use GreenwichFreecycle\Web\Utilities\SessionManagement;

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
    echo $pageManagement->handlePage('myadverts.html', null);
}

?>