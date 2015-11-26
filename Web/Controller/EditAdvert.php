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

main();

function main()
{
    $sessionManagement = new SessionManagement;
    $user = $sessionManagement->get('user');
    if ($user->AccountStatusId == AccountStatus::ReadyToPost)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            $advertTitle =  $_POST['advertTitleInput'];
            $advertDescription = $_POST['advertDescriptionTextarea'];
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
        $imageHtml = new TemplateParameter('imageHtml', getImageHtml($advert));
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

function getImageHtml($advert)
{
    $advertManagement = new AdvertManagement;
    $images = $advertManagement->GetImages($advert->AdvertId);
    foreach ($images as $image)
    {
    $html = $html . "<div class=\"col-xs-12 col-sm-12 col-md-3\">
                        <a href=\"#\" title=\"$advert->Title\" class=\"thumbnail\"><img src=\"../../..$image->Location\" alt=\"User uploaded image of $advert->Title\" /></a>
                        <a href=\"../Controller/RemoveConfirm.php?advertId=$advert->AdvertId&amp;imageId=$image->ImageId\">Remove image</a>
                     </div>";
    }
    return $html;
}

function outputPage($templateParameters = '')
{
    $pageManagement = new PageManagement;
    echo $pageManagement->handlePage('editadvert.html', $templateParameters);
}

?>