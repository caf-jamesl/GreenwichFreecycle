<?php

namespace GreenwichFreecycle\Web\DataAccess;

class ConnectionFactory{

    private static $factory;
    private static $db;

    public static function getFactory(){
        if (!self::$factory){
            self::$factory = new ConnectionFactory();
            self::$db = NULL;
        }
        return self::$factory;
    }

    public function getConnection(){
        if (is_null(self::$db))
            try{
                self::$db = new \PDO(   'mysql:host=studb.cms.gre.ac.uk;dbname=mdb_lj231;charset=utf8mb4', 'lj231', 'cartxz8Y',
                                        array(
                                            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                                            \PDO::ATTR_PERSISTENT => false
                                        )
                                    );
            }
            catch(\PDOException $ex){
                print($ex->getMessage());
                return null;
            }
        return self::$db;
    }

    public function closeConnection(){
        if (! is_null(self::$db)){
            self::$db->close();
            self::$db = null;
        }
    }
}

?>