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
    $keywords = $_GET['keywords'];
    $keywords = str_replace(" ", "*, ", $keywords);
    $advertManagement = new AdvertManagement;
    $results = $advertManagement->searchAdverts($keywords);
    $searchResultsHtml = createSearchResults($results);
    outputPage($searchResultsHtml);
}

function createSearchResults($adverts)
{
     $advertManagement = new AdvertManagement;
     foreach ($adverts as $advert)
     {
     $html = $html . $advertManagement->createAdvertHtml($advert);
     }
     if (!$html)
     {
     $html = '<br/>Sorry, no results have been found. Please try a different search.';
     }
    return array(new TemplateParameter('searchResults', $html));
}

function outputPage($templateParameters = '')
{
    $pageManagement = new PageManagement;
    echo $pageManagement->handlePage('searchresults.html', $templateParameters);
}

?>