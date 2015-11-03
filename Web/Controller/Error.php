<?php

namespace GreenwichFreecycle\Web\Controller;

error_reporting(0);

require_once (dirname(__DIR__). '/Utilities/Autoloader.php');

use GreenwichFreecycle\Web\Model\TemplateParameter;
use GreenwichFreecycle\Web\Utilities\PageManagement;

main();

function main()
{
    $password = $_GET['errorCode'];
    outputPage();
}

function outputPage()
{
    $pageManagement = new PageManagement;
    echo $pageManagement->handlePage('error.html', null);
}

?>