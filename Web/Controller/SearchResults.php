<?php

namespace GreenwichFreecycle\Web\Controller;

error_reporting(0);

require_once (dirname(__DIR__). '/Utilities/Autoloader.php');

use GreenwichFreecycle\Web\Utilities\PageManagement;
use GreenwichFreecycle\Web\Utilities\Security;
use GreenwichFreecycle\Web\Business\AdvertManagement;
use GreenwichFreecycle\Web\Model\TemplateParameter;

main();

function main()
{
    $security = new Security;
    $keywords = $security->cleanString($_GET['keywords']);
    $postcode = $security->cleanString($_GET['postcodeInput']);
    $distance = $security->cleanString($_GET['distanceInput']);
    $resultsOrder = $security->cleanString($_GET['resultsOrderSelect']);
    $numberOfResults = $_GET['resultsNumberSelect'];
    $fullTextKeywords = str_replace(' ', '*, ', $keywords) . '*';
    $likeKeywords =  str_replace(' ', '|', $keywords);
    $advertManagement = new AdvertManagement;
    $results = $advertManagement->searchAdverts($fullTextKeywords, $likeKeywords, $postcode, $distance, $resultsOrder);
    $results = array_slice($results, 0, $numberOfResults);
    $searchResultsHtml = createSearchResults($results);
    $searchNumber = new TemplateParameter('searchNumber', count($results));
    $searchKeywords = new TemplateParameter('searchKeywords', $keywords);
    outputPage(array($searchResultsHtml, $searchNumber, $searchKeywords));
}

function createSearchResults($adverts)
{
     $advertManagement = new AdvertManagement;
     foreach ($adverts as $advert)
     {
     $html = $html . $advertManagement->createAdvertHtml($advert, false);
     }
     if (!$html)
     {
     $html = '<br/>Sorry, no results have been found. Please try a different search.';
     }
    return new TemplateParameter('searchResults', $html);
}

function outputPage($templateParameters = '')
{
    $pageManagement = new PageManagement;
    echo $pageManagement->handlePage('searchresults.html', $templateParameters);
}

?>