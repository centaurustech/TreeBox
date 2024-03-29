var allowedLocations = { //array of acceptable map locations used in setBoundsOnMap() below
	unitedStates:new google.maps.LatLngBounds(
	    new google.maps.LatLng(15, -179), //sw corner
     	new google.maps.LatLng(75, -50) //ne corner
	),
	singaporeMalaysia:new google.maps.LatLngBounds(
	    new google.maps.LatLng(-2, 95), //sw corner
     	new google.maps.LatLng(5, 110) //ne corner
	) 
}; 

function getMarkersForMap(map, markers, oms){ //oms being the OverlappingMarkerSpiderfier
	/***-------------------------------populating the map with markers---------------------------***/
    //send ajax requesting markers
    $.ajax({
        url: "phpfunctions/getmarkers.php",
        type: "GET",
        data: "getmarkers=yes",
        dataType: "json",
		error: function(xhr, status, error) {
			alert("Error: " + xhr.status + " - " + error);
		},
		success: function(data) {
			$.each(data, function(index, value) {
				var markerLat = value.lat;
				var markerLng = value.lng;
				var projectName = "" + value.project_name;
				var projectId = value.project_id;

				var marker = new google.maps.Marker({
					position: new google.maps.LatLng(markerLat, markerLng),
					title: projectName, //will show up on user hover over marker
					icon: "images/marker_default_icon_forest.png",
					map: map		
				});
				marker.id = projectId; //set the marker id
				markers.push(marker); //add the marker to the array of markers
				oms.addMarker(marker);

				//var markerListener = google.maps.event.addListener(marker, "click", function(event){
				oms.addListener('click', function(marker, event) {
					/*set all markers back to default icon*/
					var i;
					for(i = 0; i < markers.length; ++i){
						markers[i].setIcon("images/marker_default_icon_forest.png"); //default icon
						markers[i].setZIndex(1); //appear on bottom of stack
					}
					//change the icon of the selected marker so that user knows which project was selected
					marker.setIcon("images/marker_selected_icon_bigTree.png");
					marker.setZIndex(10); //appear on top of stack

					/*****move map position****/
					map.setCenter(marker.getPosition());
					if(map.getZoom() < 8){
						map.setZoom(8);
					}

					//send ajax requesting data based on projectId (of marker)
					$.ajax({
				        url: "phpfunctions/getProjectById.php",
				        type: "GET",
				        data: "getProjectId=" + marker.id,
				        dataType: "json",
						error: function(xhr, status, error) {
							alert("Error: " + xhr.status + " - " + error);
						},
						success: function(data) {
							/* data retrieved:
							"project_id" : project_id,
							"user_id" : user_id,
				    		"project_name" : project_name,
				    		"project_description" : project_description,
				    		"project_date" : project_date (formatted),
				    		"project_time" : project_time,
				    		"project_address" : project_address (formatted), 
							"lat" : location_lat,
							"lng" : location_lng
							*/
							var projectName = data.project_name;
							var projectDescrip = data.project_description;
							var projectDate = data.project_date;
							var projectTime = data.project_time;
							var projectAddr = data.project_address;
							var avgRating = data.avgRating;
							var totalRatings= data.totalRatings;

							//Create custom message
							var overlay = new google.maps.OverlayView();
							overlay.draw = function() {
								//actual message
								var html = "<h1 id='message_projectName'><a target='_blank' href='view_project.php?proj_id=" + marker.id + "'>" + projectName + "</a></h1>";
								/*if(avgRating > 0){
									html += "<div id='message_projectRating'></div><span id='projectRating'>" + avgRating + " out of 5 (<a href='#'>" + totalRatings + "</a>)</span>";
								}*/
								html += "<p id='message_projectDate'><b>" + projectTime + "</b> on <i>" + projectDate + "</i></p>"
									+ "<p id='message_projectAddress'>@ <b>" + projectAddr + "</b></p>"
									+ "<p id='message_projectDescription'>" + projectDescrip + "</p>"
									+ "<p id='message_viewProjectPageLink'><a target='_blank' href='view_project.php?proj_id=" + marker.id + "'>Get directions/Go to project page</a><br/></p>";
								$("#map_message").html(html); 
								FB.getLoginStatus(function(response) { //just for debugging in console
								  	if (response.status === 'connected') {
								    	$("#map_message").append( /*###################the below URL will need to be changed########################*/
								    		"<p id='message_fbShareLike'><fb:like href='http://localhost/TreeBox/view_project.php?proj_id=" + marker.id + "' layout='button_count' action='like' show_faces='true' share='true'></fb:like></p>"
								    	);
								  	}
								});
								$("#map_message").show();
								FB.XFBML.parse(); 
								/*if ($('div#message_projectRating').length) { //if display rating element exists, won't if project has no ratings
						            $("div#message_projectRating").raty({
						                hints       : ['Bad', 'Poor', 'OK', 'Good', 'Excellent'],
						                precision   : true,
						                readOnly    : true,
						                space       : false,
						                score       : avgRating //change this to whatever the average rating for the project is
						            });
						        }*/

								/*get the position for the map_message
									(needs to be after there is something to show*/
								//get the coordinates of the map (used to set X and Y of the map_message)
								var mapPosition = $("#map_canvas").position(); 
								//to calculate the X coordinate
								var mapWidth = $("#map_canvas").width();
								var messageWidth = $("#map_message").outerWidth(true); //full width
								
								var mapContainerX = mapPosition.left + ((.995 * mapWidth) - messageWidth);
								var mapContainerY = 1.6 * mapPosition.top;

								$("#map_message").css({
									top: mapContainerY,
									left: mapContainerX 
								});
							};
							overlay.setMap(map);
						} //end success for markerListener
				    }); //end ajax for marker listener
				});  // end markerListener
			}); //end $.each()
		} //end success for getting all the markers
    }); //end ajax for getting all the markers
}

function findCurrentAllowedLoc(map){
	var allowedLoc = allowedLocations.unitedStates; //default to unitedStates
	$.each(allowedLocations, function(index, value){ //only allow the map to pan to allowedLocations (array created at the top: for now USA and Singapore/Malaysia only)
		if(value.contains(map.getCenter())){
			allowedLoc = value;
			return value;
		}
	});
	return allowedLoc;
}

function setBoundsOnMap(map){ /*-----------restrict user from scrolling outside allowedLocations bounds------------*/
	var lastValidCenter = map.getCenter();

	google.maps.event.addListener(map, 'center_changed', function() {
		var bounds = findCurrentAllowedLoc(map);

	    if (bounds != null && bounds.contains(map.getCenter())) {
	        // still within valid bounds, so save the last valid position
	        lastValidCenter = map.getCenter();
	        return;
	    }

	    // not valid anymore => return to last valid position
	    map.panTo(lastValidCenter);
	});
}

function toggleLocSelector(map){ //changes the map according to the location selector list on top right corner of map
	var currentAllowedLoc = findCurrentAllowedLoc(map);
    if(currentAllowedLoc == allowedLocations.unitedStates){
    	$("li#united_states").css("font-weight", "bold");
    	$("li#singapore_malaysia").css("font-weight", "normal");
    } else if(currentAllowedLoc == allowedLocations.singaporeMalaysia){
    	$("li#united_states").css("font-weight", "normal");
    	$("li#singapore_malaysia").css("font-weight", "bold");
    }
}

function getNearbyProjs(latitude, longitude, markers){
	$.ajax({
        url: "phpfunctions/getListOfNearbyProjects.php",
        type: "GET",
        data: "userLat=" + latitude + "&userLng=" + longitude,
        dataType: "json",
		error: function(xhr, status, error) {
			alert("Error: " + xhr.status + " - " + error);
		},
		success: function(data) {
			if (data == null) {
                $("#display_nearby_projs").html("<h3>There are no projects in this area. <a href='add_project.php'>Add one now!</a></h3>");
            } else {
				var html = "<ul id='nearby_projs_list'>";
				$.each(data, function(index, value) {
					/* data retrieved:
						"project_id" : project_id,
						"user_id" : user_id,
			    		"project_name" : project_name,
			    		"project_description" : project_description
			    		"project_date" => date,
			    		"project_time" =>  project_time,
			    		"proximity_to_user" : proximity of project (calculated by php script)
					*/
					var projectName = value.project_name;
					var projectDescrip = value.project_description;
					var projectId = value.project_id;
					var projectDate = value.project_date;
					var projectTime = value.project_time;
					var projectProx = value.proximity_to_user;
					html +=	"<li><h2 class='nearby_projs_projectName'><a href='" + projectId + "'></a>" 
								//link click event set below
								+ projectName + "</h2>" 
						+ "<p class='nearby_projs_projectDescrip'>" + projectDescrip + "</p>"
						+ "<p class='nearby_projs_datetime'>" + projectTime + " on <i>" + projectDate + "</i></p>"
						+ "<p class='nearby_projs_prox'><b>" + projectProx + " miles away</b></p></li>"
				}); //end $.each
				html += "</ul>";
				$("#display_nearby_projs").html(html); 

				//set the links to trigger the click event of the marker with the id = the link's href value	
			    $("#projects_near_you a").click(function(evt){
			    	evt.preventDefault(); //cancel default of link taking you to a new page
			    });
			    $("#projects_near_you li").click(function() {
					var selectedMarkerId = $(this).find("a:first-child").attr("href");
					
					//loop through array of markers until the one with the id
					var i;
					for(i = 0; i < markers.length; ++i){
						if(markers[i].id == selectedMarkerId){
							//NOTE: triggered twice, just in case the marker is spiderfied
							//in which case the first click would unspiderfy, and the second click would select that marker
							google.maps.event.trigger(markers[i], 'click');
							google.maps.event.trigger(markers[i], 'click');
						}
					}
				});
			} //end if data null
		} //end success
    }); //end ajax*/
}

$(document).ready(function() {
	/******************** MAP stuff********************/
    var defaultLocation = "USA"; //initial location

	var geocoder = new google.maps.Geocoder();
	geocoder.geocode({address : defaultLocation}, function(results) {
		var defaultLatLng = results[0].geometry.location;
		var markers = []; //array of markers (will be filled by server ajax request)

		//create map
		var mapOptions = {
			zoom: 5,
			center: defaultLatLng,
            minZoom: 5,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		};
		var map = new google.maps.Map($("#map_canvas").get(0), mapOptions);

		/*******------------------ vertically bound the map, restrict user from scrolling outside---------------------**/
	   	setBoundsOnMap(map);

		/************Initialize spiderfier (for the case where there are multiple markers in one loc)****************/
		var oms = new OverlappingMarkerSpiderfier(map, {
			markersWontMove: true, 
			markersWontHide: true,
			keepSpiderfied: true
		});

		/*********************--------location search box (see bottom of code also)---------********************/
		var input = document.getElementById('location_search');
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
        input.style.display = "block"; //display only after it is in position

        var viewableLocs = document.getElementById('viewable_locs');
        map.controls[google.maps.ControlPosition.TOP_RIGHT].push(viewableLocs);
        viewableLocs.style.display = "block"; //display only after it is in position
        $("ul#viewable_locs li:last-child").css("border-left", "1px solid #BBC9BB");   
        toggleLocSelector(map);     

		/*--------------/find users location-----------------*/
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (position) {
                initialLocation = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
                map.setCenter(initialLocation);
                map.setZoom(10);

                /*********send ajax request to get nearest projects around user********/
	            /****projects_near_you div****/
				getNearbyProjs(position.coords.latitude, position.coords.longitude, markers);

				toggleLocSelector(map);
            });
        }  //end geolocation of finding users location

		/*-----------------------------allowed location selector----------------------------*/
		$("li.viewable_locs_li").click(function(){
			var loc = $(this).attr("id");
			if(loc == "united_states"){
				map.setCenter(new google.maps.LatLng(38.8833, -100));
			} else if(loc == "singapore_malaysia"){
				map.setCenter(new google.maps.LatLng(1.3, 103.8));
			}

			map.setZoom(5);
			toggleLocSelector(map);
		});

        /***-------------------------------populating the map with markers---------------------------***/
        getMarkersForMap(map, markers, oms);

        /*---------------------------location search box----------------------*/
        var searchBox = new google.maps.places.SearchBox(input);
        google.maps.event.addListener(searchBox, 'places_changed', function() {
		    var places = searchBox.getPlaces();
		    var loc = places[0].geometry.location;
		    map.setCenter(loc);
		    map.setZoom(10)
		    getNearbyProjs(loc.lat(), loc.lng(), markers);
		});
	}); //end geocoder.geocode();
});//end document.ready()