<?php
session_start();
function check_login(){
    $logged_in_class="logged-out";
    
    if(isset($_GET['err'])){
        if($_GET['err'] == 1){
            $logged_in_class="login-error";
        }
    }
    //'harrington_login' is set by register.php on logging in. 
    //It is unique to this site therefore and safer than the default $_SESSION['logged-in'] = true variable.
    if (isset($_SESSION['harrington_login'])){
        if($_SESSION['harrington_login'] === true){
            $logged_in_class="logged-in";   
        }
    }
    return $logged_in_class;    
}

function get_user(){
    $usr = array();
    $name = false;

    if (isset($_SESSION['harrington_login'])){
        if($_SESSION['harrington_login'] == true){
            $name =  $_SESSION['name'];
        }
    }
    return $name;    
}



?>