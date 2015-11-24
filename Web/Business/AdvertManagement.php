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

    public function removeAdvert()
    {
    }

    public function getAdverts($userId)
    {
        return $this->getAdvertsByUserId($userId);
    }

    public function getImages($advertId)
    {
        $connection = ConnectionFactory::getFactory()->getConnection();
        $statement = $connection->prepare('select * from Images where AdvertId = ?');
        $statement->bindValue(1, $advertId, \PDO::PARAM_INT);
        $database = new Database;
        return $database->select($statement);
    }

    public function createAdvertHtml($advert)
    {
        $firstImage = $this->getImages($advert->AdvertId)[0]->Location;
        return "
        <div class=\"search-result row\">
            <div class=\"col-xs-12 col-sm-12 col-md-3\">
                 <a href=\"#\" title=\"$advert->Title\" class=\"thumbnail\"><img src=\"../../..$firstImage\" alt=\"Lorem ipsum\" /></a>
            </div>
            <div class=\"col-xs-12 col-sm-12 col-md-2\">
                <ul class=\"meta-search\">
                    <li><i class=\"glyphicon glyphicon-calendar\"></i> <span>02/15/2014</span></li>
                    <li><i class=\"glyphicon glyphicon-time\"></i> <span>4:28 pm</span></li>
                    <li><i class=\"glyphicon glyphicon-tags\"></i> <span>People</span></li>
                </ul>
            </div>
            <div class=\"col-xs-12 col-sm-12 col-md-7 excerpet\">
                <h3><a href=\"#\" title=\"\">$advert->Title</a></h3>
                <p>$advert->Description</p>
            </div>
            <span class=\"clearfix borda\"></span>
        </div>";
    }

    private function insertAdvert($title, $description, $userId)
    {
        $connection = ConnectionFactory::getFactory()->getConnection();
        $statement = $connection->prepare('insert into Adverts (Title, Description, UserId) values (?, ?, ?)');
        $statement->bindValue(1, $title, \PDO::PARAM_STR);
        $statement->bindValue(2, $description, \PDO::PARAM_STR);
        $statement->bindValue(3, $userId, \PDO::PARAM_INT);
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