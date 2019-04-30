<?php
session_start();
//connect to database
include 'ConnectToDB.php';

//Check the required fields before updating the database table.
if (($_POST['f_name'] == "") || ($_POST['l_name'] == "") || $_POST['vehicleName'] == "" || $_POST['movieTitle'] == "" || $_POST['year'] == "" || $_POST['make'] == "" || $_POST['model'] == ""
|| $_POST['description'] == "" || $_POST['ownerAddress'] == "" || $_POST['ownerCity'] == "" || $_POST['ownerState'] == "" || $_POST['ownerZipcode'] == "" || $_POST['phoneNumber'] == "")
{
	//If the required info. was not filled out return to the update page.
	header("Location: changeEntry.php");
	exit;
}

//Connect to database
doDB();

//Create clean versions of input strings
$owner_id=$_SESSION["id"];
$safe_f_name = mysqli_real_escape_string($mysqli, $_POST['f_name']);
$safe_l_name = mysqli_real_escape_string($mysqli, $_POST['l_name']);

$safe_vehicleName = mysqli_real_escape_string($mysqli, $_POST['vehicleName']);
$safe_movieTitle = mysqli_real_escape_string($mysqli, $_POST['movieTitle']);
$safe_year = mysqli_real_escape_string($mysqli, $_POST['year']);
$safe_make = mysqli_real_escape_string($mysqli, $_POST['make']);
$safe_model = mysqli_real_escape_string($mysqli, $_POST['model']);
$safe_description = mysqli_real_escape_string($mysqli, $_POST['description']);

$safe_eventTitle = mysqli_real_escape_string($mysqli, $_POST['eventTitle']);
$safe_eventDate = mysqli_real_escape_string($mysqli, $_POST['eventDate']);
$safe_eventVenue = mysqli_real_escape_string($mysqli, $_POST['eventVenue']);
$safe_venueAddress = mysqli_real_escape_string($mysqli, $_POST['venueAddress']);
$safe_venueCity = mysqli_real_escape_string($mysqli, $_POST['venueCity']);
$safe_venueState = mysqli_real_escape_string($mysqli, $_POST['venueState']);
$safe_venueZipcode = mysqli_real_escape_string($mysqli, $_POST['venueZipcode']);
$safe_venuePhone = mysqli_real_escape_string($mysqli, $_POST['venuePhone']);

$safe_ownerPhone = mysqli_real_escape_string($mysqli, $_POST['phoneNumber']);

$safe_ownerAddress = mysqli_real_escape_string($mysqli, $_POST['ownerAddress']);
$safe_ownerCity = mysqli_real_escape_string($mysqli, $_POST['ownerCity']);
$safe_ownerState = mysqli_real_escape_string($mysqli, $_POST['ownerState']);
$safe_ownerZipcode = mysqli_real_escape_string($mysqli, $_POST['ownerZipcode']);

//Update the Owner table.
$ownerSQL = "UPDATE Owner SET FirstName ='".$safe_f_name."', LastName ='". $safe_l_name."'".
					"WHERE OwnerID = ".$owner_id;
$ownerResult = mysqli_query($mysqli, $ownerSQL) or die(mysqli_error($mysqli));

//Check to see if any vehicle information is being modified or added.
if ($_SESSION["vehicle"] == "true")
{
	//If vehicle information was already present update the vehicle table.
	$vehicleSQL = "UPDATE Vehicle SET VehicleName = '". $safe_vehicleName ."', MovieTitle = '". $safe_movieTitle ."', Description = '". $safe_description ."', Year = '". $safe_year ."', Make = '". $safe_make ."', Model = '". $safe_model ."'".
							"WHERE OwnerID = ".$owner_id;
	$vehicleResult = mysqli_query($mysqli, $vehicleSQL) or die(mysqli_error($mysqli));
}
else if (($_POST['vehicleName']) != "" ) 
{
	//If vehicle information was just entered add a new record to the vehicle table.
	$vehicleSQL = "INSERT INTO Vehicle (OwnerID, VehicleName, Description, MovieTitle, Year, Make, Model) " . 
						"VALUES ('" . $owner_id . "', '" . $safe_vehicleName . "', '" . $safe_description . "', '" . $safe_movieTitle . "', '" 
						. $safe_year . "', '" . $safe_make . "', '" . $safe_model . "')";
	$vehicleResult = mysqli_query($mysqli, $vehicleSQL) or die(mysqli_error($mysqli));
}

//Check to see if any event information is being modified or added.
if ($_SESSION["event"] == "true")
{
	//If event information was already present update the event table.
	$eventSQL = "UPDATE Event SET EventTitle = '". $safe_eventTitle ."', EventDate = '". $safe_eventDate ."', EventVenue = '". $safe_eventVenue 
			."', StreetAddress = '". $safe_venueAddress ."', City = '". $safe_venueCity ."', State = '". $safe_venueState ."', Zipcode = '". $safe_venueZipcode ."', " .
			"PhoneNumber = '". $safe_venuePhone ."'".
			" WHERE OwnerID = ".$owner_id;
	$eventResult = mysqli_query($mysqli, $eventSQL) or die(mysqli_error($mysqli));
}
else if ($_POST['eventTitle'] != "" || $_POST['eventDate'] != "" || $_POST['eventVenue'] != "" || $_POST['venueAddress'] != "" || $_POST['venueCity'] != "" || $_POST['venueState'] != "" || $_POST['venueZipcode'] != "" || $_POST['venuePhone'] != "") 
{
	//If event information was just entered add a new record to the event table.
	$eventSQL = "INSERT INTO Event (OwnerID, EventTitle, EventDate, EventVenue, StreetAddress, City, State, Zipcode, PhoneNumber) " . 
						"VALUES ('" . $owner_id . "', '" . $safe_eventTitle . "', '" . $safe_eventDate . "', '" . $safe_eventVenue . "', '" 
						. $safe_venueAddress . "', '" . $safe_venueCity . "', '" . $safe_venueState . "', '" . $safe_venueZipcode . "', '" . $safe_venuePhone . "');";
	$eventResult = mysqli_query($mysqli, $eventSQL) or die(mysqli_error($mysqli));
}

//Check to see if any owner telephone information is being modified or added.
if ($_SESSION["telephone"] == "true")
{
	//If the telephone information was already present update the event table.
	$telephoneSQL = "UPDATE OwnerTelephone  SET PhoneNumber = '". $safe_ownerPhone ."' WHERE OwnerID = ".$owner_id;
	$telephoneResult = mysqli_query($mysqli, $telephoneSQL) or die(mysqli_error($mysqli));
}
else if (($_POST['phoneNumber']) != "") 
{
	//If the telephone information was just entered add a new record to the event table.
	$telephoneSQL = "INSERT INTO OwnerTelephone ( OwnerID, PhoneNumber ) " . 
						"VALUES ('" . $owner_id . "', '" . $safe_ownerPhone . "');";
	$telephoneResult = mysqli_query($mysqli, $telephoneSQL) or die(mysqli_error($mysqli));
}

//Check to see if any owner address information is being modified or added.
if ($_SESSION["address"] == "true")
{
	//If the address information was already present update the event table.
	$addressSQL = "UPDATE OwnerAddress  SET StreetAddress = '". $safe_ownerAddress ."', City = '". $safe_ownerCity ."', State = '". $safe_ownerState ."', Zipcode = '". $safe_ownerZipcode ."'  WHERE OwnerID = ".$owner_id;
	$addressResult = mysqli_query($mysqli, $addressSQL) or die(mysqli_error($mysqli));
}
else if (($_POST['ownerAddress']) != "" || ($_POST['ownerCity']) != "" || ($_POST['ownerState']) != "" || ($_POST['ownerZipcode']) != "") 
{
	//If the address information was just entered add a new record to the event table.
	$addressSQL = "INSERT INTO OwnerAddress ( OwnerID, StreetAddress, City, State, Zipcode ) " . 
						"VALUES ('" . $owner_id . "', '" . $safe_ownerAddress . "', '" . $safe_ownerCity . "', '" . $safe_ownerState . "', '" . $safe_ownerZipcode . "');";
	$addressResult = mysqli_query($mysqli, $addressSQL) or die(mysqli_error($mysqli));
}

//Close the database connection.
mysqli_close($mysqli);

//Display a message to the user that the record has been updated.
$display_block = "<br><br>";
$display_block .= "<div class='center'>";
$display_block .= "<br><br>";
$display_block .= "<h2>The Entry has been Updated.</h2>";
$display_block .= "<p><a href=\"ChangeEntry.php\">Change Another</a><br><br>";
$display_block .= "<a href='addressBookMenu.html'>Main Menu</a></p>";
$display_block .= "<br><br>";
$display_block .= "</div>";

?>
<!DOCTYPE html>
<html>
	<head>
		<title>Address Update</title>
		<link href="css/MovieVehicles.css" type="text/css" rel="stylesheet" />
	</head>
	<body>
		<?php echo $display_block; ?>
	</body>
</html>