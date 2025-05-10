<?php
	include 'connect.php';

	if (!$connection) {
		die('Could not connect: ' . mysqli_connect_error());
	}

	$query = 'SELECT * from tblstudent, tbluser where tbluser.id = tblstudent.uid';
	$resultset = mysqli_query($connection, $query);

	if (!$resultset) {
		die('Query failed: ' . mysqli_error($connection));
	}

	$GLOBALS['resultset'] = $resultset;
?>
