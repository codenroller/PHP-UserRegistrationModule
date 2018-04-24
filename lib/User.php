<?php
class User
{
    private $userid; // user id
    private $fields; // other record fields
    
    // initialize a User object
    public function __construct()
    {
        $this->userid = null;
        $this->fields = array('username'=>'',
            'password' =>'',
            'email' =>'',
            'isactive' => 0);
    }
    
    // override magic methods get properties
    public function __get($field)
    {
        if ($field == 'userid')
        {
            return $this->userid;
        }
        else if (array_key_exists($field, $this->fields))
        {
            return $this->fields[$field];
        }
        
    }
    
    // override magic methods set properties
    public function __set($field, $value)
    {
        if (array_key_exists($field, $this->fields))
        {
            $this->fields[$field] = $value;
        }
    }
    
    // return if username is valid format
    public static function validateUsername($username)
    {
        return preg_match('/^[A-Za-z0-9]{3,20}$/', $username);
    }
    
    // return if password is valid format
    public static function validatePassword($password)
    {
        //allowed password characters: A-Z, a-z, 0-9
        //password must contain at least one character in a-z range
        //password must contain at least one character in A-Z range
        //password must contain at least one digit
        //password must be at least 8 characters long
        $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/';
        
        return preg_match($pattern, $password);
    }
    
    // return if email address is valid format
    public static function validateEmailAddr($email)
    {
        $test = filter_var($email, FILTER_VALIDATE_EMAIL);
        if ($email && $test==$email) {
            return 1;
        }
        return 0;
    }
    
    // return an object populated based on the record’s user id
    public static function getById($user_id)
    {
        $user = new User();
        
        $db = DBConn::dbConnection();
        
        if (!$db) {
            exit ("Error: Unable to connect to database server");
        }
        
        try {
            //select user by userid
            $st = $db->prepare(
                "SELECT userid, username, password, email, isactive
             FROM usereg.user
             WHERE userid = :userid");
            $st->bindParam(':userid', $user_id, PDO::PARAM_INT);
            $st->execute();
            
            $row = $st->fetch(PDO::FETCH_ASSOC);            
        }
        catch (Exception $e) {
            // save error to the error log
            logError($e);
        }
        
        if ($row) {    
            $user -> userid = $row['userid'];
            $user -> username = $row['username'];
            $user -> password = $row['password'];
            $user -> email = $row['email'];
            $user -> isactive = $row['isactive'];
        }
               
        return $user;
    }
    
    // return an object populated based on the record’s username
    public static function getByUsername($username)
    {
        $user = new User();
        
        // if username does not match the allowed pattern 
        // return an empty user object and do not go any further
        if( !self::validateUsername($username) ) {
            return $user;
        }
        
        $db = DBConn::dbConnection();
        
        if (!$db) {
            exit ("Error: Unable to connect to database server");
        }
        
        try {
            $st = $db->prepare(
                "SELECT userid, username, password, email, isactive
             FROM usereg.user
             WHERE username = :username");
            $st->bindParam(':username', $username, PDO::PARAM_STR);
            $st->execute();
            
            $row = $st->fetch(PDO::FETCH_ASSOC);
        }
        catch (Exception $e) {
            //log error
            logError($e);
        }
        
        if ($row) {
            $user -> userid = $row['userid'];
            $user -> username = $row['username'];
            $user -> password = $row['password'];
            $user -> email = $row['email'];
            $user -> isactive = $row['isactive'];
        } 
       
        return $user;
    }
    
    // return an object populated based on the user's email address
    public static function getByEmail($email)
    {
        $user = new User();
        
        // if email address does not match the allowed pattern
        // return an empty user object and do not go any further
        if( !self::validateEmailAddr($email) ) {
            return $user;
        }
        
        $db = DBConn::dbConnection();
        
        if (!$db) {
            exit ("Error: Unable to connect to database server");
        }
        
        try {
            $st = $db->prepare(
                "SELECT userid, username, password, email, isactive
             FROM usereg.user
             WHERE email = :email");
            $st->bindParam(':email', $email, PDO::PARAM_STR);
            $st->execute();
            
            $row = $st->fetch(PDO::FETCH_ASSOC);
        }
        catch (Exception $e) {
            //log error
            error_log("{$e->getMessage()} in {$e->getFile()} @ {$e->getLine()}\nTrace: {$e->getTraceAsString()}\n");
        }
        
        if ($row) {
            $user -> userid = $row['userid'];
            $user -> username = $row['username'];
            $user -> password = $row['password'];
            $user -> email = $row['email'];
            $user -> isactive = $row['isactive'];
        }
        
        return $user;
    }
    
    public function save() {
        /* Saves user record to DB
         * Returns 1 in case of success and 0 in case of failure
         */
        $res = 0;
                
        if ($this->userid) {
            $res = $this->updateUser();
        }
        else {
            $res = $this->addNewUser();
            // update user id in case of success
            if ($res) {
                $this->userid = $res;
                $res = 1;
            }
        }
    }
    
    private function addNewUser(){
        /* Saves new application user to DB. 
         *  Returns new user id on success and 0 on failure
         */
        
        $res = 0; //operation result
        
        //connecting to db
        $db = DBConn::dbConnection();        
        if (!$db) {
            exit ("Error: Unable to connect to database server");
        }
                   
        try {
            // adding new user user table
            $st = $db->prepare("INSERT INTO usereg.user (username, password, email)
                            VALUES(:username, :password, :email)");
            
            $st->bindParam(':username', $this->fields['username'], PDO::PARAM_STR);
            $st->bindParam(':password', $this->fields['password'], PDO::PARAM_STR);
            $st->bindParam(':email', $this->fields['email'], PDO::PARAM_STR);
                      
            $op = $st->execute(); 
            
            if ($op) {
                $res = $db->lastInsertId();
            }                      
        }
        catch (Exception $e) {
            // save error message to application error log
            logError($e);
        }
        // save changes to DB        
        return $res;
    }
        
    // update user data in db
    private function updateUser()
    {
        /* Updates existing user data 
         * Returns 1 in case of success and 0 in case of failure
         */
        
        //operation result
        $res = 0;      
        
        //connecting to db
        $db = DBConn::dbConnection();
        
        if (!$db) {
            exit ("Error: Unable to connect to database server");
        }
        
        try {
            $st = $db->prepare("UPDATE usereg.user
                                 SET username = :username,
                                     password = :password,
                                     email = :email,
                                     isactive = :isactive
                                 WHERE userid=:userid");
            
            $st->bindParam(':userid', $this->userid, PDO::PARAM_INT);
            $st->bindParam(':username', $this->fields['username'], PDO::PARAM_STR);
            $st->bindParam(':password', $this->fields['password'], PDO::PARAM_STR);
            $st->bindParam(':email', $this->fields['email'], PDO::PARAM_STR);
            $st->bindParam(':isactive', $this->fields['isactive'], PDO::PARAM_INT);
            
            $op = $st->execute();
            $rows = $st->rowCount();
            
            if ($op) {
                if ($rows===0) {
                    $res = 1; //operation was successful; no changes were applied
                }
                else if ($rows===1) {
                    $res = 1; //operation was successful; one row was affected
                }
                else if ($rows > 1) {
                    throw new Exception('Warning! Something went wrong. There might be a problem with data integrity.');
                }
            }
        }
        catch (Exception $e) {
            // save error to the error log
            logError($e);
        }
        return $res;     
    }
    
    // Sets the existing user record as inactive. Returns user activation token
    public function setInactive()
    {
        $res = 0;
               
        // update user status in user table
        $this -> isactive = 0;
        // save changes to DB
        $this -> save();
        $token = random_text(5);
        
        // Add user to user_pending table; 
        
        //connect to db
        $db = DBConn::dbConnection();                      
        if (!$db) {
            exit ("Error: Unable to connect to database server");
        }
        
        try {
            //insert user data to user_pending
            $st = $db->prepare("INSERT INTO usereg.user_pending
                                (userid, token)
                                VALUES (:userid, :token)");
            
            $st->bindParam(':userid', $this->userid, PDO::PARAM_INT);
            $st->bindParam(':token', $token, PDO::PARAM_STR);
            
            $res = (int) $st->execute();
        }
        catch (Exception $e) {
            //log error
            logError($e);
        }        
        return $res;
    }
       
    // clear the user’s pending status and set the record as active
    public function setActive($token)
    {
        $res = 0;
                
        //connecting to db
        $db = DBConn::dbConnection();
        
        if (!$db) {
            exit ("Error: Unable to connect to database server");
        }
        
        //step 1: Check if the user is in the user_pending table
        try {
            $st = $db->prepare("SELECT userid, token FROM usereg.user_pending WHERE userid=:userid and token=:token");
            $st->bindParam(':userid', $this->userid, PDO::PARAM_INT);
            $st->bindParam(':token', $token, PDO::PARAM_STR);
            
            $st->execute();
            $rows = $st->rowCount();
        }
        catch (Exception $e) {
            //log error
            logError($e);
        }
        
        if( !$rows ) {
            return 0; //failure
        }
        
        
        //step 2: If the user was found, then delete user from user_pending table
        try {
            $st = $db->prepare("DELETE FROM usereg.user_pending WHERE userid=:userid and token=:token");
            $st->bindParam(':userid', $this->userid, PDO::PARAM_INT);
            $st->bindParam(':token', $token, PDO::PARAM_STR);
            
            $del = $st->execute();
            
            $delRows = $st->rowCount();
            
            if ($delRows > 1) {
                throw new Exception('Warning! Something went wrong. There might be a problem with data integrity.');
            }
        }
        catch (Exception $e) {
            //log error
            logError($e);
        }    
        
        if ( $delRows===1) {
            $this -> fields['isactive'] = 1;
            if ( $this->updateUser() ) {
                $res = 1;
            }
        }
        
        return $res;    
    }
}
?>