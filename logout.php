<?php
session_start();   

unset($_SESSION['harrington_login']);
unset($_SESSION['name']);
$_SESSION['harrington_login'] = false;
$_COOKIE['PHPSESSID'] = '0';
session_unset();
session_destroy();
session_write_close();
session_regenerate_id(true);
header('Location: default.php?logout=1');

?>