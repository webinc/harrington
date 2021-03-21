<?php
include_once 'login_check.php';
//logged_in_class is a classname for the body element (either 'logged-in' or 'logged-out');
$logged_in_class = check_login();
//this is for contextually displaying the same location from one page to the next.
$loc = ''; $hasloc = '';
if(isset($_GET['loc'])){
    $loc = $_GET['loc'];
    $hasloc = 'haslocation';
}
?>
<!DOCTYPE html>
<html>
	<head>
    <?php readfile('page_head.html'); ?>
	</head>
	<body class="<?php echo $logged_in_class . ' ' . $hasloc ?>" data-location = "<?php echo $loc ?>">
        <header id="h-nav">
                <?php readfile('nav.php'); ?>
        </header>
        <section>
            <h1 style="text-align:center">Take a Stroll Around Your New Residence...</h1>
            <div id="residence_buttons" class="h_accordion_buttons">

            </div>
            <div id="interactive_viewing">
                <!-- inserted from jQuery in harr.js -->
                <iframe id="showcase" width="853" height="480" src="" frameborder="0" allowfullscreen allow="xr-spatial-tracking"></iframe>
            </div>
            <div id="call_to_action" data-cta="Check Availability" data-target="#">
                <!-- all cta buttons are populated by doCTA() in harr.js -->
            </div>
        </section>
        <footer id="h-login-register">
            <!-- login divs ar filled by document.ready in /js/harr.js, but only if body does not have 'logged-in' class -->
            <div id="h-login"></div>
            <div id="h-register"></div>
        </footer>
	</body> 
</html>
<?php ?>