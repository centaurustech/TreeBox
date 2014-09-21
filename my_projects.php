<?php 
include("phpfunctions/mainfunctions.php"); //connects to database
session_start(); //for facebook login (set up in "header.php")
?>
<!DOCTYPE html>
<html>
    <head>
        <title>My Projects</title>
    
    	<!-- style stuff -->
        <link type="text/css" rel='stylesheet' href='css/mystyles/mainMyProjectsStyle.css' /> <!--this page's style stuff-->
        <link href="css/jquery-ui.min.css" type="text/css" rel="stylesheet" /><!--jQuery UI style-->
        <!-- fonts -->
        <link href='http://fonts.googleapis.com/css?family=Lato:900' rel='stylesheet' type='text/css'>
    	
    	<!-- JS and jQuery stuff -->
    	<script src="http://code.jquery.com/jquery-1.8.3.min.js"></script> <!--jQuery Library-->
        <script type="text/javascript" src="js/jquery-ui.min.js"></script> <!--jQuery UI-->
        <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?libraries=places"></script> <!--google maps places (for autocomplete)-->
        <!--*********************- include JS file for index page *********************** -->
		<script src="js/myscripts/mainMyProjectsJS.js"></script>
    </head>
    <body>
        <div id="container"> <!--closed in footer-->
        	<?php include("templates/header.php"); ?>
			<!--***************************-Beginning Page-******************************-->
			
            <div id="page_content">
                <?php //get user's active projects
                //$userId variable used below is set in "header.php"

                //retireve user active projects
                $query = "SELECT * FROM projects
                        WHERE user_id = {$userId} AND hasExpired = 0
                        ORDER BY project_datetime ASC"; 
                if ($result = @mysql_query($query, $dbc)) { //successful query
                    echo '<div id="my_active_projs">
                            <span class="projs_label">My active projects</span>
                            <!--Table of projects-->
                            <table id="projs_table">
                                <tbody id="projs_tbody">';

                    $colCount = 0; // limit the number of columns in a table row
                    while ($row = mysql_fetch_array($result)) {
                        if($colCount == 0){
                            echo '<tr class="projs_tr">';
                        }

                        echo "<td class='projs_td'>";

                        $projectId = $row['project_id'];
                        $projectName = $row['project_name'];
                        $projectTime = $row['project_time'];
                        $projectAddr = $row['loc_formatted_address'];
            
                        //format the description
                        $projectDescrip = $row['project_description'];
                        if(strlen($projectDescrip) > 170){
                            $projectDescrip = substr($row['project_description'], 0, 170) . "...";
                        }

                        //format datetime
                        $dt = date_create($row['project_datetime']);
                        $date = date_format($dt, 'l F jS, Y');

                        echo "<h1 class='projs_projectName'><a href='view_project.php?proj_id=" . $projectId . "'>" 
                            . $projectName . "</a></h1>"
                            . "<p class='projs_projectDateTime'>" . $projectTime . " on <i>" . $date . "</i></p>"
                            . "<p class='projs_projectAddr'>@ " . $projectAddr . "</p>"
                            . "<p class='projs_projectDescrip'>" . $projectDescrip . "</p>"
                            . "<p class='edit_project_p'><a href='edit_project.php?proj_id=" . $projectId . "'><span class='edit_link'>Edit</span><img src='images/edit_project_icon.png' class='edit_icon'/><a/></p>";
                        
                        echo "</td>";

                        $colCount++;
                        if($colCount > 2){ //limit of 3 projects listed in 1 table row
                            echo "</tr>";
                            $colCount = 0; //reset counter
                        }
                    } //End of while loop* /

                    echo '</tbody></table></div>'; //end table and div
                } else { //Query didn't run (later redirect to an error page) /*#######################change this to error page##########################*/
                    print '<p style="border: red; color: red;">Error, something occurred which prevented the query from executing. ' 
                        . mysql_error($dbc) . '</p>';
                }


                /*-----------------------------Get users past projects (expired)-------------------------------*/
                //retireve user project memorial
                $query = "SELECT * FROM projects
                        WHERE user_id = {$userId} AND hasExpired = 1
                        ORDER BY project_datetime DESC"; 
                if ($result = @mysql_query($query, $dbc)) { //successful query
                    echo '<div id="proj_memorial">
                            <span class="projs_label">Project memorial</span>
                            <!--Table of projects-->
                            <table id="projs_table">
                                <tbody id="projs_tbody">'; 

                    $colCount = 0; // limit the number of columns in a table row
                    while ($row = mysql_fetch_array($result)) {
                        if($colCount == 0){
                            echo '<tr class="projs_tr">';
                        }

                        echo "<td class='projs_td proj_memorial'>"; //note that the table data here has TWO classes

                        $projectId = $row['project_id'];
                        $projectName = $row['project_name'];
                        $projectTime = $row['project_time'];
                        $projectAddr = $row['loc_formatted_address'];
            
                        //format the description
                        $projectDescrip = $row['project_description'];
                        if(strlen($projectDescrip) > 170){
                            $projectDescrip = substr($row['project_description'], 0, 170) . "...";
                        }

                        //format datetime
                        $dt = date_create($row['project_datetime']);
                        $date = date_format($dt, 'l F jS, Y');

                        echo "<h1 class='projs_projectName'><a href='view_project.php?proj_id=" . $projectId . "'>" 
                            . $projectName . "</a></h1>"
                            . "<p class='projs_projectDateTime'>" . $projectTime . " on <i>" . $date . "</i></p>"
                            . "<p class='projs_projectAddr'>@ " . $projectAddr . "</p>"
                            . "<p class='projs_projectDescrip'>" . $projectDescrip . "</p>"
                            . "<p class='edit_project_p'><a href='edit_project.php?proj_id=" . $projectId . "'><span class='edit_link'>Edit</span><img src='images/edit_project_icon.png' class='edit_icon'/><a/></p>";
                        
                        echo "</td>";

                        $colCount++;
                        if($colCount > 2){ //limit of 3 projects listed in 1 table row
                            echo "</tr>";
                            $colCount = 0; //reset counter
                        }
                    } //End of while loop* /

                    echo '</tbody></table></div>'; //end table and div
                } else { //Query didn't run (later redirect to an error page) /*#######################change this to error page##########################*/
                    print '<p style="border: red; color: red;">Error, something occurred which prevented the query from executing. ' 
                        . mysql_error($dbc) . '</p>';
                }
                ?>
            </div><!--end page_content div-->

<?php include("templates/footer.php"); ?>