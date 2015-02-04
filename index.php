<?php 
include("phpfunctions/mainfunctions.php"); 
session_start(); //for facebook login (set up in "header.php")
?>

<!DOCTYPE html>
<html>
    <head>
        <title>TreeBoks</title>
    
    	<!-- style stuff -->
        <link type="text/css" rel='stylesheet' href='css/mystyles/mainIndexStyle.css' /> <!--this page's style stuff-->
        <link href="css/jquery-ui.min.css" type="text/css" rel="stylesheet" /><!--jQuery UI style-->
        <link href="js/raty-2.7.0/lib/jquery.raty.css" type="text/css" rel="stylesheet" /><!--Raty (star ratings) style-->
        <!-- fonts -->
        <link href='http://fonts.googleapis.com/css?family=Lato:900' rel='stylesheet' type='text/css'> <!--Lato Font-->
    	
    	<!-- JS and jQuery stuff -->
    	<script src="http://code.jquery.com/jquery-1.8.3.min.js"></script> <!--jQuery Library-->
        <script type="text/javascript" src="js/jquery-ui.min.js"></script> <!--jQuery UI-->
        <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?libraries=places"></script> <!--google maps places (for autocomplete)-->
        <script src="js/oms.min.js"></script> <!--OverlappingMarkerSpiderfier library-->
        <script type="text/javascript" src="js/raty-2.7.0/lib/jquery.raty.js"></script> <!--Raty (star ratings) library-->
        <!--*********************- include JS file for index page *********************** -->
		<script src="js/myscripts/mainIndexJS.js"></script> <!--main JS for this page-->
    </head>
    <body>
        <div id="container">
        	<?php include("templates/header.php"); ?>
			<!--***************************-Beginning Page-******************************-->
			<!--<div id="left_pane">
			    <?php //include("templates/add_project_form.php"); ?> <!-******div id="add_project"-->
			    <!--<div id="ads"></div>->
			</div>-->

            <div id="page_content">
    			<div id="map_canvas"></div>
    			<!--see "js/myscripts/mainIndexJS.js" will display a custom message 
    				to this div if user clicks on a marker-->
    			<div id="map_message" style="display:none;"></div>

    			<div id="projects_near_you">
                    <input type="text" name="location_search" id="location_search" class="location_search_controls" placeholder="Enter a location" style="display: none;">
                    <ul id="viewable_locs" style="display: none;">
                        <li id="united_states" class="viewable_locs_li">United States</li><li id="singapore_malaysia" class="viewable_locs_li">Singapore/Malaysia</li>
                    </ul>

                    <h1 id='nearby_projs_heading'>Projects near you:</h1>
                    <div id="display_nearby_projs"> 
                        <p id="location_prompt">Please enter a location on the map to find the nearest the projects</p>
                    </div>
                </div>
            </div>
            
<?php include("templates/footer.php"); ?>
