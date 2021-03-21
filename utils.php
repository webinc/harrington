<?php

if(isset($_POST['install'])){
    generate_database();
}

function randomDbName($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function generate_database(){
    $un = $_POST['username'];
    $pw = $_POST['password'];
    $host = $_POST['host'];
    $db = "harr_" . randomDbName(7);

    $msg = ""; $error = false;

    // Create connection
    $conn = new mysqli($host, $un, $pw);
    // Check connection
    if ($conn->connect_error) {
        $msg .= "Connection failed: " . $conn->connect_error;
        $error = true;
    }

    if(!$error){
        // Create database
        $sql = "CREATE DATABASE " . $db ;
        if ($conn->query($sql) === TRUE) {
        $msg .= "<p>Database created successfully </p> ";
        } else {
        $msg .= "Error creating database: " . $conn->error;
        $error = true;
        }        
    }


    $link = mysqli_connect("localhost", "root", "", $db );
    
    // Check connection
    if($link === false && !$error){
        $msg .= ("ERROR: Could not connect. " . mysqli_connect_error());
        $error = true;
    }
    
    if(!$error){
        // Attempt create table query execution
        $sql = "CREATE TABLE accounts (
        `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
        `username` varchar(50) NOT NULL,
        `password` varchar(255) NOT NULL,
        `email` varchar(100) NOT NULL
        )";        
    }


    if(mysqli_query($link, $sql)){
        $msg .= "<p>Table created successfully.</p>";
        //generate db_credentials file here:
        if(file_exists('dbcreds.php')){
            unlink('dbcreds.php');
        }
        //create the variables as file contents:
        $vars = "$"."host = '" . $host ."';\n"."$"."password = '" . $pw."';\n";
        $vars .= "$"."username = '" . $un ."';\n" ."$"."database = '" .$db ."';" ;
        $var = "<?p"."hp \n". "\n" ;
        $var .= $vars ;
        $var .= "\n\n ?>";
        file_put_contents('dbcreds.php', $var);
        $msg .= "<p>That all worked great! </p>";
        $msg .= "<p>We created a new database in your MySQL called " . $db ;
        $msg .= ". This contains a single table 'accounts' for collecting and verifying usernames, passwords and emails.</p> ";
        $msg .= "<p>Please note: For emails to be sent to new registrations, you will need to have an SMTP server configured ";
        $msg .= "in your php.ini and your local sendmail.ini file.</p><p>For instructions for how to configure this please see ";
        $msg .= "<a href='https://meetanshi.com/blog/send-mail-from-localhost-xampp-using-gmail/' target='_blank'>this page<i class='fas fa-external-link-alt'></i></a></p>";
        $msg .= "<p><b><a href='default.php'>Continue to Website <i class='far fa-hand-point-right'></i></a></b></p>";

    } else{
        $msg .=  "ERROR: Could not execute table creation $sql. " . mysqli_error($link);
    }
    
    ob_start();
    ?>
        <!DOCTYPE html>
        <html>
            <head>
                <?php readfile('page_head.html'); ?>
            </head>
                <body class="install">
                    <div class="center-textblock" style="margin-top:10%">
                        <?php echo $msg ?>
                    </div>
                </body>
        </html>
    <?php
    echo ob_get_clean();
}
?>