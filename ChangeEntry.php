<?php
session_start();
include 'ConnectToDB.php';
doDB();

if (!$_POST)  
{
	//haven't seen the selection form, so show it
	//$display_block = "<h1>Select an Entry to Update</h1>";
	$display_block = "<div class='center'><br><br><h1>Select an Entry</h1>";

	//get parts of records
	$get_list_sql = "SELECT OwnerID,
	                 CONCAT_WS(', ', LastName, FirstName) AS display_name
	                 FROM Owner ORDER BY LastName, FirstName";
	$get_list_res = mysqli_query($mysqli, $get_list_sql) or die(mysqli_error($mysqli));

	if (mysqli_num_rows($get_list_res) < 1) 
	{
		//no records
		$display_block .= "<p><em>Sorry, no records to select!<br><br><a href='addressBookMenu.html'>Main Menu</a></em></p>";

	} 
	else 
	{
		//If one or more records were found...

		//Start building the form and the drop-down list that will contain the owner names.
		$display_block .= "
		<form method=\"post\" action=\"".$_SERVER['PHP_SELF']."\">
		<p><label for=\"change_id\">Select a Record to Update:</label><br/>
		<select id=\"change_id\" name=\"change_id\" required=\"required\">
		<option value=\"\">-- Select One --</option>";

		//Fill the drop-down list with the owner names.
		while ($recs = mysqli_fetch_array($get_list_res)) {
			$id = $recs['OwnerID'];
			$display_name = stripslashes($recs['display_name']);
			$display_block .= "<option value=\"".$id."\">".$display_name."</option>";
		}

		//Close the drop-down list and add the html for the button and main menu link.
		$display_block .= "
		</select><br><br></p>
		<button type=\"submit\" name=\"submit\" value=\"change\">Change Entry</button>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='addressBookMenu.html'>Main Menu</a>
		</form>";
	}

	//Release the resources used for the resultset.
	mysqli_free_result($get_list_res);

} 
else if ($_POST) 
{
	//If postback condition...

	//check for required fields
	if ($_POST['change_id'] == "")  {
		header("Location: ChangeEntry.php");
		exit;
	}

	//Create safe version of ID.
	$safe_id = mysqli_real_escape_string($mysqli, $_POST['change_id']);
	$_SESSION["id"]=$safe_id;

	//Set the flags that determine whether information is present to be modified to true.
	$_SESSION["address"]="true";
	$_SESSION["telephone"]="true";
	$_SESSION["vehicle"]="true";
	$_SESSION["event"]="true";
	$_SESSION["owner"]="true";

	//Build the SQL statement to get the owner's first name and last name.
	$get_master_sql = "SELECT FirstName, LastName FROM Owner WHERE OwnerID = '".$safe_id."'";
	$get_master_res = mysqli_query($mysqli, $get_master_sql) or die(mysqli_error($mysqli));

	//Retrieve the owner's first name and last name from the resultset.
	while ($name_info = mysqli_fetch_array($get_master_res)) {
		$display_fname = stripslashes($name_info['FirstName']);
		$display_lname = stripslashes($name_info['LastName']);		
	}

	//Start bulding the form.
	$display_block = "<div><h1>Update an Entry</h1>";
	$display_block .= "<br><br>";
	$display_block.="<form method='post' action='Change.php'>";

	//Release the resources used for the resultset.
	mysqli_free_result($get_master_res);

	//Build the SQL statement to get the owner's first name and last name.
	$get_addresses_sql = "SELECT FirstName, LastName FROM Owner WHERE OwnerID = '".$safe_id."'";
	$get_addresses_res = mysqli_query($mysqli, $get_addresses_sql) or die(mysqli_error($mysqli));

	//Check to see if any results were found.
	if (mysqli_num_rows($get_addresses_res) > 0) 
	{
		//If a record was found...
		
		//Open the fieldset for the first name and last name textboxes.
		$display_block .= "<fieldset>";
		$display_block .="<legend>Vehicle Owner Information:</legend><br/>";

		//Get the information from the resultset and fill in the owner's first and last name into the textboxes.
		while ($add_info = mysqli_fetch_array($get_addresses_res)) 
		{
			//Get the owner's first name and last name from the resultset.
			$firstName = stripslashes($add_info['FirstName']);
			$lastName = stripslashes($add_info['LastName']);

			//Add the textbox for the owner's first name.
			$display_block .="<label for='f_name'>First Name</label><br>";
			$display_block .="<input type='text' name='f_name' size='20' maxlength='25' required='required'  value='".$firstName."'/><br><br>";

			//Add the textbox for the owner's last name.
			$display_block .="<label for='l_name'>Last Name</label><br>";
			$display_block .="<input type='text' name='l_name' size='30' maxlength='35' required='required'  value='".$lastName."'/><br><br>";
		}

		//Close the fieldset.
		$display_block .="</fieldset>";
	}

	//Release the resources used for the resultset.
	mysqli_free_result($get_addresses_res);

	//Build the SQL statement and execute it to get the vehicle information.
	$get_tel_sql = "SELECT VehicleName, Description, MovieTitle, Year, Make, Model FROM Vehicle
	                WHERE OwnerID = '".$safe_id."'";
	$get_tel_res = mysqli_query($mysqli, $get_tel_sql) or die(mysqli_error($mysqli));

	//Check to see if any results were found.
	if (mysqli_num_rows($get_tel_res) > 0) 
	{
		//If a record was found...

		//Get the information from the resultset and fill in the vehicle information into the textboxes.
		while ($tel_info = mysqli_fetch_array($get_tel_res)) 
		{
			//Get The values to be updated.
			$vehicleName = stripslashes($tel_info['VehicleName']);
			$description = stripslashes($tel_info['Description']);
			$movieTitle = stripslashes($tel_info['MovieTitle']);
			$year = $tel_info['Year'];
			$make = stripslashes($tel_info['Make']);
			$model = stripslashes($tel_info['Model']);
			
			//Display the values in the textboxes.
			$display_block .= "<fieldset>";
			$display_block .= "<legend>Movie Vehicle Information</legend><br/>";
			$display_block .= "<label for='vehicleName'>Vehicle Name</label><br>";
			$display_block .= "<input type='text' id='vehicleName' name='vehicleName' size='30' maxlength='70' value='".$vehicleName."' required='required' /><br><br>";

			$display_block .= "<label for='movieTitle'>Movie Title <br>(that vehicle appeared in)</label><br>";
			$display_block .= "<input type='text' id='movieTitle' name='movieTitle' size='30' maxlength='70' value='".$movieTitle."' required='required' /><br><br>";

			$display_block .= "<label for='year'>Vehicle Year </label><br>";
			$display_block .= "<input type='text' id='year' name='year' size='30' maxlength='4' value='".$year."' required='required' /><br><br>";

			$display_block .= "<label for='make'>Vehicle Make</label><br>";
			$display_block .= "<input type='text' id='make' name='make' size='30' maxlength='25' value='".$make."' required='required' /><br><br>";

			$display_block .= "<label for='model'>Vehicle Model</label><br>";
			$display_block .= "<input type='text' id='model' name='model' size='30' maxlength='25' value='".$model."' required='required' /><br><br>";

			$display_block .= "<label for='description'>Vehicle Description</label><br>";
			$display_block .= "<textarea id='description' name='description' cols='35' rows='5' maxlength='490' required='required'>$description</textarea><br><br>";
			$display_block .= "</fieldset><br>";
		}
	}
	else
	{
		//If no record was found display empy textboxes.
		$_SESSION["vehicle"]='false';	
		$display_block .= "<fieldset>";
		$display_block .= "<legend>Movie Vehicle Information</legend><br/>";
		$display_block .= "<label for='vehicleName'>Vehicle Name</label><br>";
		$display_block .= "<input type='text' id='vehicleName' name='vehicleName' size='30' maxlength='70' required='required' /><br><br>";

		$display_block .= "<label for='movieTitle'>Movie Title (that vehicle appeared in)</label><br>";
		$display_block .= "<input type='text' id='movieTitle' name='movieTitle' size='30' maxlength='70' required='required' /><br><br>";

		$display_block .= "<label for='year'>Vehicle Year </label><br>";
		$display_block .= "<input type='text' id='year' name='year' size='30' maxlength='4' required='required' /><br><br>";

		$display_block .= "<label for='make'>Vehicle Make</label><br>";
		$display_block .= "<input type='text' id='make' name='make' size='30' maxlength='25' required='required' /><br><br>";

		$display_block .= "<label for='model'>Vehicle Model</label><br>";
		$display_block .= "<input type='text' id='model' name='model' size='30' maxlength='25' required='required' /><br><br>";

		$display_block .= "<label for='description'>Vehicle Description</label><br>";
		$display_block .= "<textarea id='description' name='description' cols='35' rows='5' maxlength='490' required='required'></textarea><br><br>";
		$display_block .= "</fieldset><br>";
	}

	//Release the resources used for the resultset.
	mysqli_free_result($get_tel_res);

	//Build the SQL statement and execute it to get the event information.
	$eventSQL = "SELECT EventTitle, EventDate, EventVenue, StreetAddress, City, State, Zipcode, PhoneNumber FROM Event
	WHERE OwnerID = '".$safe_id."'";
	$eventResult = mysqli_query($mysqli, $eventSQL) or die(mysqli_error($mysqli));

	//Check to see if any results were found.
	if (mysqli_num_rows($eventResult) > 0) 
	{
		//If a record was found...

		//Get the information from the resultset and fill in the event information into the textboxes.
		while ($eventInfo = mysqli_fetch_array($eventResult)) 
		{
			$eventTitle = stripslashes($eventInfo['EventTitle']);
			$evenDate = stripslashes($eventInfo['EventDate']);
			$eventVenue = stripslashes($eventInfo['EventVenue']);
			$streetAddress = $eventInfo['StreetAddress'];
			$eventCity = stripslashes($eventInfo['City']);
			$eventState = stripslashes($eventInfo['State']);
			$eventZipcode = stripslashes($eventInfo['Zipcode']);
			$venuePhone = stripslashes($eventInfo['PhoneNumber']);

			$display_block .= "<fieldset>";
			$display_block .= "<legend>Upcoming Event Information</legend><br/>";
			$display_block .= "<label for='eventTitle'>Event Title</label><br>";
			$display_block .= "<input type='text' id='eventTitle' name='eventTitle' size='30' maxlength='25' value='".$eventTitle."' /><br><br>";

			$display_block .= "<label for='eventDate'>Event Date</label><br>";
			$display_block .= "<input type='text' id='eventDate' name='eventDate' size='30' maxlength='13' value='".$evenDate."' /><br><br>";

			$display_block .= "<label for='eventVenue'>Event Venue</label><br>";
			$display_block .= "<input type='text' id='eventVenue' name='eventVenue' size='30' maxlength='45' value='".$eventVenue."' /><br><br>";

			$display_block .= "<label for='venueAddress'>Venue Address</label><br>";
			$display_block .= "<input type='text' id='venueAddress' name='venueAddress' size='30' maxlength='45' value='".$streetAddress."' /><br><br>";

			$display_block .= "<label for='venueCity'>Venue City</label><br>";
			$display_block .= "<input type='text' id='venueCity' name='venueCity' size='30' maxlength='45' value='".$eventCity."' /><br><br>";

			$display_block .= "<label for='venueState'>Venue State</label><br>";
			$display_block .= "<input type='text' id='venueState' name='venueState' size='30' maxlength='2' value='".$eventState."' /><br><br>";

			$display_block .= "<label for='venueZipcode'>Venue Zipcode</label><br>";
			$display_block .= "<input type='text' id='venueZipcode' name='venueZipcode' size='30' maxlength='10' value='".$eventZipcode."' /><br><br>";

			$display_block .= "<label for='venuePhone'>Venue Phone #</label><br>";
			$display_block .= "<input type='text' id='venuePhone' name='venuePhone' size='30' maxlength='24' value='".$venuePhone."' /><br><br>";
			$display_block .= "</fieldset><br>";
		}
	}
	else 
	{
		//If no record was found display empty textboxes.
		$_SESSION["event"]='false';
		$display_block .= "<fieldset>";
		$display_block .= "<legend>Upcoming Event Information</legend><br/>";
		$display_block .= "<label for='eventTitle'>Event Title</label><br>";
		$display_block .= "<input type='text' id='eventTitle' name='eventTitle' size='30' maxlength='25' /><br><br>";

		$display_block .= "<label for='eventDate'>Event Date</label><br>";
		$display_block .= "<input type='text' id='eventDate' name='eventDate' size='30' maxlength='13' /><br><br>";

		$display_block .= "<label for='eventVenue'>Event Venue</label><br>";
		$display_block .= "<input type='text' id='eventVenue' name='eventVenue' size='30' maxlength='45' /><br><br>";

		$display_block .= "<label for='venueAddress'>Venue Address</label><br>";
		$display_block .= "<input type='text' id='venueAddress' name='venueAddress' size='30' maxlength='45' /><br><br>";

		$display_block .= "<label for='venueCity'>Venue City</label><br>";
		$display_block .= "<input type='text' id='venueCity' name='venueCity' size='30' maxlength='45' /><br><br>";

		$display_block .= "<label for='venueState'>Venue State</label><br>";
		$display_block .= "<input type='text' id='venueState' name='venueState' size='30' maxlength='2' /><br><br>";

		$display_block .= "<label for='venueZipcode'>Venue Zipcode</label><br>";
		$display_block .= "<input type='text' id='venueZipcode' name='venueZipcode' size='30' maxlength='10' /><br><br>";

		$display_block .= "<label for='venuePhone'>Venue Phone #</label><br>";
		$display_block .= "<input type='text' id='venuePhone' name='venuePhone' size='30' maxlength='24' /><br><br>";
		$display_block .= "</fieldset><br>";
	}

	//Release the resources used for the resultset.
	mysqli_free_result($eventResult);

	//Build the SQL statement and execute it to get the owner telephone information.
	$teleponeSQL = "SELECT PhoneNumber FROM OwnerTelephone 
	WHERE OwnerID = '".$safe_id."'";
	$teleponeResult = mysqli_query($mysqli, $teleponeSQL) or die(mysqli_error($mysqli));

	//Check to see if any results were found.
	if (mysqli_num_rows($teleponeResult) > 0) 
	{
		//If a record was found...

		//Get the information from the resultset and fill in the owner's telephone # into the textboxes.
		while ($teleponeInfo = mysqli_fetch_array($teleponeResult)) 
		{
			$ownerPhone = stripslashes($teleponeInfo['PhoneNumber']);

			$display_block .= "<fieldset>";
			$display_block .= "<legend>Owner Telephone Number Information</legend><br/>";
			$display_block .= "<label for='phoneNumber'>Owner Phone #</label><br>";
			$display_block .= "<input type='text' id='phoneNumber' name='phoneNumber' size='30' maxlength='23' value='".$ownerPhone."' required='required'/><br><br>";
			$display_block .= "</fieldset><br><br><br>";
		}
	}
	else if(mysqli_num_rows($teleponeResult) <= 0)
	{
		//If no record was found display an empty textbox.
		$_SESSION["telephone"]='false';
		$display_block .= "<fieldset>";
		$display_block .= "<legend>Owner Telephone Number Information</legend><br/>";
		$display_block .= "<label for='phoneNumber'>Owner Phone #</label><br>";
		$display_block .= "<input type='text' id='phoneNumber' name='phoneNumber' size='30' maxlength='23' required='required' /><br><br>";
		$display_block .= "</fieldset><br><br><br>";
	}

	//Release the resources used for the resultset.
	mysqli_free_result($teleponeResult);

	//Build the SQL statement and execute it to get the owner address information.
	$addressSQL = "SELECT StreetAddress, City, State, Zipcode FROM OwnerAddress 
	WHERE OwnerID = '".$safe_id."'";
	$addressResult = mysqli_query($mysqli, $addressSQL) or die(mysqli_error($mysqli));

	//Check to see if any results were found.
	if (mysqli_num_rows($addressResult) > 0) 
	{
		//If a record was found...

		//Get the information from the resultset and fill in the owner's address information into the textboxes.
		while ($addressInfo = mysqli_fetch_array($addressResult)) 
		{
			$ownerAddress = stripslashes($addressInfo['StreetAddress']);
			$ownerCity = stripslashes($addressInfo['City']);
			$ownerState = stripslashes($addressInfo['State']);
			$ownerZipcode = stripslashes($addressInfo['Zipcode']);

			$display_block .= "<fieldset>";
			$display_block .= "<legend>Owner Address Information</legend><br/>";
			$display_block .= "<label for='ownerAddresss'>Street Address</label><br>";
			$display_block .= "<input type='text' id='ownerAddress' name='ownerAddress' size='30' maxlength='70' value='".$ownerAddress."' required='required'/><br><br>";

			$display_block .= "<label for='ownerCity'>City</label><br>";
			$display_block .= "<input type='text' id='ownerCity' name='ownerCity' size='30' maxlength='45' value='".$ownerCity."' required='required'/><br><br>";

			$display_block .= "<label for='ownerState'>State</label><br>";
			$display_block .= "<input type='text' id='ownerState' name='ownerState' size='30' maxlength='2' value='".$ownerState."' required='required'/><br><br>";

			$display_block .= "<label for='ownerZipcode'>Zipcode</label><br>";
			$display_block .= "<input type='text' id='ownerZipcode' name='ownerZipcode' size='30' maxlength='10' value='".$ownerZipcode."' required='required'/><br><br>";
			$display_block .= "</fieldset><br>";
		}
	}
	else
	{
		//If no record was found display empty textboxes.
		$_SESSION["address"]='false';
		$display_block .= "<fieldset>";
		$display_block .= "<legend>Owner Address Information</legend><br/>";
		$display_block .= "<label for='ownerAddresss'>Street Address</label><br>";
		$display_block .= "<input type='text' id='ownerAddress' name='ownerAddress' size='30' maxlength='70' required='required' /><br><br>";

		$display_block .= "<label for='ownerCity'>City</label><br>";
		$display_block .= "<input type='text' id='ownerCity' name='ownerCity' size='30' maxlength='45' required='required' /><br><br>";

		$display_block .= "<label for='ownerState'>State</label><br>";
		$display_block .= "<input type='text' id='ownerState' name='ownerState' size='30' maxlength='2' required='required' /><br><br>";

		$display_block .= "<label for='ownerZipcode'>Zipcode</label><br>";
		$display_block .= "<input type='text' id='ownerZipcode' name='ownerZipcode' size='30' maxlength='10' required='required' /><br><br>";
		$display_block .= "</fieldset><br>";
	}

	//Release the resources used for the resultset.
	mysqli_free_result($addressResult);

	//Display the submit button and link to the main menu.
	$display_block .= "<fieldset>";
	$display_block .= "<br>";
	$display_block .= "<p style=\"text-align: center\"><button type='submit' name='submitChange' id='submitChange' value='submitChange'>Change Entry</button>";
	$display_block .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='addressBookMenu.html'>Main Menu</a></p></form>";
	$display_block .= "<br>";
	$display_block .= "</fieldset>";
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
		<div class="flex-container">
			<br><br>
			<?php echo $display_block; ?>
			<br><br>
		</div> 
	</body>
</html>

