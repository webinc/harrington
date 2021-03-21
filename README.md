# harrington
Test project for Harrington Residences.

This is a simple example website following the instructions provided in Candidate Technical Test.
It is packed and ready to be unzipped to a new folder in your localhost webserver (xampp).

Installation:
1. Download the zip file and unpack it in your xampp/htdocs folder. 
2. Navigate to http://localhost/{new_folder_name}.
3. The first time you visit the site, it needs to set up the database. 
4. Check the credentials and click the button to proceed.
5. That process enables the storage and verification of users for the website's login.

Usage:
On the homepage there are selector buttons for each of the 6 locations. Click a selector button to see the carousel for that location.

The carousels are populated by image urls and titles fed from the JSON at https://jsonplaceholder.typicode.com/photos.

With a location selected, click on 'View in 3D'. That takes you to the Tour page with that location loaded. The Tour page deploys sample interactive tours from Matterport.com using their free SDK key. The free SDK key allows display of these sample 3D showcases from a localhost server only. If this website is deployed to a public server the message "Oops, model not available." will appear instead.

The website has a registration and login form in its footer. These are linked-to from a 'login|register' menu item.
Currently there are no privileges for users, but you will see notifications on successful login/logout.

Finally - the call to action on the home page (default.php) goes to the corresponding location on the Tours page. 
The call to action on the Tours page doesn't go anywhere.
