<?php

//check if there is a database connection or not. 
//And if there isn't go to make one:
    
if(!file_exists('dbcreds.php')){
    header('Location: install.php');
    exit;
}

include_once 'login_check.php';
//logged_in_class is a classname for the body element (either 'logged-in' or 'logged-out');
$logged_in_class = check_login();

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
            <h1 style="text-align:center">Long, Medium or Short Stay?</h1>
            <p style="text-align:center" class="subhead">Our serviced residences offer modern London living from buildings rooted in history</p>
            <div id="carousel-series">
                <!-- this is populated by setCarouselsWithAccordions() called by document.ready in /js/harr.js -->
            </div>
            <div id="call_to_action" class="contextual" data-cta="View in 3D" data-target="tour.php">
                <!-- all cta buttons are populated by doCTA() in harr.js -->
            </div>            
        </section>
        <footer id="h-login-register">
            <!-- login divs ar filled by document.ready in /js/harr.js, but only if body does not have 'logged-in' class -->
            <div id="h-login"></div>
            <div id="h-register"></div>
            <script src="assets/owlcarousel/owl.carousel.min.js"></script>                        
        </footer>
	</body>   
</html>
<?php ?>