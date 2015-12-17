<?php

namespace GreenwichFreecycle\Web\Controller;

error_reporting(0);

require_once (dirname(__DIR__). '/Utilities/Autoloader.php');

use GreenwichFreecycle\Web\Utilities\PageManagement;
use GreenwichFreecycle\Web\Business\AdvertManagement;
use GreenwichFreecycle\Web\Model\TemplateParameter;

main();

function main()
{
    $advertId = trim($_GET['advertId']);
    $advertManagement = new AdvertManagement;
    $advert = $advertManagement->getAdvert($advertId);
    $images = $advertManagement->getImages($advertId);
    $imagesHtml = $advertManagement->getImageHtml($advert, false);
    $advertTitle = new TemplateParameter('advertTitle', $advert->Title);
    $advertDescription = new TemplateParameter('advertDescription', $advert->Description);
    $advertImages = new TemplateParameter('advertImages', $imagesHtml);
    outputPage(array($advertTitle, $advertDescription, $advertImages));
}

function outputPage($templateParameters = '')
{
    $pageManagement = new PageManagement;
    echo $pageManagement->handlePage('viewadvert.html', $templateParameters);
}

?>