<?php
/*
	will be called by "js/mainIndexJS.js"
	gets all the project information of the project with the sent id in the GET
		will be displayed when the user clicks on a marker on the map on "index.php"
*/
	include("mainfunctions.php"); //connects to database

function print_array($array) {
// Print a nicely formatted array representation (for debugging data)
  echo '<pre>';
  print_r($array);
  echo '</pre>';
}

	if(isset($_GET['getProjectId'])){
		//Define query for an user-selected poll
		/*$query = "SELECT location_lat, location_lng  FROM projects 
		WHERE (lat and lng) - (location_lat and lng) < .01  LIMIT 100"; //limit markers by location and number*/
        $query = "SELECT * FROM projects 
        	WHERE project_id = {$_GET['getProjectId']} LIMIT 1"; 
        if ($result = @mysql_query($query, $dbc)) {
            $row = mysql_fetch_array($result);
			
			$dt = date_create($row['project_datetime']);
			$date = date_format($dt, 'l F jS, Y');
			$projectHasExpired = false;
		    if($row['hasExpired'] == 1)
		        $projectHasExpired = true;
			//ie. g:ia \o\n l jS F Y
			//output = 5:45pm on Saturday 24th March 2012

            /*if (isset($row['tags'])) { //If tags column is set for this project
                $tags = $row['tags']; //and add to array
            }*/
            $projectInfoArray = array("project_id" => "{$row['project_id']}",
            	"user_id" => "{$row['user_id']}",
        		"project_name" => "{$row['project_name']}",
        		"project_description" => "{$row['project_description']}",
        		"project_date" => "{$date}",
        		"project_time" => "{$row['project_time']}",
        		"project_hasExpired" => "{$row['hasExpired']}",
        		"project_address" => "{$row['loc_formatted_address']}",
        		"lat" => "{$row['location_lat']}",
        		"lng" => "{$row['location_lng']}");
        } else { //Query didn't run
	        print '<p style="border: red; color: red;">Error, something occurred which prevented the query from executing. ' 
	        	. mysql_error($dbc) . '</p>';
	    }

	    /*encoded like:
	    {
			"project_id" : project_id,
			"user_id" : user_id,
    		"project_name" : project_name,
    		"project_description" : project_description,
    		"project_date" : project_date (formatted),
    		"project_time" : project_time,
    		"project_hasExpired" => hasExpired (boolean)
    		"project_address" : project_address (formatted), 
			"lat" : location_lat,
			"lng" : location_lng
	    }
	    */
	    echo json_encode($projectInfoArray); 
	    //print_array($projectInfoArray);
	}
?>