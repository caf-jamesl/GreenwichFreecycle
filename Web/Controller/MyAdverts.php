<?php

namespace GreenwichFreecycle\Web\Controller;

error_reporting(0);

require_once (dirname(__DIR__). '/Utilities/Autoloader.php');

use GreenwichFreecycle\Web\Business\UserManagement;
use GreenwichFreecycle\Web\Business\AdvertManagement;
use GreenwichFreecycle\Web\Model\TemplateParameter;
use GreenwichFreecycle\Web\Utilities\PageManagement;
use GreenwichFreecycle\Web\Utilities\SessionManagement;

main();

function main()
{
    $userManagement = new UserManagement;
    if ($userManagement->isLoggedIn())
    {
        $sessionManagement = new SessionManagement;
        $userId = $sessionManagement->get('user')->UserId;
        $advertManagement = new AdvertManagement;
        $adverts = $advertManagement->getAdverts($userId);
        $searchResults = createSearchResults($adverts);
        outputPage($searchResults);
        exit;
    }
    else
    {
        header('Location: Index.php');
        exit;
    }
}

function createSearchResults($adverts)
{
     $advertManagement = new AdvertManagement;
     foreach ($adverts as $advert)
     {
     $html = $html . $advertManagement->createAdvertHtml($advert);
     }
    return array(new TemplateParameter(searchResults, $html));
}

function outputPage($templateParameters = '')
{
    $pageManagement = new PageManagement;
    echo $pageManagement->handlePage('myadverts.html', $templateParameters);
}

?>