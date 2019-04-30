<?php

//Include Files.
include 'ConnectToDB.php';

//Connect to the database.
doDB();

if (!$_POST)  
{
	//If the page is not in the postback condition...

	//Display the page heading.
	$display_block = "<h1>Select an Entry</h1>";

	//Get the owner's first and last name to fill the drop-down list.
	$get_list_sql = "SELECT OwnerID,
	                 CONCAT_WS(', ', LastName, FirstName) AS display_name
	                 FROM Owner ORDER BY LastName, FirstName";
	$get_list_res = mysqli_query($mysqli, $get_list_sql) or die(mysqli_error($mysqli));

	//Check the resultset to see if any records were retrieved.
	if (mysqli_num_rows($get_list_res) < 1) 
	{
		//If no records were found display a message to the user.
		$display_block .= "<p><em>Sorry, no records to select!<br><br><a href='addressBookMenu.html'>Main Menu</a></em></p>";

	} 
	else 
	{
		//If one or more records were found...

		//Start building the form and drop-down list.
		$display_block .= "
		<form method=\"post\" action=\"".$_SERVER['PHP_SELF']."\">
		<p><label for=\"sel_id\">Select a Record:</label><br/>
		<select id=\"sel_id\" name=\"sel_id\" required=\"required\">
		<option value=\"\">-- Select One --</option>";

		//Fill the drop-down list with the owner names.
		while ($recs = mysqli_fetch_array($get_list_res)) {
			$id = $recs['OwnerID'];
			$display_name = stripslashes($recs['display_name']);
			$display_block .= "<option value=\"".$id."\">".$display_name."</option>";
		}

		//Close the drop-down list and display the submit button and link to the main menu.
		$display_block .= "
		</select><br><br></p>
		<button type=\"submit\" name=\"submit\" value=\"del\">&nbsp;&nbsp;Delete Entry&nbsp;&nbsp;</button>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='addressBookMenu.html'>Main Menu</a>
		</form>";
	}

	//Release the system resources used in the resultset.
	mysqli_free_result($get_list_res);
} 
else if ($_POST) 
{
	//If the page is in the postback condition...

	//Check the value of the required field.
	if ($_POST['sel_id'] == "")  
	{
		//If there is no value for the required field redirect the user back to the page to select a record.
		header("Location: DeleteEntry.php");
		exit;
	}

    //create safe version of ID
    $safe_id = mysqli_real_escape_string($mysqli, $_POST['sel_id']);

	//Delete the owner's information from the Owner table.
	$del_master_sql = "DELETE FROM Owner WHERE OwnerID = '".$safe_id."'";
	$del_master_res = mysqli_query($mysqli, $del_master_sql) or die(mysqli_error($mysqli));

	//Delete the owner's address information from the OwnerAddress table.
	$del_address_sql = "DELETE FROM OwnerAddress WHERE OwnerID = '".$safe_id."'";
	$del_address_res = mysqli_query($mysqli, $del_address_sql) or die(mysqli_error($mysqli));

	//Delete the owner's telephone information from the OwnerTelephone table.
	$del_telephone_sql = "DELETE FROM OwnerTelephone WHERE OwnerID = '".$safe_id."'";
	$del_telephone_res = mysqli_query($mysqli, $del_telephone_sql) or die(mysqli_error($mysqli));

	//Delete the owner's vehicle(s) from the Vehicles table.
	$del_vehicle_sql = "DELETE FROM Vehicle WHERE OwnerID = '".$safe_id."'";
	$del_vehicle_res = mysqli_query($mysqli, $del_vehicle_sql) or die(mysqli_error($mysqli));

	//Delete all the events the selcted owner is scheduled to appear at from the Event table.
	$del_event_sql = "DELETE FROM Event WHERE OwnerID = '".$safe_id."'";
	$del_event_res = mysqli_query($mysqli, $del_event_sql) or die(mysqli_error($mysqli));

	//Close the connection to the database.
	mysqli_close($mysqli);

	//Display a message to the user telling them the deletions were successful.
	$display_block = "<h2>Record(s) Deleted</h2>";
	$display_block .= "<p><a href=\"DeleteEntry.php\">Delete Another</a><br><br>";
	$display_block .= "<a href='addressBookMenu.html'>Main Menu</a></p>";
	$display_block .= "<br><br>";
}
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
