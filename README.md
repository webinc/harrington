# harr
Test project for Harrington Residences.

This is a simple example website following the instructions provided in Candidate Technical Test.

Installation:
1. Download the zip file and unpack it in your xampp/htdocs folder. 
2. Navigate to http://localhost/harrington and click the button to set up the database.
3. That process enables the storage and verification of users for the website's login.

Usage:
On the homepage there are selector buttons for each of the 6 locations. Click a selector button to see the carousel for that location.

The carousels are populated by image urls and titles fed from the JSON at https://jsonplaceholder.typicode.com/photos.

With a location selected, click on 'View in 3D'. That takes you to the Tour page with that location loaded. The Tour page deploys sample interactive tours from Matterport.com using their free SDK key. The free SDK key allows display of these sample 3D showcases from a localhost server only. If this website is deployed to a public server the message "Oops, model not available." will appear instead.

The website has a registration and login form in its footer. These are linked-to from a 'login|register' menu item.
