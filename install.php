<?php

?>
<!DOCTYPE html>
<html>
	<head>
        <?php readfile('page_head.html'); ?>
	</head>
  <body class="install">
    <div>
      <div class="center-textblock">
        <p style="font-size: 2.5em;margin:1em;text-align:center">Set up and Connect the Database</p>
        <p></p>
        <p>This website presumes you are installing on your remote localhost with default MySQL settings of username: 'root' and no password.</p>
        <p>If this is the case then <b>change nothing in the form below and just click "Create and connect database"</b></p>
        <p>If you are running it on a different server or with different MySQL connection credentials. Enter them below so it can create the database for you.</p>
        <p>NB: The website loads sample 3D touring imagery from <a href="https://matterport.com" target="_blank">Matterport</a>. The free SDK api key only allows viewing on your remote localhost server and will not work on a public webserver.</p>
      </div>
    </div>
    <div class="install_box register">
      <form id="install_creds" action="utils.php" method="post" autocomplete="off">
          <label for="host">Host: </label>
          <input type="text" name="host" id="host" value="localhost" />
          <label for="username">Username: </label>
          <input type="text" name="username" id="username" value="root" />
          <label for="password">Password: </label>
          <input type="text" name="password" placeholder="leave blank by default" id="password" value="" />    
          <input hidden type="text" name="install" value="install" >
          <input id="submit-register" class="submit-button" type="submit" value="Create and connect database">                
      </form>
    </div>
  </body>
  <footer>
  </footer>
</html>
<?php
?>