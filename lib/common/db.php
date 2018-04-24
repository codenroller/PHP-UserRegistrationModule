<?php
//Singleton Class is used to connect to DB

class DBConn extends PDO {
    //single db connection holder
    private static $_instance = null;
    
    //private constructor
    function __construct() {
        parent::__construct('mysql:host=localhost; dbname=usereg', 'username', 'password');
    }
    
    //get db connection
    public static function dbConnection() {
        if (!(self::$_instance instanceof DBConn)) {
            try {
                self::$_instance = new DBConn();
                self::$_instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
            //in case of db connection error we save the error message to error log
            catch (Exception $e) { 
                logError($e); // logs error to the error log (user defined function)
            }
            
        }        
        return self::$_instance;//null or db connection instance
    }
}
?>