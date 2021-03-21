<?php
session_start();
ob_start();
?>
<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="#">
              <img alt="Brand" src="img/logo-white.png">
            </a>
        </div>      
            <ul class="nav justify-content-end">
              <li class="nav-item"><a class="nav-link" href="default.php">Home</a></li>
              <li class="nav-item"><a class="nav-link" href="tour.php">Tour</a></li>
              <li class="nav-item showifloggedout"><a class="nav-link sliding-link" href="#h-login">Login | Register</a></li>
              <li class="nav-item showifloggedin"><a class="nav-link" href="logout.php">Logout</a></li>                                
            </ul>
    </div>
</nav>
<div id="loginMessage" class="alert noheight" style="text-align:center"></div>
<?php 
return ob_get_clean();
?>