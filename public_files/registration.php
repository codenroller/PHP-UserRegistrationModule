<?php     
    include '../lib/common/environment.php';
    include '../lib/common/functions.php';
    include '../lib/common/db.php';    
    include '../lib/User.php';
    
    // start or continue session so the CAPTCHA text stored in $_SESSION is accessible
    session_start();
    header('Cache-control: private'); //Do not cache
    
    // prepare the registration form’s HTML
    ob_start(); //buffering on
    include('../templates/forms/user_registration_form.php');
    $form = ob_get_clean(); 
    
    
    //process http request
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // initialize content as an empty string
        $GLOBALS['TEMPLATE']['content'] = "";
        
        /* Validate form data */        
        // username validation
        $username = (isset($_POST['username'])) ? trim($_POST['username']) : '';   
        if ( !User::validateUsername($username) ) {
            $username = '';
            $GLOBALS['FORM_ERRORS']['username'] = "Username does not meet the requirements.";
            //echo $GLOBALS['FORM_ERRORS']['username'];
        }
        
        // password validation     
        $password = '';
        $password1 = (isset($_POST['password1'])) ? trim( $_POST['password1'] ) : '';
        $password2 = (isset($_POST['password2'])) ? trim( $_POST['password2'] ) : '';
        
        if ( User::validatePassword($password1) ) {
            if ($password1 == $password2) {
                $password = password_hash($password1, PASSWORD_BCRYPT);
            }
            else {
                $GLOBALS['FORM_ERRORS']['password'] = "Password and repeated password should match.";
                //echo $GLOBALS['FORM_ERRORS']['password'];
            }
        }
        else {
            $GLOBALS['FORM_ERRORS']['password'] = "Password does nots meet the requirements.";    
            //echo $GLOBALS['FORM_ERRORS']['password'];
        }
        
        // email validation
        $email = (isset($_POST['email']) ) ? trim($_POST['email']) : '';
        if (!User::validateEmailAddr($email)) {
            $email = '';
            $GLOBALS['FORM_ERRORS']['email'] = "The email address doesn't seem to be correct."; 
            //echo $GLOBALS['FORM_ERRORS']['email'];
        }
        
        // captcha
        $captcha = ( isset($_POST['captcha']) ) ? trim( $_POST['captcha']) : '';
        if (strtoupper($captcha) != $_SESSION['captcha']) {
            $captcha = '';   
            $GLOBALS['FORM_ERRORS']['captcha'] = "The captcha value does not match.";
            //echo $GLOBALS['FORM_ERRORS']['captcha'];
        } 
        /* End of Validate form data */

        // form data processing
        if( $username && $password && $email && $captcha ) {
            /* Processing form data */
            echo "Processing forms data...<br/>";
            
            // check if the user with a given user name already exists
            $userExists = "";
            $user = User::getByUsername($username);
            if ($user -> userid)
            {
                // add message to content that the user with a given user name already exists
                $GLOBALS['TEMPLATE']['content'] .= "<p><strong>Sorry, user with such a name already exists.</strong><br/>";
                $GLOBALS['TEMPLATE']['content'] .= "<p>Try using a different user name.</p>";      
                $userExists .= "1";
            }
            
            // check if the user with a given email already exists
            $user = User::getByEmail($_POST['email']);
            if ($user -> userid)
            {
                // add message to content that the user with a given email already exists
                $GLOBALS['TEMPLATE']['content'] .= "<p><strong>Sorry, user with such an email address already exists.</strong><br/>";
                $GLOBALS['TEMPLATE']['content'] .= "<p>Try using a different email address.</p>";
                $userExists .= "1";
            }
            
            if ($userExists) {
                // display the form
                $GLOBALS['TEMPLATE']['content'] .= $form;
            }
            else {
                // create an inactive user record
                echo "User registration in course...<br/>";
                $user = new User();
                $user -> username = $username;
                $user -> password = $password;
                $user -> email = $email;
                
                $addnew = $user -> addUser();
                $uid = $addnew['userid'];
                $token = $addnew['token'];
                
                if($token && $uid) {
                    //new user has been registered. provide account activation link
                    $GLOBALS['TEMPLATE']['content'] .= 
                        "<p>New user has been created successfully. Remember to activate your account.</p>";
                    $GLOBALS['TEMPLATE']['content'] .=
                        "<a href='./verify.php?uid=$uid&token=$token'>";
                    $GLOBALS['TEMPLATE']['content'] .=
                        "Click the link to activate your account"."</a></p>";
                }
                else {
                    $GLOBALS['TEMPLATE']['content'] .= 
                        "<p>There was a problem when registering a new user. Please try again later</p>";
                }
            }            
                     
        }
        else {
            /* Displaying validation errors */
            ob_start(); //buffering on
            include '../templates/forms/user_registration_form_errors.php';
            $form_errors = ob_get_clean(); 
            $GLOBALS['TEMPLATE']['content'] .= $form_errors; 
            
            //Display form with highlighted error fields
            $GLOBALS['TEMPLATE']['content'] .= $form;
        }     
        
    } 
    else {
        $GLOBALS['TEMPLATE']['content'] = $form;
    }
    
    
    // display the page
    include '../templates/page-template.php';
?>