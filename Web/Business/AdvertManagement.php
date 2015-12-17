<?php

namespace GreenwichFreecycle\Web\Business;

require_once (dirname(__DIR__). '/Utilities/Autoloader.php');

use GreenwichFreecycle\Web\DataAccess\ConnectionFactory;
use GreenwichFreecycle\Web\DataAccess\Database;
use GreenwichFreecycle\Web\Utilities\SessionManagement;

class AdvertManagement
{
    public function addAdvert($title, $description, $images)
    {
        $sessionManagement = new SessionManagement;
        $user = $sessionManagement->get('user');
        $advertId = $this->insertAdvert($title, $description, $user->UserId);
        $this->insertImageLocations($advertId, $images);
    }

    public function updateAdvert($advertId, $title, $description, $images)
    {
        $connection = ConnectionFactory::getFactory()->getConnection();
        $statement = $connection->prepare('Update Adverts Set Title = ?, Description = ? where advertId = ?');
        $statement->bindValue(1, $title, \PDO::PARAM_STR);
        $statement->bindValue(2, $description, \PDO::PARAM_STR);
        $statement->bindValue(3, $advertId, \PDO::PARAM_INT);
        $database = new Database;
        $database->update($statement);
        $this->insertImageLocations($advertId, $images);
    }

    public function removeAdvert($advertId)
    {
        $connection = ConnectionFactory::getFactory()->getConnection();
        $statement = $connection->prepare('delete from Adverts where AdvertId = ?');
        $statement->bindValue(1, $advertId, \PDO::PARAM_INT);
        $database = new Database;
        $database->delete($statement);
    }

     public function RemoveImage($imageId)
     {
        $connection = ConnectionFactory::getFactory()->getConnection();
        $statement = $connection->prepare('Delete from Images where ImageId = ?');
        $statement->bindValue(1, $imageId, \PDO::PARAM_INT);
        $database = new Database;
        $database->insert($statement);
     }

    public function getAdverts($userId)
    {
        return $this->getAdvertsByUserId($userId);
    }

    public function getAdvert($advertId)
    {
        $connection = ConnectionFactory::getFactory()->getConnection();
        $statement = $connection->prepare('select * from Adverts where AdvertId = ?');
        $statement->bindValue(1, $advertId, \PDO::PARAM_INT);
        $database = new Database;
        return $database->select($statement)[0];
    }

    public function getImages($advertId)
    {
        $connection = ConnectionFactory::getFactory()->getConnection();
        $statement = $connection->prepare('select * from Images where AdvertId = ?');
        $statement->bindValue(1, $advertId, \PDO::PARAM_INT);
        $database = new Database;
        return $database->select($statement);
    }

    public function getImage($imageId)
    {
        $connection = ConnectionFactory::getFactory()->getConnection();
        $statement = $connection->prepare('select * from Images where ImageId = ?');
        $statement->bindValue(1, $imageId, \PDO::PARAM_INT);
        $database = new Database;
        return $database->select($statement)[0];
    }

    public function searchAdverts($fullTextKeywords, $likeKeywords)
    {
        $connection = ConnectionFactory::getFactory()->getConnection();
        $statement = $connection->prepare('SELECT * FROM Adverts WHERE MATCH(Title, Description) AGAINST (? IN BOOLEAN MODE)');
        $statement->bindValue(1, $fullTextKeywords, \PDO::PARAM_STR);
        $database = new Database;
        $results = $database->select($statement);
        if (!$results)
        {
        $connection = ConnectionFactory::getFactory()->getConnection();
        $statement = $connection->prepare('SELECT * FROM Adverts WHERE Title REGEXP ? or Description REGEXP ?');
        $statement->bindValue(1, $likeKeywords, \PDO::PARAM_STR);
        $statement->bindValue(2, $likeKeywords, \PDO::PARAM_STR);
        $database = new Database;
        $results = $database->select($statement);
        }
        return $results;
    }

    public function createAdvertHtml($advert, $editable)
    {
        $firstImage =  $this->getImages($advert->AdvertId)[0]->Location;
        if($firstImage)
        {
        $firstImage = '../../..' . $firstImage;
        } else
        {
        $firstImage = '../Image/no-image.gif';
        }
        $insertedStamp = new \DateTime($advert->InsertedStamp);
        $date = date_format($insertedStamp, 'd/m/y');
        $time = date_format($insertedStamp, 'g:i A');
        if($editable)
        {
        $link = "../Controller/EditAdvert.php?advertId=$advert->AdvertId";
        $linkTooltip = 'Click to edit advert';
        } else
        {
         $link = "../Controller/ViewAdvert.php?advertId=$advert->AdvertId";
         $linkTooltip = 'Click to view advert';
        }
        return "
        <div class=\"search-result row\">
            <div class=\"col-xs-12 col-sm-12 col-md-3\">
                 <a href=\"#\" title=\"$advert->Title\" class=\"thumbnail\"><img src=\"$firstImage\" alt=\"User uploaded image of $advert->Title\" /></a>
            </div>
            <div class=\"col-xs-12 col-sm-12 col-md-2\">
                <ul class=\"meta-search\">
                    <li><i class=\"glyphicon glyphicon-calendar\"></i> <span>$date</span></li>
                    <li><i class=\"glyphicon glyphicon-time\"></i> <span>$time</span></li>
                </ul>
            </div>
            <div class=\"col-xs-12 col-sm-12 col-md-7 excerpet\">
                <h3><a href=\"$link\" title=\"$linkTooltip\">$advert->Title</a></h3>
                <p>$advert->Description</p>
            </div>
            <span class=\"clearfix borda\"></span>
        </div>";
    }

    public function getImageHtml($advert, $removable)
    {
    $advertManagement = new AdvertManagement;
    $images = $advertManagement->GetImages($advert->AdvertId);
    foreach ($images as $image)
    {
    if($removable)
    {
    $removeLink = "<a href=\"../Controller/RemoveConfirm.php?advertId=$advert->AdvertId&amp;imageId=$image->ImageId\">Remove image</a>";
    }
    $html = $html . "<div class=\"col-xs-12 col-sm-12 col-md-3\">
                        <a href=\"#\" title=\"$advert->Title\" class=\"thumbnail\"><img src=\"../../..$image->Location\" alt=\"User uploaded image of $advert->Title\" /></a>
                        $removeLink
                     </div>";
    }
    return $html;
    }

    private function insertAdvert($title, $description, $userId)
    {
        $connection = ConnectionFactory::getFactory()->getConnection();
        $statement = $connection->prepare('insert into Adverts (Title, Description, InsertedStamp, UserId) values (?, ?, ?, ?)');
        $statement->bindValue(1, $title, \PDO::PARAM_STR);
        $statement->bindValue(2, $description, \PDO::PARAM_STR);
        $statement->bindValue(3, date("Y-m-d H:i:s"), \PDO::PARAM_STR);
        $statement->bindValue(4, $userId, \PDO::PARAM_INT);
        $database = new Database;
        $database->insert($statement);
        return $connection->lastInsertId();
    }

    private function getAdvertsByUserId($userId)
    {
        $connection = ConnectionFactory::getFactory()->getConnection();
        $statement = $connection->prepare('select * from Adverts where UserId = ?');
        $statement->bindValue(1, $userId, \PDO::PARAM_INT);
        $database = new Database;
        return $database->select($statement);
    }

    private function insertImageLocations($advertId, $images)
    {
        foreach ($images as $image)
        {
            $connection = ConnectionFactory::getFactory()->getConnection();
            $statement = $connection->prepare('insert into Images (Location, AdvertId) values (?, ?)');
            $statement->bindValue(1, $image, \PDO::PARAM_STR);
            $statement->bindValue(2, $advertId, \PDO::PARAM_INT);
            $database = new Database;
            $database->insert($statement);
        }
    }
}

?>