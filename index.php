<!DOCTYPE html>
<html>
    <head>
        <link type="text/css" rel='stylesheet' href='css/mainstyle.css' />
        <link href='http://fonts.googleapis.com/css?family=Lato:900' rel='stylesheet' type='text/css'>
        <script src="https://maps.googleapis.com/maps/api/js"></script>
        <script>
            function initialize() {
                var map_canvas = document.getElementById('map_canvas');
                var map_options = {
                    center: new google.maps.LatLng(44.5403, -78.5463),
                    zoom: 8,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                }
                var map = new google.maps.Map(map_canvas, map_options);
            }
            google.maps.event.addDomListener(window, 'load', initialize); //wait for page to load before initializing map
        </script>
        <title>TreeBox</title>
    </head>
    <body>
        <div id="container">
            <div id="header"></div>
            <div id="left_pane">
                <div id="add_a_project">
                    <section id="add_project_section">
                        <h1 id="add_project_heading">ADD YOUR PROJECT!</h1>
                    </section>
                </div>
                <div id="ads"></div>
            </div>
            <div id="map_canvas"></div>
        </div>
        <?php
        // put your code here
        ?>
    </body>
</html>
