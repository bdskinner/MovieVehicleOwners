<?php

//Include Files.
include 'ConnectToDB.php';

//connect to database.
doDB();

//Build the SQL query to get the vehicle information and executed it against the database.
$sql = "SELECT EventID, EventTitle, EventDate, EventVenue, StreetAddress, City, State, Zipcode, PhoneNumber, FirstName, LastName, VehicleName 
FROM Event INNER JOIN Owner ON Event.OwnerID = Owner.OwnerID INNER JOIN Vehicle ON Owner.OwnerID = Vehicle.OwnerID";
$result = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));

//Check the number of records returned by the query.
if (mysqli_num_rows($result) > 0) 
{
    //If one or more records were found...

    //Start the xml string with the root tag.
    $xml = "<eventlist>";

    //Loop throught the records retrieved from the database and create the tags for the child data.
    while($rvl = mysqli_fetch_array($result))
    {
        $xml .= "<event>";
		//$xml .= "<eventid>" . $rvl['EventID'] . "</eventid>";
		$xml .= "<eventtitle>" . $rvl['EventTitle'] . "</eventtitle>";
        $xml .= "<eventdate>" . $rvl['EventDate'] . "</eventdate>";
        $xml .= "<eventvenue>" . $rvl['EventVenue'] . "</eventvenue>";
		$xml .= "<streetaddress>" . $rvl['StreetAddress'] . "</streetaddress>";
		$xml .= "<City>" . $rvl['City'] . "</City>";
		$xml .= "<State>" . $rvl['State'] . "</State>";
        $xml .= "<Zipcode>" . $rvl['Zipcode'] . "</Zipcode>";
        $xml .= "<phonenumber>" . $rvl['PhoneNumber'] . "</phonenumber>";
        $xml .= "<ownername>" . $rvl['LastName'] . " " . $rvl['FirstName'] . "</ownername>";
        $xml .= "<vehiclename>" . $rvl['VehicleName'] . "</vehiclename>";
	    $xml .= "</event>";
    }
    
    //Finish the xml string with the closing root tag.
    $xml .= "</eventlist>";

    //Create the xml file.
    $sxe = new SimpleXMLElement($xml);
    $sxe -> asXML("EventListing.xml");

    //Display a message to the user that the file was created.
    $display_block = "<h2>The Event List file has been created.</h2>";

    $display_block .= "<p style='text-align:center;font-size:2em;'><a href='ViewEventXMLFile.php'>View Event List</a><br>";
    $display_block .= "<a href='addressBookMenu.html'>Main Menu</a></p>";
} 
else 
{
    //If no records were found display an error message to the user.
    $display_block = "<h2>No event information was found.</h2>";
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