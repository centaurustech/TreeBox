<?php 
include("phpfunctions/mainfunctions.php"); //connects to database
session_start(); //for facebook login (set up in "header.php")

if(is_numeric($_GET['proj_id'])){
    $projectId = $_GET['proj_id'];    
} else{ //redirect (accessed page incorrectly)
    header("Location: http://localhost/TreeBox/index.php"); /*###########################this needs to be updated############*/
}

?>
<!DOCTYPE html>
<html>
    <head>
        <title>My Projects</title>
    
    	<!-- style stuff -->
        <link type="text/css" rel='stylesheet' href='css/mystyles/mainMyProjectsStyle.css' /> <!--this page's style stuff-->
        <link href="css/jquery-ui.min.css" type="text/css" rel="stylesheet" /><!--jQuery UI style-->
        <!-- fonts -->
        <link href='http://fonts.googleapis.com/css?family=Lato:900' rel='stylesheet' type='text/css'>
    	
    	<!-- JS and jQuery stuff -->
    	<script src="http://code.jquery.com/jquery-1.8.3.min.js"></script> <!--jQuery Library-->
        <script type="text/javascript" src="js/jquery-ui.min.js"></script> <!--jQuery UI-->
        <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?libraries=places"></script> <!--google maps places (for autocomplete)-->
        <!--*********************- include JS file for index page *********************** -->
		<script src="js/myscripts/mainMyProjectsJS.js"></script>
    </head>
    <body>
        <div id="container"> <!--closed in footer-->
        	<?php include("templates/header.php"); ?>
			<!--***************************-Beginning Page-******************************-->
			
            <div id="page_content">
            </div><!--end page_content div-->

<?php include("templates/footer.php"); ?>