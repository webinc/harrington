<?php

if(isset($_GET['incorrect'])){
    echo first_login_page($_GET['incorrect'], 'h_incorrect_info');
    return false;
}

//connect to MySQL if 'authenticate' is sent with this call.
//'authenticate' is a name attribute of a hidden field on both register and login forms
if(isset($_POST['authenticate'])){
    include 'dbcreds.php';
    $hst = $host;
    $usr = $username;
    $pwd = $password;
    $dbe = $database;
    /*
    $usr = 'harr';
    $pwd = 'harr_287&';
    $dbe = 'harr_reg';
    */
    $curr = $_SERVER['REQUEST_URI'];
    if($_POST['authenticate'] == 'register'){
        register_new_account($hst, $usr, $pwd, $dbe, $curr);
    } elseif($_POST['authenticate'] == 'login'){
        login_existing_account($hst, $usr, $pwd, $dbe, $curr);
    }
} else {
    echo "Registration was not called correctly. Please go <a href='default.php'>back.</a>" ;
    return false;
}

function goToFail($curr, $msg){
    header('location: ' . $curr . '?err=1');
}

function login_existing_account($hst, $usr, $pwd, $dbe, $curr){
    $con = mysqli_connect($hst, $usr, $pwd, $dbe);
    // class gets changed to 'success' only on successful login
    $class = 'danger';

    // Try and connect using the info above.
    if (mysqli_connect_errno()) {
        // If there is an error with the connection, stop the script and display the error.
        $msg = 'Failed to connect to MySQL: ' . mysqli_connect_error();
        $class = 'danger';
    }
    // Check if the data from the login form was submitted.
    if ( !isset($_POST['username'], $_POST['password']) ) {
        // Could not get the data that should have been sent.
        $msg = 'Please fill both the username and password fields!';
    }
    // Prepare the SQL to prevent SQL injection.
    if ($stmt = $con->prepare('SELECT id, password FROM accounts WHERE username = ?')) {
        $stmt->bind_param('s', $_POST['username']);
        $stmt->execute();
        // Store the result so we can check if the account exists in the database.
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $password);
            $stmt->fetch();
            // Account exists, now we verify the password.
            // Note: remember to use password_hash in your registration file to store the hashed passwords.
            if (password_verify($_POST['password'], $password)) {
                // Verification success! User has logged-in!
                // Create sessions, so we know the user is logged in, they basically act like cookies but remember the data on the server.
                session_start();
                session_regenerate_id();
                //$_SESSION['loggedin'] = TRUE;
                $_SESSION['harrington_login'] = TRUE;
                $_SESSION['name'] = $_POST['username'];
                $_SESSION['id'] = $id;
                //echo 'Welcome ' . $_SESSION['name'] . '!';
                $msg = 'Welcome! You have logged-in successfully';
                $class = 'success';
                header('Location: default.php?lg=1&un=' . $_POST['username']);
                exit;
            } else {
                // Incorrect password
                //echo 'Incorrect username and/or password!';
                //goToFail('default.php', 'incorrect user or password');
                $msg = 'Incorrect password. Please try again.';
                //header('location: register.php?incorrect=password');
                //exit;
            }
        } else {
            // Incorrect username
            $msg = 'Incorrect username. <a href="default.php" >Please try again</a>';
            //header('location: register.php?incorrect=username');
            //exit;            
        } 
        //$response = array('msg' => $msg, 'class' => $class);
        goToFail('default.php', $msg);
    }    
    $stmt->close();
}

function register_new_account($hst, $usr, $pwd, $dbe, $curr){
    $con = mysqli_connect($hst, $usr, $pwd, $dbe);
    // Try and connect using the info above.
    if (mysqli_connect_errno()) {
        // If there is an error with the connection, stop the script and display the error.
        exit('Failed to connect to MySQL: ' . mysqli_connect_error());
    }

    // Now we check if the data was submitted, isset() function will check if the data exists.
    if (!isset($_POST['username'], $_POST['password'], $_POST['email'])) {
        // Could not get the data that should have been sent.
        exit('Please complete the registration form!');
    }
    // Make sure the submitted registration values are not empty.
    if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['email'])) {
        // One or more values are empty.
        exit('Please complete the registration form');
    }

    // We need to check if the account with that username exists.
    if ($stmt = $con->prepare('SELECT id, password FROM accounts WHERE username = ?')) {
        // Bind parameters (s = string, i = int, b = blob, etc), hash the password using the PHP password_hash function.
        $stmt->bind_param('s', $_POST['username']);
        $stmt->execute();
        $stmt->store_result();
        // Store the result so we can check if the account exists in the database.
        if ($stmt->num_rows > 0) {
            // Username already exists
            echo 'Username exists, please choose another!';
        } else {
            // Username doesnt exist, validate the entry fields
            if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                exit('Email is not valid!');
            }
            if (preg_match('/^[a-zA-Z0-9]+$/', $_POST['username']) == 0) {
                exit('Username is not valid!');
            }
            if (strlen($_POST['password']) > 20 || strlen($_POST['password']) < 5) {
                exit('Password must be between 5 and 20 characters long!');
            }        
            
            //insert new account
            if ($stmt = $con->prepare('INSERT INTO accounts (username, password, email) VALUES (?, ?, ?)')) {
                // We do not want to expose passwords in our database, so hash the password and use password_verify when a user logs in.
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $stmt->bind_param('sss', $_POST['username'], $password, $_POST['email']);
                $stmt->execute();
                //You have successfully registered, you can now login!;
                echo(first_login_page($_POST['username'],$_POST['password']));                
                send_registration_email($_POST['username'],$_POST['email']);
            } else {
                // Something is wrong with the sql statement, check to make sure accounts table exists with all 3 fields.
                echo 'Could not prepare statement!';
            }
        }
        $stmt->close();
    } else {
        // Something is wrong with the sql statement, check to make sure accounts table exists with all 3 fields.
        echo 'Could not prepare statement!';
    }
    $con->close();    
}

function send_registration_email($name,$email){
    // the message
        $msg = "Dear " . $name ."\n ";
        $msg = $msg."Thank you for registering. \n";
    // the subject
        $subj = $name . " - You have successfully registered.";

        $headers =  'MIME-Version: 1.0' . "\r\n"; 
        $headers .= 'From: Harry <harrington@test.com>' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n"; 

        // use wordwrap() if lines are longer than 70 characters
        $msg = wordwrap($msg,70);
        // send email
        
        echo "<p style='text-align:center'>";
        if (mail($email, $subj, $msg, $headers)) {
            echo "Email successfully sent to ". $email;
        } else {
            echo "Email sending failed to " . $email;
        }
        echo "</p>";        
}

function first_login_page($user, $pw){
    //get a welcome login screen for new registrants
    ob_start();
    ?>
    <!DOCTYPE html>
    <html>
        <head>
            <?php readfile('page_head.html'); ?>
        </head>
        <body class="<?php echo $logged_in_class ?>">
        <!-- logged_in_class is either logged-in or logged-out -->
            <header id="h-nav">
                    <?php readfile('nav.php'); ?>
            </header>
            <section>    
                <div class="welcome-screen">

                    <div style="text-align:center;margin-top:50px;">
                        <?php if($pw !== 'h_incorrect_info'){ ?>
                            <p>Thank you for registering. You may now login:</p>
                        <?php } else { ?>
                            <p style="color:red;">Incorrect username or password. Please try again</p>
                        <?php } ?>
                    </div>
                    <div id="h-login" data-un="<?php echo $user ?>" data-pw="<?php echo $pw ?>">
                    <?php readfile("login.html"); ?>
                    </div>
                </div>
            </section>
        </body>
    </html>
    <?php
    return ob_get_clean();
}

?>