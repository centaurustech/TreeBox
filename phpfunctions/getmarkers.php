<?php
/*
	will be called by "js/mainIndexJS.js"
	gets the first 100 markers from the server table "projects" and sends them
		back to be displayed on the map on "index.php"

	ALSO checks for expired projects and marks them as expired
*/
include("mainfunctions.php"); //connects to database

function print_array($array) {
// Print a nicely formatted array representation (for debugging data)
  echo '<pre>';
  print_r($array);
  echo '</pre>';
}

//Check for expiry/set expiry
$query = "UPDATE projects
	SET hasExpired=1
	WHERE project_datetime <= NOW() + INTERVAL 1 DAY";
executeQuery($query);


	if(isset($_GET['getmarkers'])){
		//Define query for an user-selected poll
		/*$query = "SELECT location_lat, location_lng  FROM projects 
		WHERE (lat and lng) - (location_lat and lng) < .01  LIMIT 100"; //limit markers by location and number*/
        $query = "SELECT project_id, project_name, location_lat, location_lng  
        	FROM projects 
        	WHERE hasExpired=0
        	LIMIT 100"; 
        if ($result = @mysql_query($query, $dbc)) {
	        $markerArray = array(); //multidimensional array
	        while ($row = mysql_fetch_array($result)) { //still results
				//stored in array as [project_id => lat,lng]
	        	$markerArray[]= array("project_id" => "{$row['project_id']}",
	        		"project_name" => "{$row['project_name']}",
	        		"lat" => "{$row['location_lat']}",
	        		"lng" => "{$row['location_lng']}");
	        } //End of while loop
	    } else { //Query didn't run
	        print '<p style="border: red; color: red;">Error, something occurred which prevented the query from executing. ' 
	        	. mysql_error($dbc) . '</p>';
	    }

	    /*encoded like:
	    {
			"0": {
				"project_id" : project_id,
				"project_name" : project_name
				"lat" : location_lat,
				"lng" : location_lng
			}
			"1": {
				"project_id" : project_id,
				"project_name" : project_name
				"lat" : location_lat,
				"lng" : location_lng
			}
	    }
	    */
	    echo json_encode($markerArray); 
	    //print_array($markerArray);
	}
?>