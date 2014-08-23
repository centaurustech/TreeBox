<?php 
include("phpfunctions/mainfunctions.php"); 
session_start();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>TreeBox</title>
    
    	<!-- style stuff -->
        <link type="text/css" rel='stylesheet' href='css/mainstyle.css' />
        <link href="css/jquery-ui.min.css" type="text/css" rel="stylesheet">
        <!-- fonts -->
        <link href='http://fonts.googleapis.com/css?family=Lato:900' rel='stylesheet' type='text/css'>
    	
    	<!-- JS and jQuery stuff -->
    	<script src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
        <script type="text/javascript" src="js/jquery-ui.min.js"></script> <!--jQuery UI-->
        <script src="https://maps.googleapis.com/maps/api/js"></script> <!--google maps API-->
        <!--*********************- include JS file for index page *********************** -->
		<script src="js/myscripts/mainIndexJS.js"></script>
    </head>
    <body>
        <div id="container">
        	<?php include("templates/header.php"); ?>
			<!--***************************-Beginning Page-******************************-->
			<!--<div id="left_pane">
			    <?php //include("templates/add_project_form.php"); ?> <!-******div id="add_project"-->
			    <!--<div id="ads"></div>->
			</div>-->

			<div id="map_canvas"></div>
			<!--see "js/myscripts/mainIndexJS.js" will display a custom message 
				to this div if user clicks on a marker-->
			<div id="map_message" style="display:none;"></div>

			<div id="projects_near_you">
            </div>

<?php include("templates/footer.php"); ?>
