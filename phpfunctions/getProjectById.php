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
			
			//format the description
            $projectDescrip = $row['project_description'];
            if(strlen($projectDescrip) > 230){
            	$abridged = substr($row['project_description'], 0, 230);
            	$cutoff = strrpos($abridged, ' '); //find the end of the last word
	            $projectDescrip = substr($row['project_description'], 0, $cutoff) . "...";
            }

			$dt = date_create($row['project_datetime']);
			$date = date_format($dt, 'l F j, Y');
			$projectHasExpired = false;
		    if($row['hasExpired'] == 1)
		        $projectHasExpired = true;
			//ie. g:ia \o\n l jS F Y
			//output = 5:45pm on Saturday 24th March 2012

			$now = date('m/d/Y h:i:s a', time()); //current date time
			$secondsUntilProject = strtotime($row['project_datetime']) - strtotime($now); //difference in seconds
			$daysUntilProject = round($secondsUntilProject / 86400, 0); //number of days
			$hoursUntilProject = round($secondsUntilProject / 3600, 0);


            /*if (isset($row['tags'])) { //If tags column is set for this project
                $tags = $row['tags']; //and add to array
            }*/
            $projectInfoArray = array("project_id" => "{$row['project_id']}",
            	"user_id" => "{$row['user_id']}",
        		"project_name" => "{$row['project_name']}",
        		"project_description" => $projectDescrip,
        		"project_date" => "{$date}",
        		"project_time" => "{$row['project_time']}",
        		"days_until_project" => $daysUntilProject,
        		"hours_until_project" => $hoursUntilProject,
        		"project_hasExpired" => "{$row['hasExpired']}",
        		"project_address" => "{$row['loc_formatted_address']}",
        		"lat" => "{$row['location_lat']}",
        		"lng" => "{$row['location_lng']}",
        		"avgRating" => "{$row['avgRating']}",
        		"totalRatings" => "{$row['totalRatings']}");
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
    		"days_until_project" => daysUntilProject,
    		"hours_until_project" => hoursUntilProject,
    		"project_hasExpired" => hasExpired (boolean)
    		"project_address" : project_address (formatted), 
			"lat" : location_lat,
			"lng" : location_lng,
			"avgRating" : avgRating,
			"totalRatings" : totalRatings
	    }
	    */
	    echo json_encode($projectInfoArray); 
	    //print_array($projectInfoArray);
	}
?>