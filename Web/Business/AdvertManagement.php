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

    private function getAdvertsByUserId($userId)
    {
        $connection = ConnectionFactory::getFactory()->getConnection();
        $statement = $connection->prepare('select from Adverts where UserId = ?');
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