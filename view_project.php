<?php 
include("phpfunctions/mainfunctions.php"); //connects to database
session_start(); //for facebook login (set up in "header.php")

$projectId = $_GET['proj_id'];  

$query = "SELECT * FROM projects
        WHERE project_id = {$projectId}"; 
if ($result = @mysql_query($query, $dbc)) { //successful query
    $row = mysql_fetch_array($result);

    $projectUserId = $row['user_id'];
    $projectName = $row['project_name'];
    //format the description
    $projectDescrip = $row['project_description'];

    /*project datetime stuff*/
    $projectTime = $row['project_time'];
    //format datetime
    $dt = date_create($row['project_datetime']);
    $date = date_format($dt, 'l F j, Y');
    $projectHasExpired = false;
    if($row['hasExpired'] == 1)
        $projectHasExpired = true;

    /*project location stuff*/
    $projectAddr = $row['loc_formatted_address'];
    $projectLat = $row['location_lat'];
    $projectLng = $row['location_lng'];
    $avgRating = $row['avgRating'];
    $totalRatings = $row['totalRatings'];
} else { //Query didn't run (later redirect to an error page) /*#######################change this to error page##########################*/
    //redirect (accessed page incorrectly)
    header("Location: http://localhost/TreeBox/index.php"); /*###########################this needs to be updated############*/
}

//Get host user's name:
$query = "SELECT * FROM users
        WHERE user_id = {$projectUserId}"; 
if ($result = @mysql_query($query, $dbc)) { //successful query
    $row = mysql_fetch_array($result);
    $projectHost = $row['first_name'] . " " . $row['last_name'];
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">  
        <title><?php echo $projectName . " - TreeBoks"; ?></title>
    
    	<!-- style stuff -->
        <link type="text/css" rel='stylesheet' href='css/mystyles/mainViewProjectStyle.css' /> <!--this page's style stuff-->
        <link href="css/jquery-ui.min.css" type="text/css" rel="stylesheet" /><!--jQuery UI style-->
        <link href="js/raty-2.7.0/lib/jquery.raty.css" type="text/css" rel="stylesheet" /><!--Raty (star ratings) style-->
    	
    	<!-- JS and jQuery stuff -->
    	<script src="http://code.jquery.com/jquery-1.8.3.min.js"></script> <!--jQuery Library-->
        <script type="text/javascript" src="js/jquery-ui.min.js"></script> <!--jQuery UI-->
        <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?libraries=places"></script> <!--google maps places (includes autocomplete)-->
        <script type="text/javascript" src="js/raty-2.7.0/lib/jquery.raty.js"></script> <!--Raty (star ratings) library-->
        <!--*********************- no external JS file for this page *********************** -->
    </head>
    <body>
        <div id="container"> <!--closed in footer-->
        	<?php include("templates/header.php");
            
            //Check whether user has already submitted a review for this project (if so, he/she cannot do so again: is prevented at the bottom of code)
            $userHasWrittenReview = false;
            $query = "SELECT * FROM ratings
                WHERE project_id = {$projectId} AND author_id = {$userId}"; //$userId from header.php
            if ($result = @mysql_query($query, $dbc)) { //successful query
                if (mysql_num_rows($result) > 0) {
                    $userHasWrittenReview = true;
                }
            }
            ?>
            <!--script starts here to use $userId variable from header.php-->
<script>
    //embedded cause the use of PHP variables retrieved from database is used in JS code (project address)
    function getDirections(originLocation, destinationMarker, directionsRenderer, map){
        var directionsService = new google.maps.DirectionsService();

        var originMarker = new google.maps.Marker({
            position: originLocation, 
            title: "Start location",
            icon: "images/marker_directions_icon_start.png",
            map: map
        });
        var geocoder = new google.maps.Geocoder();
        geocoder.geocode({'latLng': originLocation}, function(results){
            var originAddr = results[0].formatted_address;
            var infoWindow = new google.maps.InfoWindow({
                content: originAddr,
                maxWidth: 150
            });
            var originMarkerListener = google.maps.event.addListener(originMarker, "click", function(event){
                infoWindow.open(map, originMarker);
            });
        });

        var marker1 = originMarker;
        var marker2 = destinationMarker;

        directionsRenderer.setMap(map);
        directionsRenderer.setPanel($("#map_directions").get(0));
        var request = {
            origin: marker1.getPosition(), 
            destination: marker2.getPosition(), 
            travelMode: google.maps.TravelMode.DRIVING
        };
        directionsService.route(request, function(result, status) {
            if (status == google.maps.DirectionsStatus.OK) {
                directionsRenderer.setDirections(result); 
                $("#map_directions_prompt").hide();
            }
        });
    }
    function adjustContentHeight(){
        var heightRightPg = $("div#right_container").outerHeight(true); //true for include margin
        var heightLeftPg = $("div#left_container").outerHeight(true);
        var maxHeight = Math.max(heightRightPg, heightLeftPg);
        var height = Math.max(maxHeight, .89 * $("div#container").height()); //test again vs. the container this time

        $("div#page_content").height(height);
    }
    $(window).load(function(){ //HAS TO BE IN $(window).load to make sure all page elements are fully loaded
        /*-----------------------------Height of the page------------------------------------*/
        adjustContentHeight();
    });
    $(document).ready(function(){
        /*-------------------------------Ratings plugin widget------------------------------*/
        //display avg ratings in proj info
        if ($('div#display_projectRating').length) { //if display rating element exists, won't if project has no ratings
            $("div#display_projectRating").raty({
                hints       : ['Bad', 'Poor', 'OK', 'Good', 'Excellent'],
                precision   : true,
                readOnly    : true,
                space       : false,
                score       : <?php echo $avgRating; ?> //change this to whatever the average rating for the project is
            });
        }

        //asks user to write a review
        if ($('button#rating_prompt').length) { //if display rating prompt exists, won't if user has already submitted a review for this project
            $("div#user_rating").raty({
                hints       : ['Bad', 'Poor', 'OK', 'Good', 'Excellent'],
                target      : "span#star_hints",
                targetKeep  : true
            });
            $("div#give_rating").hide();

            $("button#rating_prompt").button(); //jQuery UI styling for button prompting the user to write a review
            $("button#submit_review").button();
            $("button#submit_review").click(function(){
                var title = $("input#review_title").val().trim();
                var content = $("textarea#review_content").val().trim();
                var stars = $("div#user_rating").raty("score");

                //validate form
                var isValid = true;
                if(title.length == 0){ //empty
                    isValid = false;
                    $("input#review_title").css("border", ".0625em solid red");
                } else{
                    $("input#review_title").css("border", ""); //remove styling
                }

                if(content.length == 0){ //empty
                    isValid = false;
                    $("textarea#review_content").css("border", ".0625em solid red");
                } else{
                    $("textarea#review_content").css("border", "");
                }

                if(stars == null){
                    isValid = false;
                    $("span#star_hints").html("<span class='error'>Please give a Star Rating</span>");
                }

                if(isValid){
                    $("div#give_rating").html("<span class='success'>Your review has been submitted</span>");
                    var ratingStars = stars;
                    var ratingTitle = title;
                    var ratingContent = content;

                    $.ajax({
                        url: "phpfunctions/submitReview.php",
                        type: "POST",
                        data: {
                            rating_type     : 3, //type 1: rating for a user host of project, type 2: rating for user volunteer of a project, type 3: rating for a project
                            rating_stars    : ratingStars,
                            review_title    : ratingTitle,
                            review_content  : ratingContent,
                            recipient_id    : <?php echo $projectId; ?>,
                            author_id       : <?php echo $userId; //variable from header.php ?>
                        },
                        dataType: "json",
                        error: function(xhr, status, error) {
                            alert("Error: " + xhr.status + " - " + error);
                        },
                        success: function(data) {
                            var isSuccess = data.success;
                            if(isSuccess == true){
                                $("div#give_rating").html("<span class='success'>Your review has been submitted</span>");
                            } else{
                                $("div#give_rating").html("<span class='error'>Something went wrong</span>");
                            }
                        } //end success
                    }); //end ajax*/
                } //end if(isValid)
            }); //end button onclick listener

            $("button#rating_prompt").click(function(){
                $("div#give_rating").toggle();

                var reviewsHeight = 0;
                if($("p#no_reviews_message").length > 0){
                    reviewsHeight = $("p#no_reviews_message").outerHeight(true);
                } else{
                    $("div.a_review").each(function(){
                        reviewsHeight += $(this).outerHeight(true);
                    });
                }
                var height = $("h2#review_title").outerHeight(true) + reviewsHeight + $("button#rating_prompt").outerHeight(true);
                if($("div#give_rating").attr("style") == "display: block;"){
                    height += $("button#submitReview").outerHeight(true) + $("div#give_rating").outerHeight(true) + 21;
                } 
                $("div#project_review_container").height(height);

                adjustContentHeight(); //readjust height to reposition footer
            });
        }//end if(prompt user to write a review button exists)

        /*-----------------------------------displays top 3 reviews------------------------------*/
        $("div.review_stars").each(function(){
            var stars = $(this).attr("data-stars");
            $(this).raty({
                hints       : ['1 out of 5 stars', '2 out of 5 stars', '3 out of 5 stars', '4 out of 5 stars', '5 out of 5 stars'],
                readOnly    : true,
                space       : false,
                score       : stars
            });
        });
        $("div#project_review_container .a_review").not($("div#project_review_container .a_review:last")).css("margin-bottom", "1.3em"); //about 20px spacing between reviews
        /*------------------------------------end give rating-----------------------------------*/

        /*--------------------Map for directions----------------------------*/
        var directionsRenderer = new google.maps.DirectionsRenderer({
            suppressMarkers: true
        }); //passed into the getDirections() calls below 
        
        var geocoder = new google.maps.Geocoder();
        geocoder.geocode({address: "<?php echo $projectAddr; ?>"}, function(results){
            var myLatLng = results[0].geometry.location;

            var mapOptions = {
                zoom: 11, 
                center: myLatLng, 
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };
            var map = new google.maps.Map($("#map_canvas").get(0), mapOptions);

            var destinationMarker = new google.maps.Marker( {
                position: myLatLng,
                title: "<?php echo $projectName; ?>", //will show up on user hover over marker
                icon: "images/marker_directions_icon_finish.png",
                map: map        
            }); 
            var infoWindow = new google.maps.InfoWindow({
                content: "<?php echo $projectAddr; ?>",
                maxWidth: 150
            });
            var destinationMarkerListener = google.maps.event.addListener(destinationMarker, "click", function(event){
                infoWindow.open(map, destinationMarker);
            });

            /*********************--------location search box for directions (option 2)---------********************/
            var input = document.getElementById('location_search');
            map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
            input.style.display = "block"; //display only after it is in position
            /*---------------------------location search box----------------------*/
            var searchBox = new google.maps.places.SearchBox(input);
            google.maps.event.addListener(searchBox, 'places_changed', function() {
                var places = searchBox.getPlaces();
                var loc = places[0].geometry.location;
                var originLocation = new google.maps.LatLng(loc.lat(), loc.lng());
                getDirections(originLocation, destinationMarker, directionsRenderer, map);
            });

            /*--------------/find users location to get directions (option 1)-----------------*/
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (position) {
                    initialLocation = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
                    getDirections(initialLocation, destinationMarker, directionsRenderer, map);
                });
            }  //end geolocation of finding users location
        }); //end geocode
    });  // end ready
</script>

<!--***************************-Beginning Page-******************************-->
            <div id="page_content">
                <div id="left_container">
                    <div id="project_info" class="container left_page">
                        <?php
                            echo "<h1 id='display_projectName'>" . $projectName . "</h1>";
                            if($avgRating > 0){ //there actually are ratings
                                $pluralRatings = "ratings";
                                if($totalRatings == 1){
                                    $pluralRatings = "rating";
                                }
                                echo "<div id='display_projectRating'></div><span id='projectRating_message'>" . $avgRating . " out of 5 (<a href='#'>" . $totalRatings . " $pluralRatings</a>)</span>";
                            }
                            echo "<p id='display_projectHost'><span class='info_descript'>Hosted by: </span><a href='#'>" . $projectHost . "</a></p>"
                                . "<p id='display_projectDateTime'><span class='info_descript'>Time: </span>" . $projectTime . " on " . $date . "</p>"
                                . "<p id='display_projectAddr'><span class='info_descript'>Address: </span>" . $projectAddr . "</p>"
                                . "<p id='display_projectDescript'>" . $projectDescrip . "</p>";

                            //FB like and share buttons
                            if ( isset( $session ) ) { //user is logged in
                                echo "<p id='message_fbShareLike'><fb:like href='http://localhost/TreeBox/view_project.php?proj_id=" . $projectId . "' layout='standard' action='like' show_faces='true' share='true'></fb:like></p>";
                            }
                        ?>
                    </div>
                    <div id="pinned_posts_container" class='container left_page'>
                        <h2 id='review_title' class='container_title'>Pinned Posts</h2>
                    </div>
                </div>
                <div id="right_container">
                    <div id="map_container" class="container right_page">
                        <div id="map_canvas"></div>
                        <div id="map_directions">
                            <p id="map_directions_prompt">Enter a starting location to get directions to this project</p>
                        </div>
                        <input type="text" name="location_search" id="location_search" class="location_search_controls" placeholder="Enter a starting location" style="display: none;">
                    </div>
                    <div id="project_review_container" class="container right_page">
                        <h2 id='review_title' class='container_title'>Project Reviews</h2>
                        <?php
                        $ratingsQuery = "SELECT * FROM ratings
                                WHERE project_id = {$projectId}
                                ORDER BY date_submitted DESC
                                LIMIT 0, 3"; 
                        if ($ratingsResult = @mysql_query($ratingsQuery, $dbc)) { //successful query
                            if (mysql_num_rows($ratingsResult) > 0) {
                                while ($ratingsRow = mysql_fetch_array($ratingsResult)) {
                                    $ratingAuthor = $ratingsRow['author_id'];
                                    $authorQuery = "SELECT * FROM users
                                            WHERE user_id = {$ratingAuthor}"; 
                                    if ($authorResult = @mysql_query($authorQuery, $dbc)) { //successful query
                                        $authorRow = mysql_fetch_array($authorResult);
                                        $authorName = $authorRow['first_name'] . " " . $authorRow['last_name'];
                                    }

                                    $reviewTitle = $ratingsRow['review_title'];
                                    $reviewContent = $ratingsRow['review_content'];
                                    $reviewStars = $ratingsRow['stars'];
                                    //format datetime
                                    $dt = date_create($ratingsRow['date_submitted']);
                                    $dateSubmitted = date_format($dt, 'F j, Y'); 

                                    echo "<div class='a_review'>
                                            <div class='review_stars' data-stars={$reviewStars}></div>
                                            <span class='review_title'>{$reviewTitle}</span>
                                            <p class='review_info'>by <a href='#'>{$authorName}</a> on {$dateSubmitted}</p>
                                            <p class='review_content'>{$reviewContent}</p>
                                        </div>";  //data stored for javascript raty plugin initailization
                                }      
                            } else{
                                echo "<p id='no_reviews_message'>There are no project reviews yet</p>";
                            }      
                        } 

                        //prompt user to write a review
                        if(!$userHasWrittenReview){ //user has not yet written a review for this project
                            echo "<button type='button' id='rating_prompt'>Write a review for this project</button>
                                <div id='give_rating'>
                                    <form action='view_project.php' method='POST' id='rating_form'>
                                        <div id='user_rating'></div><span id='star_hints'></span><br/>
                                        <input type='text' name='review_title' id='review_title' class='rating_form_element' placeholder='Title your review' maxlength='140'/><br/>
                                        <textarea name='review_content' id='review_content' class='rating_form_element' rows=12 placeholder='Write your review here'></textarea>
                                        <br/><button type='button' name='submit_review' id='submit_review'>Submit</button>
                                    </form>
                                </div>"; //the div id="give_rating" is hidden until button is clicked
                        }
                        ?>
                    </div> <!--end review container div-->
                </div> <!--end right container div-->
            </div><!--end page_content div-->

<?php include("templates/footer.php"); ?>