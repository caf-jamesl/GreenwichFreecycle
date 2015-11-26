<?php

namespace GreenwichFreecycle\Web\Controller;

#error_reporting(0);

require_once (dirname(__DIR__). '/Utilities/Autoloader.php');

use GreenwichFreecycle\Web\Business\UserManagement;
use GreenwichFreecycle\Web\Business\AdvertManagement;
use GreenwichFreecycle\Web\Model\TemplateParameter;
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
        $advertId = $_GET['advertId'];
        if(!$advertId)
        {
            header('Location: MyAdverts.php');
            exit;
        }
        $advertManagement = new AdvertManagement;
        $advert = $advertManagement->GetAdvert($advertId);
        if ($advert->UserId != $user->UserId)
        {
            echo $advert->UserId . '+' . $user->UserId;
            header('Location: MyAdverts.php');
            exit;
        }
        $advertTitle = new TemplateParameter('advertTitle', $advert->Title);
        $advertDescription = new TemplateParameter('advertDescription', $advert->Description);
        outputPage(array($advertTitle, $advertDescription));
        exit;
    }
    else
    {
        header('Location: Index.php');
        exit;
    }
}

function outputPage($templateParameters = '')
{
    $pageManagement = new PageManagement;
    echo $pageManagement->handlePage('editadvert.html', $templateParameters);
}

?>