<?php

namespace GreenwichFreecycle\Web\Controller;

error_reporting(0);

require_once (dirname(__DIR__). '/Utilities/Autoloader.php');

use GreenwichFreecycle\Web\Business\UserManagement;
use GreenwichFreecycle\Web\Business\AdvertManagement;
use GreenwichFreecycle\Web\Model\TemplateParameter;
use GreenwichFreecycle\Web\Model\Enum\AccountStatus;
use GreenwichFreecycle\Web\Utilities\PageManagement;
use GreenwichFreecycle\Web\Utilities\Validation;
use GreenwichFreecycle\Web\Utilities\SessionManagement;
use GreenwichFreecycle\Web\Utilities\Security;

main();

function main()
{
    $sessionManagement = new SessionManagement;
    $user = $sessionManagement->get('user');
    if ($user->AccountStatusId == AccountStatus::ReadyToPost)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            $security = new Security;
            $advertTitle = $security->cleanString($_POST['advertTitleInput']);
            $advertDescription = $security->cleanString($_POST['advertDescriptionTextarea']);
            $validation = new Validation;
            $result = $validation->validateAdvert($advertTitle, $advertDescription);
            if(!$result)
            {
            $templateParameters = array(new TemplateParameter('advertErrorMessage', 'Please give the advert a title and description'));
            outputPage($templateParameters);
            exit;
            }
            $images = $_FILES['fileInput'];
            $okayImages = $validation->validateImages($images);
            $advertManagement = new AdvertManagement;
            $advertManagement->updateAdvert($_POST['advertIdHidden'], $advertTitle, $advertDescription, $okayImages);
            header('Location: MyAdverts.php');
            exit;
        }
        if(!isset($_GET['advertId']))
        {
            header('Location: MyAdverts.php');
            exit;
        }
        $advertId = $_GET['advertId'];
        $advertManagement = new AdvertManagement;
        $advert = $advertManagement->GetAdvert($advertId);
        if ($advert->UserId != $user->UserId)
        {
            header('Location: MyAdverts.php');
            exit;
        }
        $imageHtml = new TemplateParameter('imageHtml', $advertManagement->getImageHtml($advert, true));
        $advertTitle = new TemplateParameter('advertTitle', $advert->Title);
        $advertDescription = new TemplateParameter('advertDescription', $advert->Description);
        $advertId = new TemplateParameter('advertId', $advert->AdvertId);
        $advertErrorMessage = new TemplateParameter('advertErrorMessage', '');
        outputPage(array($imageHtml, $advertTitle, $advertDescription, $advertId, $advertErrorMessage));
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