<?php
	if($_SERVER['PHP_SELF'] != '/TreeBox/test.php'){
		header("Location: http://localhost/TreeBox/test.php"); /* Redirect browser */
		exit();
	}
	echo $_SERVER['PHP_SELF']; //"/Treebox/test.php"
?>