<?php

namespace GreenwichFreecycle\Web\Controller;

error_reporting(0);

require_once (dirname(__DIR__). '/Utilities/Autoloader.php');

use GreenwichFreecycle\Web\Model\TemplateParameter;
use GreenwichFreecycle\Web\Utilities\PageManagement;

main();

function main()
{
    outputPage();
}

function outputPage()
{
    $pageManagement = new PageManagement;
    echo $pageManagement->handlePage('index.html', null);
}

?>