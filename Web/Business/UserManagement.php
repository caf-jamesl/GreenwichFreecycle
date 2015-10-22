<?php

namespace GreenwichFreecycle\Web\Business;

require_once (dirname(__DIR__). '/Utilities/Autoloader.php');

use GreenwichFreecycle\Web\Utilities\Security;
use GreenwichFreecycle\Web\Utilities\Email;
use GreenwichFreecycle\Web\Model\Result;
use GreenwichFreecycle\Web\DataAccess\ConnectionFactory;
use GreenwichFreecycle\Web\DataAccess\Database;

class UserManagement
{
    public function login($username, $password)
    {
        $user = $this->getUser($username);
        $this->checkPassword($user, $password);
    }

    public function register($username, $password, $email)
    {
        $security = new Security;
        $hashedPassword = $security->phpHash($password);
        if ($this->getUser($username))
        {
            return new Result(false, "Sorry, that username has already been taken. Please choose another.");
        }
        $this->addUser($username, $hashedPassword, $email);
        $activationCode = $this->getActivationCode($username);
        $this->sendConfirmation($username, $email, $activationCode);
        return new Result(true);
    }

    private function getUser($username)
    {
        $database = null;
        $connection = ConnectionFactory::getFactory()->getConnection();
        $statement = $connection->prepare('select * from Users where Username = ?');
        $statement->bindValue(1, $username, \PDO::PARAM_STR);
        $database = new Database;
        return $database->select($statement)[0];
    }

    private function addUser($username, $hashedPassword, $email)
    {
        $connection = ConnectionFactory::getFactory()->getConnection();
        $statement = $connection->prepare('insert into Users (Username, Password, Email, AccountStatusId) values (?, ?, ?, ?)');
        $statement->bindValue(1, $username, \PDO::PARAM_STR);
        $statement->bindValue(2, $hashedPassword, \PDO::PARAM_STR);
        $statement->bindValue(3, $email, \PDO::PARAM_STR);
        $statement->bindValue(4, 1, \PDO::PARAM_INT);
        $database = new Database;
        $database->insert($statement);
    }

    private function checkPassword($user, $password)
    {
        $security = new Security;
        return $security->phpVerify($user->Password, $password);
    }

    private function getActivationCode($username)
    {
        $security = new Security;
        $hashedDecimalUsername = hexdec($security->md5Hash($username));
        return substr($hashedDecimalUsername, 2, 5);
    }

    private function sendConfirmation($username, $email, $activationCode)
    {
        $subject = "Greenwich Freecycle Registration Confirmation";
        $message = "Hello $username \r\n"
                    . "Thank you for signing up to Greenwich Freecycle. "
                    . "please click the link below to complete you're registration. \r\n"
                    . "http://www.google.com \r\n"
                    . "Here is your activation code: $activationCode";
        $email = new Email($email, $subject, $message);
        $email->send();
    }
}

?>