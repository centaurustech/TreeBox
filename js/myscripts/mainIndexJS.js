$(document).ready(function() {
	/******************** MAP stuff********************/
    var userLocation = "USA";

	var geocoder = new google.maps.Geocoder();
	geocoder.geocode({address : userLocation}, function(results) {
		var myLatLng = results[0].geometry.location;
		var markers = []; //array of markers (will be filled by server ajax request)
		
		//create map
		var mapOptions = {
			zoom: 5,
			center: myLatLng,
            minZoom: 3,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		};
		var map = new google.maps.Map($("#map_canvas").get(0), mapOptions);

		/*--------------/find users location-----------------*/
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (position) {
                initialLocation = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
                map.setCenter(initialLocation);
                map.setZoom(10);
            });

            /*********send ajax request to get nearest projects around user********/
        }  //end geolocation of finding users location

		/*******------------------ vertically bound the map, restrict user from scrolling outside---------------------**/
	   	var strictBounds = new google.maps.LatLngBounds(
	     	new google.maps.LatLng(-73, -170), //sw corner the lng value doesnt really matter
	     	new google.maps.LatLng(73, 170) //ne corner the lng value doesnt really matter
	   	);
		// Listen for the dragend event
		google.maps.event.addListener(map, 'dragend', function() {
	    	if (strictBounds.contains(map.getCenter())) return;
	    	// We're out of bounds - Move the map back within the bounds (only vertical bounds)
	     	var c = map.getCenter(),
	        	x = c.lng(),
	         	y = c.lat(),
	         	//maxX = strictBounds.getNorthEast().lng(),
	         	maxY = strictBounds.getNorthEast().lat(),
	         	//minX = strictBounds.getSouthWest().lng(),
	         	minY = strictBounds.getSouthWest().lat();

	     	//if (x < minX) x = minX;
	     	//if (x > maxX) x = maxX;
	     	if (y < minY) y = minY;
	     	if (y > maxY) y = maxY;

	     	map.setCenter(new google.maps.LatLng(y, x));
	   	}); //end of bounding

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
						title: projectName,
						map: map		
					});

					var markerListener = google.maps.event.addListener(marker, "click", function(event){
						//send ajax requesting data based on projectId (of marker)
						$.ajax({
					        url: "phpfunctions/getProjectById.php",
					        type: "GET",
					        data: "getProjectId=" + projectId,
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

								//Create custom message
								var overlay = new google.maps.OverlayView();
								overlay.draw = function() {
									//actual message
									$("#map_message").html(
										"<h1 id='message_projectName'>" + projectName + "</h1>" 
										+ "<p id='message_projectDate'><b>" + projectTime + "</b> on <i>" + projectDate + "</i></p>"
										+ "<p id='message_projectAddress'>@ <b>" + projectAddr + "</b></p>"
										+ "<p id='message_projectDescription'>" + projectDescrip + "</p>"
									); 
									$("#map_message").show();

									/*get the position for the map_message
										(needs to be after there is something to show*/
									//get the coordinates of the map (used to set X and Y of the map_message)
									var mapPosition = $("#map_canvas").position(); 
									//to calculate the X coordinate
									var mapWidth = $("#map_canvas").width();
									var messageWidth = $("#map_message").outerWidth(true); //full width

									var mapContainerX = mapPosition.left + (mapWidth - messageWidth);
									var mapContainerY = mapPosition.top;

									$("#map_message").css({
										top: mapContainerY,
										left: mapContainerX 
									});
								};
								overlay.setMap(map);
							} //end success
					    }); //end ajax* /
					});  // end markerListener*/
				}); //end $.each()
			} //end success
	    }); //end ajax*/		
	}); //end geocoder.geocode();
});//end document.ready()