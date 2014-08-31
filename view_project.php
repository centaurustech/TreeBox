<?php 
include("phpfunctions/mainfunctions.php"); //connects to database
session_start(); //for facebook login (set up in "header.php")

if(is_numeric($_GET['proj_id'])){
    $projectId = $_GET['proj_id'];    
} else{ //redirect (accessed page incorrectly)
    header("Location: http://localhost/TreeBox/index.php"); /*###########################this needs to be updated############*/
}

$query = "SELECT * FROM projects
        WHERE user_id = {$userId} AND hasExpired = 0
        ORDER BY project_datetime ASC"; 
if ($result = @mysql_query($query, $dbc)) { //successful query
    $projectHost = $row['user_id'];
    $projectName = $row['project_name'];
    $projectTime = $row['project_time'];
    $projectAddr = $row['loc_formatted_address'];

    //format the description
    $projectDescrip = $row['project_description'];
    if(strlen($projectDescrip) > 170){
        $projectDescrip = substr($row['project_description'], 0, 170) . "...";
    }

    //format datetime
    $dt = date_create($row['project_datetime']);
    $date = date_format($dt, 'l F jS, Y');
} else { //Query didn't run (later redirect to an error page) /*#######################change this to error page##########################*/
    print '<p style="border: red; color: red;">Error, something occurred which prevented the query from executing. ' 
        . mysql_error($dbc) . '</p>';
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