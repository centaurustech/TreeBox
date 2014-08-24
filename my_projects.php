<?php 
include("phpfunctions/mainfunctions.php"); 
session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>My Projects</title>
    
    	<!-- style stuff -->
        <link type="text/css" rel='stylesheet' href='css/mystyles/mainIndexStyle.css' /> <!--this page's style stuff-->
        <link href="css/jquery-ui.min.css" type="text/css" rel="stylesheet" /><!--jQuery UI style-->
        <!-- fonts -->
        <link href='http://fonts.googleapis.com/css?family=Lato:900' rel='stylesheet' type='text/css'>
    	
    	<!-- JS and jQuery stuff -->
    	<script src="http://code.jquery.com/jquery-1.8.3.min.js"></script> <!--jQuery Library-->
        <script type="text/javascript" src="js/jquery-ui.min.js"></script> <!--jQuery UI-->
        <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?libraries=places"></script> <!--google maps places (for autocomplete)-->
        <!--*********************- include JS file for index page *********************** -->
		<script src="js/myscripts/mainIndexJS.js"></script>
    </head>
    <body>
        <div id="container">
        	<?php include("templates/header.php"); ?>
			<!--***************************-Beginning Page-******************************-->
			

<?php include("templates/footer.php"); ?>