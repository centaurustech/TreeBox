<?php 
include("phpfunctions/mainfunctions.php"); //connects to database
session_start(); //for facebook login (set up in "header.php")


$projectId = $_GET['proj_id'];  

$query = "SELECT * FROM projects
        WHERE project_id = {$projectId}"; 
if ($result = @mysql_query($query, $dbc)) { //successful query
    $row = mysql_fetch_array($result);

    $projectHost = $row['user_id'];
    $projectName = $row['project_name'];
    //format the description
    $projectDescrip = $row['project_description'];
    if(strlen($projectDescrip) > 170){
        $projectDescrip = substr($row['project_description'], 0, 170) . "...";
    }

    /*project datetime stuff*/
    $projectTime = $row['project_time'];
    //format datetime
    $dt = date_create($row['project_datetime']);
    $date = date_format($dt, 'l F jS, Y');
    $projectHasExpired = false;
    if($row['hasExpired'] == 1)
        $projectHasExpired = true;

    /*project location stuff*/
    $projectAddr = $row['loc_formatted_address'];
    $projectLat = $row['location_lat'];
    $projectLng = $row['location_lng'];
} else { //Query didn't run (later redirect to an error page) /*#######################change this to error page##########################*/
    //redirect (accessed page incorrectly)
    header("Location: http://localhost/TreeBox/index.php"); /*###########################this needs to be updated############*/
}

?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $projectName . " - TreeBoks"; ?></title>
    
    	<!-- style stuff -->
        <link type="text/css" rel='stylesheet' href='css/mystyles/mainViewProjectStyle.css' /> <!--this page's style stuff-->
        <link href="css/jquery-ui.min.css" type="text/css" rel="stylesheet" /><!--jQuery UI style-->
    	
    	<!-- JS and jQuery stuff -->
    	<script src="http://code.jquery.com/jquery-1.8.3.min.js"></script> <!--jQuery Library-->
        <script type="text/javascript" src="js/jquery-ui.min.js"></script> <!--jQuery UI-->
        <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?libraries=places"></script> <!--google maps places (includes autocomplete)-->
        <!--*********************- include JS file for this page *********************** -->
		<script src="js/myscripts/mainViewProjectJS.js"></script>
        <script>
            //embedded cause the use of PHP variables retrieved from database is used in JS code
            function getDirections(originLocation, destinationMarker, directionsRenderer, map){
                var directionsService = new google.maps.DirectionsService();

                var originMarker = new google.maps.Marker({
                    position: originLocation, 
                    title: "this is you",
                    map: map
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
            $(document).ready(function(){
                var directionsRenderer = new google.maps.DirectionsRenderer(); //passed into the getDirections() calls below 
                
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
                        map: map        
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
    </head>
    <body>
        <div id="container"> <!--closed in footer-->
        	<?php include("templates/header.php"); ?>
			<!--***************************-Beginning Page-******************************-->
			
            <div id="page_content">
                <div id="project_info"></div>
                <div id="map_container">
                    <div id="map_canvas"></div>
                    <div id="map_directions">
                        <p id="map_directions_prompt">Enter a starting location to get directions to this project</p>
                    </div>
                    <input type="text" name="location_search" id="location_search" class="location_search_controls" placeholder="Enter a starting location" style="display: none;">
                </div>
            </div><!--end page_content div-->

<?php include("templates/footer.php"); ?>