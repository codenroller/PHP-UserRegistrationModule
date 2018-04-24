<?php
    require '..\lib\common\environment.php';
    require '..\lib\common\functions.php';
    require '..\lib\common\db.php';
    
    require '..\lib\User.php';
    
    print "All the libraries have loaded successfully. <br/>";   
    
    
    $u = new User();
    $u -> username = 'Ralph';
    $u -> password = password_hash('Ralph1990', PASSWORD_BCRYPT, ["cost" => 10]); //we have to hash the password
    $u -> email = 'ralph1990@example.com';
    var_dump($u);
    
    //validate username
    print "<p>User name validation: ".User::validateUsername($u->username)."</p>";
    //validate user password
    print "<p>Password validation: ".User::validatePassword($u->password)."</p>";
    //validate user email
    print "<p>Email validation: ".User::validateEmailAddr($u->email)."</p>";
    
    //Add new user to DB
    $userAdded = $u->addUser();
    print "<p>User has been added to DB: ".$userAdded."</p>";
    
    
    

?>