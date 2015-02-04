<?php 
include("phpfunctions/mainfunctions.php"); 
session_start(); //for facebook login (set up in "header.php")
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Add your project to TreeBox!</title>
    
    	<!-- style stuff -->
        <link type="text/css" rel='stylesheet' href='css/mystyles/mainAddProjectStyle.css' /><!--add_project page's style stuff-->
        <link href="css/jquery-ui.min.css" type="text/css" rel="stylesheet"> <!--jQuery UI style-->

        
    	<!-- JS and jQuery stuff -->
    	<script src="http://code.jquery.com/jquery-1.8.3.min.js"></script> <!--jQuery Library-->
        <script src="js/jquery.validate.min.js"></script> <!--Form validation-->
        <script type="text/javascript" src="js/jquery-ui.min.js"></script> <!--jQuery UI-->
        <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?libraries=places"></script> <!--google maps places (for autocomplete and location verification)-->
    	<script src="js/myscripts/mainAddProjectJS.js"></script> <!--******************************** include JS for add_project *************************************-->
    </head>
    <body>
        <div id="container">
        	<?php include("templates/header.php");?>
<!--***********************start content**********************-->
<div id='page_content'>
	<div id="add_project">
		<?php
		$projectSubmitted = false;
		if (!empty($_POST)){ //Check if project_form.php is submitted
			//debugging $projectLocLat = $_POST['hidden_loc_address']; print $projectLocLat;}
			//Validate form
		    $problems = false;
			$error_message = "";
			$errorCounter = 0;

			function validateForm($form_element, $errorMessage){
				if (empty($form_element)) {
		        	$problems = true;
		        	$errorCounter++;
		        	return "<li class='error'>{$errorMessage}</li>";
		    	}
		    	return "";
			}
		    //Check if any required fields are empty
		    $error_message .= validateForm($_POST['project_name'], "Please give your project a name.");
		    $error_message .= validateForm($_POST['project_description'], "Please give your project a description.");
		    $error_message .= validateForm($_POST['project_loc'], "Please give your project a location.");
		    if ($_POST['hidden_loc_lat'] == 0) {
		        $problems = true;
		        $errorCounter++;
		        $error_message .= "<li class='error'>Please provide a valid location.</li>";
		   	}

		    //Validate date
		    $dateRegExp = '/^((((0[13578])|([13578])|(1[02]))[\/](([1-9])|([0-2][0-9])|(3[01])))|(((0[469])|([469])|(11))[\/](([1-9])|([0-2][0-9])|(30)))|((2|02)[\/](([1-9])|([0-2][0-9]))))[\/]\d{4}$|^\d{4}$/';
		    if (empty($_POST['project_date'])) {
		        $problems = true;
		        $error_message .= '<li>Please enter a date for your project.</li>';
		    } elseif (!preg_match($dateRegExp, $_POST['project_date'])) {
		    	$problems = true;
	    		$error_message .= '<li>Please enter a valid date for your project (mm/dd/yyyy).</li>';
			}

		    /*************************If no problems execute query*****************/
		    if (!$problems) {
		        //Set INSERT query variables
		        $projectName = mysql_real_escape_string(htmlentities(trim(strip_tags($_POST['project_name']))));
		        $projectDescription = mysql_real_escape_string(htmlentities(trim(strip_tags($_POST['project_description']))));
		        
		        //Get the location values
		        $projectLocLat = $_POST['hidden_loc_lat'];
		        $projectLocLng = $_POST['hidden_loc_lng'];
		        $projectLocAddress = mysql_real_escape_string(htmlentities(trim(strip_tags($_POST['hidden_loc_address']))));
		        $projectLocCity = mysql_real_escape_string(htmlentities(trim(strip_tags($_POST['hidden_loc_city']))));
		        $projectLocState = mysql_real_escape_string(htmlentities(trim(strip_tags($_POST['hidden_loc_state']))));
		        $projectLocZip = mysql_real_escape_string(htmlentities(trim(strip_tags($_POST['hidden_loc_zip']))));
		        $projectLocCountry = mysql_real_escape_string(htmlentities(trim(strip_tags($_POST['hidden_loc_country']))));
		        $projectLocFormattedAddress = mysql_real_escape_string(htmlentities(trim(strip_tags($_POST['hidden_loc_formatted_address']))));
		        
		        //Format the Time
				$projectTimeMin = "";
				if($_POST['select_minute'] == 0){
					$projectTimeMin = "0" . $_POST['select_minute'];
				} else
					$projectTimeMin = $_POST['select_minute'];
		        $projectTime = $_POST['select_hour'] . ':' . $projectTimeMin . ' ' . $_POST['select_period'];

		        //Make a DateTime object (NOTE that the time is also stored separately)
		        $projectDate = mysql_real_escape_string(htmlentities(trim(strip_tags($_POST['project_date']))));
		        $projectDate = new DateTime($projectDate . ' ' . $projectTime); //convert to date time formatting
		        $projectDate = $projectDate->format('Y-m-d H:i'); //convert back to string (trust me this is necessary, mysql will not do it for you)

		        //Check crowdfunding checkbox
		        $crowdfunding = false;
		        if(isset($_POST['crowdfunding_checkbox'])){
		        	$crowdfunding = true;
		        }
		        
		        //Define query (note that $userId is retrieved by header.php)
		        $query = "INSERT INTO projects(project_name, user_id, project_description, project_datetime, project_time, 
		        		location_lat, location_lng, location_address, location_city, location_state, location_zipcode, 
		        		location_country, loc_formatted_address) 
		            VALUES('$projectName', '$userId', '$projectDescription', '$projectDate', '$projectTime',  
		            	'$projectLocLat', '$projectLocLng', '$projectLocAddress', '$projectLocCity', '$projectLocState', '$projectLocZip', 
		            	'$projectLocCountry', '$projectLocFormattedAddress')";
		        executeQuery($query, "Project added to the map!");
		        if($crowdfunding){
					$id = mysql_insert_id();
		        	header("Location: http://localhost/TreeBox/wepay_setup.php?projectId=" . $id);
		        	die();
		        }

		        //$projectId = mysql_insert_id();

		        //for sticky form to clear form
		        $projectSubmitted = true;
		    } elseif ($problems) { //Fields are empty or incorrect
		    	$pluralErrors = "";
		    	if($errorCounter == 1){
		    		$pluralErrors = " is 1 error";
		    	} else
		    		$pluralErrors = " are {$errorCounter} errors";
		        print "<p class='error'>Please make sure you filled out the entire form correctly. There {$pluralErrors}.</p>
		        	<ul class='ul_error'>{$error_message}</ul><br/>";
		    } //end of query if
		}//End of form submit if*/

		$project_form_action = "add_project.php";
		$project_form_id = "add_project_form";
		$project_form_legend = "ADD YOUR PROJECT";
		$project_form_element_class = "add_project";
		$project_form_submit_button_value = "Add this project!";
		include("templates/project_form.php"); 
		?>
	</div><!--*********End add_project div***********-->
</div>

<?php include("templates/footer.php"); ?>