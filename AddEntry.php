<?php
include 'ConnectToDB.php';

if (!$_POST) {
	//haven't seen the form, so show it
	$display_block = <<<END_OF_BLOCK
	<form method="post" action="$_SERVER[PHP_SELF]">

	<br><br>
	<h1>Add an Entry</h1>
	<br><br>
	
	<fieldset>
		<legend>Vehicle Owner Information:</legend><br/>
		<label for="f_name">First Name</label><br>
		<input type="text" name="f_name" size="20" maxlength="25" required="required" /><br><br>

		<label for="l_name">Last Name</label><br>
		<input type="text" name="l_name" size="30" maxlength="35" required="required" /><br><br>	
	</fieldset><br>

	<fieldset>
		<legend>Movie Vehicle Information</legend><br/>
		<label for="vehicleName">Vehicle Name</label><br>
		<input type="text" id="vehicleName" name="vehicleName" size="30" maxlength="70" required="required" /><br><br>

		<label for="movieTitle">Movie Title <br>(that vehicle appeared in)</label><br>
		<input type="text" id="movieTitle" name="movieTitle" size="30" maxlength="70" required="required" /><br><br>

		<label for="year">Vehicle Year </label><br>
		<input type="text" id="year" name="year" size="30" maxlength="4" required="required" /><br><br>

		<label for="make">Vehicle Make</label><br>
		<input type="text" id="make" name="make" size="30" maxlength="25" required="required" /><br><br>

		<label for="model">Vehicle Model</label><br>
		<input type="text" id="model" name="model" size="30" maxlength="25" required="required" /><br><br>

		<label for="description">Vehicle Description</label><br>
		<textarea id="description" name="description" cols="35" rows="5" maxlength="490" required="required"></textarea><br><br>
	</fieldset><br>

	<fieldset>
		<legend>Upcoming Event Information</legend><br/>
		<label for="eventTitle">Event Title</label><br>
		<input type="text" id="eventTitle" name="eventTitle" size="30" maxlength="25" /><br><br>

		<label for="eventDate">Event Date</label><br>
		<input type="text" id="eventDate" name="eventDate" size="30" maxlength="13" /><br><br>

		<label for="eventVenue">Event Venue</label><br>
		<input type="text" id="eventVenue" name="eventVenue" size="30" maxlength="45"" /><br><br>

		<label for="venueAddress">Venue Address</label><br>
		<input type="text" id="venueAddress" name="venueAddress" size="30" maxlength="45" /><br><br>

		<label for="venueCity">Venue City</label><br>
		<input type="text" id="venueCity" name="venueCity" size="30" maxlength="45" /><br><br>

		<label for="venueState">Venue State</label><br>
		<input type="text" id="venueState" name="venueState" size="30" maxlength="2" /><br><br>

		<label for="venueZipcode">Venue Zipcode</label><br>
		<input type="text" id="venueZipcode" name="venueZipcode" size="30" maxlength="10" /><br><br>

		<label for="venuePhone">Venue Phone #</label><br>
		<input type="text" id="venuePhone" name="venuePhone" size="30" maxlength="24" /><br><br>
	</fieldset><br>

	<fieldset>
		<legend>Owner Address Information</legend><br/>
		<label for="ownerAddresss">Street Address</label><br>
		<input type="text" id="ownerAddresss" name="ownerAddresss" size="30" maxlength="70" required="required" /><br><br>

		<label for="ownerCity">City</label><br>
		<input type="text" id="ownerCity" name="ownerCity" size="30" maxlength="45" required="required" /><br><br>

		<label for="ownerState">State</label><br>
		<input type="text" id="ownerState" name="ownerState" size="30" maxlength="2" required="required" /><br><br>

		<label for="ownerZipcode">Zipcode</label><br>
		<input type="text" id="ownerZipcode" name="ownerZipcode" size="30" maxlength="10" required="required" /><br><br>
	</fieldset><br>

	<fieldset>
		<legend>Owner Telephone Number Information</legend><br/>
		<label for="phoneNumber">Owner Phone #</label><br>
		<input type="text" id="phoneNumber" name="phoneNumber" size="30" maxlength="23" required="required" />
		<br><br>
	</fieldset><br><br><br>

	<fieldset>
		<br><br>
		<button type="submit" name="submit" value="send">&nbsp;&nbsp;&nbsp;Add Entry&nbsp;&nbsp;&nbsp;</button>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='addressBookMenu.html'>Main Menu</a>
		<br><br>
	</fieldset>
	</form>
	<br><br><br><br>
END_OF_BLOCK;

} else if ($_POST) {
	//time to add to tables, so check for required fields
	if (($_POST['f_name'] == "") || ($_POST['l_name'] == "") || $_POST['vehicleName'] == "" || $_POST['movieTitle'] == "" || $_POST['year'] == "" || $_POST['make'] == "" || $_POST['model'] == ""
	|| $_POST['description'] == "" || $_POST['ownerAddresss'] == "" || $_POST['ownerCity'] == "" || $_POST['ownerState'] == "" || $_POST['ownerZipcode'] == "" || $_POST['phoneNumber'] == "") 
	{
		header("Location: AddEntry.php");
		exit;
	}

	//connect to database
	doDB();

	//create clean versions of input strings
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
	$safe_ownerAddress = mysqli_real_escape_string($mysqli, $_POST['ownerAddresss']);
	$safe_ownerCity = mysqli_real_escape_string($mysqli, $_POST['ownerCity']);
	$safe_ownerState = mysqli_real_escape_string($mysqli, $_POST['ownerState']);
	$safe_ownerZipcode = mysqli_real_escape_string($mysqli, $_POST['ownerZipcode']);
	$safe_phoneNumber = mysqli_real_escape_string($mysqli, $_POST['phoneNumber']);

	//Insert new owner information into the Owner table.
	$add_master_sql = "INSERT INTO Owner (FirstName, LastName)
                       VALUES ('".$safe_f_name."', '".$safe_l_name."')";
	$add_master_res = mysqli_query($mysqli, $add_master_sql) or die(mysqli_error($mysqli));

	//Ge the OwnerID for the record just inserted for use with other tables.
	$ownerID = mysqli_insert_id($mysqli);

	//Check to see if any values were entered for the vehicle information.
	if (($_POST['vehicleName']) || ($_POST['movieTitle']) || ($_POST['year']) || ($_POST['make']) || ($_POST['model']) || ($_POST['description'])) 
	{
		//If the vehicle information was entered in the form insert the record into the Vehicle table.
		$add_address_sql = "INSERT INTO Vehicle (OwnerID, VehicleName, Description, MovieTitle, Year, Make, Model)
						  VALUES ('".$ownerID."', '".$safe_vehicleName."', '".$safe_description ."',
		                    '".$safe_movieTitle ."' , '".$safe_year."' , '".$safe_make."', '".$safe_model."')";
		$add_address_res = mysqli_query($mysqli, $add_address_sql) or die(mysqli_error($mysqli));
	}

	//Check to see if any values were entered for the upcoming event information.
	if ($_POST['eventTitle'] || $_POST['eventDate'] || $_POST['eventVenue'] || $_POST['venueAddress'] || $_POST['venueCity'] || $_POST['venueState']
	|| $_POST['venueZipcode'] || $_POST['venuePhone']) 
	{
		//If information for an event was entered in the form insert the record into the Event table.
		$add_event_sql = "INSERT INTO Event (OwnerID, EventTitle, EventDate, EventVenue, StreetAddress, City, State, Zipcode, PhoneNumber) 
		VALUES ('".$ownerID."', '".$safe_eventTitle."', '".$safe_eventDate."', '".$safe_eventVenue."', '".$safe_venueAddress."', 
		'".$safe_venueCity."', '".$safe_venueState."', '".$safe_venueZipcode."', '".$safe_venuePhone."' )";
		$add_event_sql = mysqli_query($mysqli, $add_event_sql) or die(mysqli_error($mysqli));
	}

	//If the owner's phone number information was entered in the form insert the record into the OwnerTelephone table.
	if ($_POST['phoneNumber']) {
		//If the owner's telephone # was entered in the form insert the record into the OwnerTelephone table.
		$add_phone_sql = "INSERT INTO OwnerTelephone (OwnerID, PhoneNumber)  
		VALUES ('".$ownerID."', '".$safe_phoneNumber."')";
		$add_phone_sql = mysqli_query($mysqli, $add_phone_sql) or die(mysqli_error($mysqli));
	}

	//If the owner's address information was entered in the form insert the record into the OwnerAddress table.
	if ($_POST['ownerAddresss'] || $_POST['ownerCity'] || $_POST['ownerState'] || $_POST['ownerZipcode']) {
		//If the owner's telephone # was entered in the form insert the record into the OwnerTelephone table.
		$add_address_sql = "INSERT INTO OwnerAddress (OwnerID, StreetAddress, City, State, Zipcode)  
		VALUES ('".$ownerID."', '".$safe_ownerAddress."', '".$safe_ownerCity."', '".$safe_ownerState."', '".$safe_ownerZipcode."')";
		$add_address_sql = mysqli_query($mysqli, $add_address_sql) or die(mysqli_error($mysqli));
	}

	//Close the connection to the database.
	mysqli_close($mysqli);

	//Display a confirmation message to the user that the record(s) have been added to the database.
	$display_block = "<br><br>";
	$display_block .= "<div class='center'>";
	$display_block .= "<br><br>";
	$display_block .= "<h2>Your entry has been added.</h2>";
	$display_block .= "<p><a href=\"AddEntry.php\">Add Another</a><br><br>";
	$display_block .= "<a href='addressBookMenu.html'>Main Menu</a></p>";
	$display_block .= "<br><br>";
	$display_block .= "</div>";
}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Add an Entry</title>
		<link href="css/MovieVehicles.css" type="text/css" rel="stylesheet" />
	</head>
	<body>
		<div class="flex-container">
			<?php echo $display_block; ?>
		</div>
	</body>
</html>