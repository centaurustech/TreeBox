<?php
$hostname = 'localhost';
$user = 'James';
$password = 'Madforthem0n3y';
$dbname = 'treebox';

$dbc = mysql_connect($hostname, $user, $password);
mysql_select_db($dbname, $dbc);
global $dbc;

function deletePoll($poll_id) {
    GLOBAL $dbc;
    $query = "DELETE FROM polls WHERE poll_id=$poll_id LIMIT 1";
    mysql_query($query, $dbc);
    if (mysql_affected_rows($dbc) == 1) {
        print '<p style="color: green;">Project successful successfully deleted!</p>';
    } else {
        print '<p style="color: red;">Unable to delete.</p>';
    }
}

function executeQuery($query, $success_message = 'none', $display_error_message = true) {
    global $dbc;
    @mysql_query($query, $dbc);

    if($success_message != 'none'){ //function called with a message
        if (mysql_affected_rows($dbc) == 1) { //something changed
            print "<p style='border: green; color: green;'><img src='images/check_icon.png' id='check_icon' style='width: 1em; height: 1em'/> {$success_message}</p>";
        } else {
            if($display_error_message){ 
                print '<p style="border: red; color: red;">Error, something occurred which prevented the action from executing. ' . mysql_error($dbc) . '</p>';
            }
        }
    }
}
?>