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

	if (!empty($_POST)){
		//Define query 
		$ratingType = $_POST['rating_type'];
		if($ratingType == 3){ //based on rating type = what type of recipient id needs to be accepted (userId or projectId)
			$reviewRecipient = 'project_id';
		} else{
			$reviewRecipient = 'recipient_id';
		}

		$authorId = $_POST['author_id'];
		$recipientId = $_POST['recipient_id'];
		$stars = $_POST['rating_stars'];
		$content = mysql_real_escape_string(htmlentities(trim(strip_tags($_POST['review_content']))));;
		$title = mysql_real_escape_string(htmlentities(trim(strip_tags($_POST['review_title']))));;

        $query = "INSERT INTO ratings(author_id, {$reviewRecipient}, stars, review_title, review_content, rating_type) 
            VALUES('$authorId', '$recipientId', '$stars', '$title', '$content', '$ratingType')"; //default date_submitted is current timestamp in db
        @mysql_query($query, $dbc);
        $successArray = array();
        if (mysql_affected_rows($dbc) == 1) { //something changed
            $successArray["success"] = true;
        } else {
            $successArray["success"] = false;
        }

        //change the has_ratings to 1
        if($ratingType == 3){
        	$total = 0;
        	$n = 0;
        	$query = "SELECT stars  
	        	FROM ratings 
	        	WHERE project_id={$recipientId}";
	        if ($result = @mysql_query($query, $dbc)) {
		        while ($row = mysql_fetch_array($result)) { //still results
		        	$total += $row['stars'];
		        	$n++;
		        } //End of while loop
		    }
		    $avg = round($total / $n, 1); //round to one decimal place
	        $query = "UPDATE projects
		        SET avgRating='$avg', totalRatings='$n'
		        WHERE project_id={$recipientId}";
		} else{
			$total = 0;
        	$n = 0;
        	$query = "SELECT stars  
	        	FROM ratings 
	        	WHERE user_id={$recipientId}";
	        if ($result = @mysql_query($query, $dbc)) {
		        while ($row = mysql_fetch_array($result)) { //still results
		        	$total += $row['stars'];
		        	$n++;
		        } //End of while loop
		    }
		    $avg = round($total / $n, 1); //round to one decimal place
			$query = "UPDATE users
		        SET avgRating='$avg', totalRatings='$n'
		        WHERE user_id={$recipientId}";
		}
		@mysql_query($query, $dbc);
        
	    /*encoded like:
	    {
	    	"success" : "true"
	    }
	    */
	    echo json_encode($successArray); 
	    //print_array($successArray);
	} //end if(isset($_POST))
?>