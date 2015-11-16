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
      //  $this->insertImageLocations($advertId, $images);
    }

    public function removeAdvert()
    {
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

    private function insertImageLocations($advertId, $images)
    {
        foreach ($images as $image)
        {
            $connection = ConnectionFactory::getFactory()->getConnection();
            $statement = $connection->prepare('insert into Images (Location) values (?)');
            $statement->bindValue(1, $image, \PDO::PARAM_STR);
            $database = new Database;
            $database->insert($statement);
    
        }
    }
}

?>