<?php

namespace GreenwichFreecycle\Web\DataAccess;

class Database
{
    public function select($statement)
    {
        try{
            $statement->execute();
            return $statement->fetchAll(\PDO::FETCH_OBJ);
        }
        catch (Exception $ex){
            echo ($ex->getMessage());
            return null;
        }
    }

    public function insert($statement)
    {
        try{
            $statement->execute();
        }
        catch (Exception $ex){
            echo ($ex->getMessage);
        }
    }

    public function update($statement)
    {
        try{
            $statement->execute();
            return true;
        }
        catch (Exception $ex){
            echo ($ex->getMessage());
            return false;
        }
    }
}

?>