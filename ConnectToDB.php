<?php

//Creates and returns a connection to a database.
function doDB() {
	global $mysqli;

	//connect to server and select database; you may need it
	//local db - $mysqli = mysqli_connect("localhost", "root", "", "MovieVehicleDB");
	$mysqli = mysqli_connect("localhost", "lisabalbach_skinneb", "CIT1802508", "lisabalbach_Skinner");

	//if connection fails, stop script execution
	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}
}

?>