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
    $keywords = trim($_GET['keywords']);
    $numberOfResults = $_GET['resultsNumberSelect'];
    $fullTextKeywords = str_replace(' ', '*, ', $keywords) . '*';
    $likeKeywords =  str_replace(' ', '|', $keywords);
    $advertManagement = new AdvertManagement;
    $results = $advertManagement->searchAdverts($fullTextKeywords, $likeKeywords);
    $results = array_slice($results, 0, $numberOfResults);
    $searchResultsHtml = createSearchResults($results);
    $searchNumber = new TemplateParameter('searchNumber', count($results));
    $searchKeywords = new TemplateParameter('searchKeywords', trim($_GET['keywords']));
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