<?php
require 'wepay/wepay_required.php';
include("phpfunctions/mainfunctions.php"); 
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Add your project to TreeBox!</title>
    
    	<!-- style stuff -->
        <link type="text/css" rel='stylesheet' href='css/mystyles/mainWepaySetupStyle.css' /><!--page's style stuff-->
        <link href="css/jquery-ui.min.css" type="text/css" rel="stylesheet"> <!--jQuery UI style-->

        
    	<!-- JS and jQuery stuff -->
    	<script src="http://code.jquery.com/jquery-1.8.3.min.js"></script> <!--jQuery Library-->
        <script src="js/jquery.validate.min.js"></script> <!--Form validation-->
        <script type="text/javascript" src="js/jquery-ui.min.js"></script> <!--jQuery UI-->
        <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?libraries=places"></script> <!--google maps places (for autocomplete and location verification)-->
    	<script src="js/myscripts/mainAddProjectJS.js"></script> <!--******************************** include JS for this page *************************************-->
    </head>
    <body>
        <div id="container">
        	<?php include("templates/header.php");?>
<!--***********************start content**********************-->
<div id='page_content'>

	<div id="wepay_container">
		<p>
			<span style='border: green; color: green;'>
				<img src='images/check_icon.png' id='check_icon' style='width: 1em; height: 1em'/> Your project has been added to the map
			</span>
		</p>
		<h1>Attach crowdfunding to your project with WePay</h1>
		
		<?php 
		if (empty($_SESSION['wepay_access_token'])): ?>
			<a href="login.php" class="buttonTwo">Log in with WePay</a>
		<?php else: ?>
			<a href="user.php">User info</a><br />
			<a href="openaccount.php">Open new account</a><br />
			<a href="accountlist.php">Account list</a><br />
			<a href="logout.php">Log out</a>
		<?php endif; ?>
	</div><!--end wepay container-->

</div><!--end page content-->

<?php include("templates/footer.php"); ?>