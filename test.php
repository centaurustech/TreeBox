<?php
	$projectDate = new DateTime("08/09/2014 " . ' 08:15PM');
	$projectDate = $projectDate->format('Y-m-d H:i');

	$projectDate = strtotime($projectDate);

	$now = new DateTime();
	$expiryDate = $now->add(new DateInterval('P1D')); //The project will officially expire 1 day from its datetime
	$expiryDate = $expiryDate->format('Y-m-d H:i');
	$expiryDate = strtotime($expiryDate);

	echo $expiryDate . ' ' . $projectDate;
?>