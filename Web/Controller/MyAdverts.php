<?php

namespace GreenwichFreecycle\Web\Controller;

error_reporting(0);

require_once (dirname(__DIR__). '/Utilities/Autoloader.php');

use GreenwichFreecycle\Web\Business\UserManagement;
use GreenwichFreecycle\Web\Model\TemplateParameter;
use GreenwichFreecycle\Web\Utilities\PageManagement;

main();

function main()
{
        $advertManagement = new AdvertManagement;
        $adverts = $advertManagement->getAdverts();
        $searchResults = createSearchResults($adverts);

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

function createSearchResults()
{
    $pageManagement = new PageManagement;
    echo $pageManagement->handlePage('myadverts.html', null);
}

function outputPage()
{
    $pageManagement = new PageManagement;
    echo $pageManagement->handlePage('myadverts.html', null);
}

?>