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
    $userManagement = new UserManagement;
    if ($userManagement->isLoggedIn())
    {        
        if ($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            $title = $_POST['titleInput'];
            $firstname = $_POST['firstnameInput'];
            $lastname = $_POST['lastnameInput'];
            $postcode = $_POST['postcodeInput'];
            $userManagement->updateCurrentUser($title, $firstname, $lastname, $postcode);
            header('Location: MyAdverts.php');
            exit;
        }
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
    echo $pageManagement->handlePage('myprofile.html', null);
}

?>