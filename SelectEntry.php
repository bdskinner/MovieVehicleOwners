<?php

//Include Files.
include 'ConnectToDB.php';

//Connect to the database.
doDB();

//Check to see if the page is in postback.
if (!$_POST)  
{
	//If the page is not in the postback condition...

	//Display the page heading.
	$display_block = "<h1>Select an Entry</h1>";

	//Get the owner's name to fill the drop-down list.
	$get_list_sql = "SELECT OwnerID,
	                 CONCAT_WS(', ', LastName, FirstName) AS display_name
					 FROM Owner ORDER BY LastName, FirstName";
	$get_list_res = mysqli_query($mysqli, $get_list_sql) or die(mysqli_error($mysqli));

	//Check the resultset that was returned.
	if (mysqli_num_rows($get_list_res) < 1) 
	{
		//If no records were returned display a message on screen.
		$display_block .= "<p><em>Sorry, no records to select!<br><br><a href='addressBookMenu.html'>Main Menu</a></em></p>";

	} 
	else 
	{
		//If one or more records were returned...

		//Start building the drop-down list.
		$display_block .= "
		<form method=\"post\" action=\"".$_SERVER['PHP_SELF']."\">
		<p><label for=\"sel_id\">Select a Record:</label><br/>
		<select id=\"sel_id\" name=\"sel_id\" required=\"required\">
		<option value=\"\">-- Select One --</option>";

		//Fill the drop-down list with the owner names.
		while ($recs = mysqli_fetch_array($get_list_res)) {
			$ownerID = $recs['OwnerID'];
			$display_name = stripslashes($recs['display_name']);
			$display_block .= "<option value=\"" . $ownerID . "\">" . $display_name . "</option>";
		}

		//Finish building the drop-down list.
		$display_block .= "
		</select><br><br></p>
		<button type=\"submit\" name=\"submit\" value=\"view\">&nbsp;&nbsp;View Entry&nbsp;&nbsp;</button>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='addressBookMenu.html'>Main Menu</a>
		</form>";
	}
	//Release the resources used for the resultset.
	mysqli_free_result($get_list_res);

} 
else if ($_POST) 
{
	//If the page is in the postback condition...

	//Check the value for the required field.
	if ($_POST['sel_id'] == "")  
	{
		//If there was no value for the required field redirect the user back to the page to select an entry.
		header("Location: SelectEntry.php");
		exit;
	}

	//Create safe version of ID.
	$safe_id = mysqli_real_escape_string($mysqli, $_POST['sel_id']);

	//Build a SQL statement to retrieve the vehicle owner's information.
	$get_master_sql = "SELECT concat_ws(' ', FirstName, LastName) as display_name
	                   FROM Owner WHERE OwnerID = '" . $safe_id . "'";
	$get_master_res = mysqli_query($mysqli, $get_master_sql) or die(mysqli_error($mysqli));

	//Get the owner's full name from the resultset that was returned.
	while ($name_info = mysqli_fetch_array($get_master_res)) 
	{
		$display_name = stripslashes($name_info['display_name']);
	}

	$display_block = "<h1>Showing Record for <br>".$display_name."</h1>";

	//Release the resources used for the resultset.
	mysqli_free_result($get_master_res);

	//Build a SQL statement to retrieve the owner's vehicle information.
	$get_addresses_sql = "SELECT StreetAddress, City, State, Zipcode
	                      FROM OwnerAddress WHERE OwnerID = '" . $safe_id . "'";
	$get_addresses_res = mysqli_query($mysqli, $get_addresses_sql) or die(mysqli_error($mysqli));

	//Check to see if any record were returned.
 	if (mysqli_num_rows($get_addresses_res) > 0) {
		//If a record was returned...

		//Display the sub-heading.
		$display_block .= "<p><strong>Owner Address:</strong><br/>
		<ul>";

		//Get and display the address for the vehicle owner.
		while ($add_info = mysqli_fetch_array($get_addresses_res)) 
		{
			$address = stripslashes($add_info['StreetAddress']);
			$city = stripslashes($add_info['City']);
			$state = stripslashes($add_info['State']);
			$zipcode = stripslashes($add_info['Zipcode']);

			$display_block .= "<li>$address, $city, $state $zipcode </li>";
		}

		$display_block .= "</ul>";
	}

	//Release the resources used for the resultset.
	mysqli_free_result($get_addresses_res);

	//Get all the telephone numbers for the vehicle owner.
	$get_tel_sql = "SELECT PhoneNumber FROM OwnerTelephone
	                WHERE OwnerID = '" . $safe_id . "'";
	$get_tel_res = mysqli_query($mysqli, $get_tel_sql) or die(mysqli_error($mysqli));

	//Check to see if any record were returned.
	if (mysqli_num_rows($get_tel_res) > 0) 
	{
		//If a record was returned...

		//Display the sub-heading.
		$display_block .= "<p><strong>Owner Telephone #:</strong><br/>
		<ul>";

		//Get and display the telephone number for the vehicle owner.
		while ($tel_info = mysqli_fetch_array($get_tel_res)) 
		{
			$tel_number = stripslashes($tel_info['PhoneNumber']);

			$display_block .= "<li>$tel_number </li>";
		}

		$display_block .= "</ul>";
	}

	//Release the resources used for the resultset.
	mysqli_free_result($get_tel_res);	

	//Build a SQL statement to retrieve the owner's vehicle information.
	$get_vehicle_sql = "SELECT VehicleName, Description, MovieTitle, Year, Make, Model FROM Vehicle
	                  WHERE OwnerID = '".$safe_id."'";
	$get_vehicle_res = mysqli_query($mysqli, $get_vehicle_sql) or die(mysqli_error($mysqli));

	//Check to see if any record were returned.
	if (mysqli_num_rows($get_vehicle_res) > 0) 
	{
		//If a record was returned...

		//Display the sub-heading.
		$display_block .= "<p><strong>Movie Vehicle</strong><br/>
		<ul>";

		//Get and display the owner's vehicle information.
		while ($vehicle_info = mysqli_fetch_array($get_vehicle_res)) 
		{
			$vehicleName = $vehicle_info['VehicleName'];
			$description = $vehicle_info['Description'];
			$MovieTitle = $vehicle_info['MovieTitle'];
			$year = $vehicle_info['Year'];
			$make = $vehicle_info['Make'];
			$model = $vehicle_info['Model'];

			$display_block .= "<li>Vehicle: $vehicleName <br> Movie Title: $MovieTitle <br> Year: $year, Make: $make, Model: $model<br>Description: $description</li>";
		}

		$display_block .= "</ul>";
	}

	//Buld a SQL Statement to retrieve the event information.
	$get_event_sql = "SELECT EventTitle, EventDate, EventVenue, StreetAddress, City, State, Zipcode, PhoneNumber FROM Event
	                WHERE OwnerID = '".$safe_id."'";
	$get_event_res = mysqli_query($mysqli, $get_event_sql) or die(mysqli_error($mysqli));

	//Check to see if any record were returned.
	if (mysqli_num_rows($get_event_res) > 0) 
	{
		//If a record was returned...

		//Display the sub-heading.
		$display_block .= "<p><strong>Upcoming Event</strong><br/>
		<ul>";

		//Get and dislay the event the vehicle will be appearing at.
		while ($addressInfo = mysqli_fetch_array($get_event_res)) 
		{
			$eventTitle =  $addressInfo['EventTitle'];
			$eventDate = $addressInfo['EventDate'];
			$eventVenue = $addressInfo['EventVenue'];
			$eventAddress = $addressInfo['StreetAddress'];
			$eventCity =  $addressInfo['City'];
			$eventState = $addressInfo['State'];
			$eventZipcode = $addressInfo['Zipcode'];
			$eventPhone = $addressInfo['PhoneNumber'];

			$display_block .= "<li>Event: $eventTitle<br> Event Date: $eventDate <br> Venue: $eventVenue <br> Venue Address: $eventAddress, $eventCity, $eventState $eventZipcode<br>Venue Phone #: $eventPhone <br><br></li>";
		}

		$display_block .= "</ul>";
	}

	//Release the resources used for the resultset.
	mysqli_free_result($get_event_res);

	//Display links to allow the user to update the record, select another record, or go to the main menu.
	$display_block .= "<br/><p style=\"text-align: center\"><a href=\"ChangeEntry.php?change_id=".$_POST['sel_id']."\">Update Info</a> ...<a href=\"".$_SERVER['PHP_SELF']."\">Select Another</a>...<a href='addressBookMenu.html'>Main Menu</a></p>";
}
//close connection to MySQL
mysqli_close($mysqli);
?>
<!DOCTYPE html>
<html>
	<head>
		<title>My Records</title>
		<link href="css/MovieVehicles.css" type="text/css" rel="stylesheet" />
	</head>
	<body>
		<br><br>
		<div class="flex-container">
			<div class="center">
				<br><br>
				<?php echo $display_block; ?>
				<br><br>
			</div>
		</div>
		<br><br>
	</body>
</html>