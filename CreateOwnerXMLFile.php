<?php

//Include Files.
include 'ConnectToDB.php';

//connect to database.
doDB();

//Build the SQL query to get the vehicle information and executed it against the database.
$sql = "SELECT Distinct Owner.OwnerID, FirstName, LastName, StreetAddress, City, State, Zipcode, PhoneNumber, VehicleName FROM Owner INNER JOIN OwnerAddress ON Owner.OwnerID = OwnerAddress.OwnerID INNER JOIN OwnerTelephone ON Owner.OwnerID = OwnerTelephone.OwnerID INNER JOIN Vehicle ON Owner.OwnerID = Vehicle.OwnerID";
$result = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));

//Check the number of records returned by the query.
if (mysqli_num_rows($result) > 0) 
{
    //If one or more records were found...

    //Start the xml string with the root tag.
    $xml = "<ownerlist>";

    //Loop throught the records retrieved from the database and create the tags for the child data.
    while($rvl = mysqli_fetch_array($result))
    {
        $xml .= "<owner>";
		$xml .= "<OwnerID>" . $rvl['OwnerID'] . "</OwnerID>";
		$xml .= "<FirstName>" . $rvl['FirstName'] . "</FirstName>";
		$xml .= "<LastName>" . $rvl['LastName'] . "</LastName>";
		$xml .= "<StreetAddress>" . $rvl['StreetAddress'] . "</StreetAddress>";
		$xml .= "<City>" . $rvl['City'] . "</City>";
		$xml .= "<State>" . $rvl['State'] . "</State>";
        $xml .= "<Zipcode>" . $rvl['Zipcode'] . "</Zipcode>";
        $xml .= "<PhoneNumber>" . $rvl['PhoneNumber'] . "</PhoneNumber>";
		$xml .= "<VehicleName>" . $rvl['VehicleName'] . "</VehicleName>";
	    $xml .= "</owner>";
    }
    
    //Finish the xml string with the closing root tag.
    $xml .= "</ownerlist>";

    //Create the xml file.
    $sxe = new SimpleXMLElement($xml);
    $sxe -> asXML("OwnerListing.xml");

    //Display a message to the user that the file was created.
    $display_block = "<h2>The Owner List file has been created.</h2>";

    $display_block .= "<p style='text-align:center;font-size:2em;'><a href='ViewOwnerXMLFile.php'>View Owner List</a><br>";
    $display_block .= "<a href='addressBookMenu.html'>Main Menu</a></p>";
} 
else 
{
    //If no records were found display an error message to the user.
    $display_block = "<h2>No owner information was found.</h2>";
    $display_block .= "<p style='text-align:center;font-size:2em;'><a href='addressBookMenu.html'>Main Menu</a></p>";
}

//Release the resources used for the resultset and close connection to MySQL
mysqli_free_result($result);
mysqli_close($mysqli);

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Create Owner Listing (XML)</title>
        <link href="css/MovieVehicles.css" rel="stylesheet" type="text/css">
    </head>
    <body >
        <br/><br/>
        <div class="flex-container">
            <div class="center">
                <br/><br/>
                <?php echo $display_block  ?>
                <br/><br/>
            </div>
        </div>
    </body>
</html>