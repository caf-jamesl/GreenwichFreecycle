<?php

namespace GreenwichFreecycle\Web\Controller;

error_reporting(0);

require_once (dirname(__DIR__). '/Utilities/Autoloader.php');

use GreenwichFreecycle\Web\Business\UserManagement;
use GreenwichFreecycle\Web\Business\AdvertManagement;
use GreenwichFreecycle\Web\Model\Enum\AccountStatus;
use GreenwichFreecycle\Web\Utilities\PageManagement;
use GreenwichFreecycle\Web\Utilities\Validation;
use GreenwichFreecycle\Web\Utilities\SessionManagement;

main();

function main()
{
    $sessionManagement = new SessionManagement;
    $user = $sessionManagement->get('user');
    if ($user->AccountStatusId == AccountStatus::ReadyToPost)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            $validation = new Validation;
            $images = $_FILES['fileInput'];
            $okayImages = $validation->validateImages($images);
            $advertManagement = new AdvertManagement;
            $advertManagement->addAdvert($_POST['advertTitleInput'], $_POST['advertDescriptionTextarea'], $okayImages);
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
    echo $pageManagement->handlePage('addadvert.html', null);
}

?>