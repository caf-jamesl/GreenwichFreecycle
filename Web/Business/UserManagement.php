<?php

namespace GreenwichFreecycle\Web\Business;

require_once (dirname(__DIR__). '/Utilities/Autoloader.php');

use GreenwichFreecycle\Web\Utilities\Security;
use GreenwichFreecycle\Web\Utilities\Email;
use GreenwichFreecycle\Web\Utilities\SessionManagement;
use GreenwichFreecycle\Web\Model\Result;
use GreenwichFreecycle\Web\DataAccess\ConnectionFactory;
use GreenwichFreecycle\Web\DataAccess\Database;

class UserManagement
{
    public function login($username, $password)
    {
        $user = $this->getUser($username);
        if(!$this->checkPassword($user, $password))
        {
            return new Result(false, "Sorry, there is something wrong with your login details. Please check them and try again. $user->Username $password");
        }
        SessionManagement::instance()->set('user', $user);
        return new Result(true);
    }

    public function logout()
    {
        SessionManagement::instance()->remove('user');
        return new Result(true);
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

    public function activate($username, $activationCode)
    {
        if (!$this->getUser($username))
        {
            return new Result(false, 'Sorry, that username cannot be found in our database. Please check your it and try again.');
        }
        $correctActivationCode = $this->getActivationCode($username);
        if ($correctActivationCode === $activationCode)
        {
            $this->actvateUser($username);
            return new Result(true);
        }
        return new Result(false, 'Sorry, that activation code was incorrect. Please check it and try again');
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

    private function actvateUser($username)
    {
        $connection = ConnectionFactory::getFactory()->getConnection();
        $statement = $connection->prepare('UPDATE Users SET AccountStatusId=? WHERE Username = ?');
        $statement->bindValue(1, 2, \PDO::PARAM_INT);
        $statement->bindValue(2, $username, \PDO::PARAM_STR);
        $database = new Database;
        $database->update($statement);
    }

    private function checkPassword($user, $password)
    {
        $security = new Security;
        return $security->phpVerify($password, $user->Password);
    }

    private function getActivationCode($username)
    {
        $security = new Security;
        $hashedDecimalUsername = hexdec($security->md5Hash($username));
        return substr($hashedDecimalUsername, 2, 5);
    }

    private function sendConfirmation($username, $email, $activationCode)
    {
        $subject = 'Greenwich Freecycle Registration Confirmation';
        $message = "Hello $username \r\n"
                    . "Thank you for signing up to Greenwich Freecycle. "
                    . "please click the link below to complete you're registration. \r\n"
                    . "http://stuweb.cms.gre.ac.uk/~lj231/GreenwichFreecycle/Web/Controller/Activation.php?activationCode=$activationCode&username=$username \r\n"
                    . "Here is your activation code: $activationCode";
        $email = new Email($email, $subject, $message);
        $email->send();
    }
}

?>