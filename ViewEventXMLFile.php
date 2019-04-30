<?php

//Load the XML file.
$xmlList = simplexml_load_file("EventListing.xml") or die("Error: Cannot Open File");

//Add the heading for the owner list.
$display_block =  "<h1>Movie Vehicle<br>Event Listing</h1>";
$display_block .=  "<ul>";

//Loop through the contents of the file and read the information.
foreach($xmlList->event as $e)
{
	//Get the information for the individual vehicle.
	//$eventID = $e->eventid;
    $eventTitle = $e->eventtitle;
    $eventDate = $e->eventdate;
    $eventVenue = $e->eventvenue;
	$streetAddress = $e->streetaddress;
	$city = $e->City;
	$state = $e->state;
    $zipcode = $e->zipcode;
    $phoneNumber = $e->phonenumber;
    $onwerName = $e->ownername;
    $vehicleName = $e->vehiclename;

    //Start a new list item.
    $display_block .=  "<li>";

    //Format the data from the XML File to display the vehicle information on screen.
    //$display_block .=  "Event ID: " . $eventID . "<br>";
    $display_block .=  "Event Title: " . $eventTitle . "<br>";
    $display_block .=  "Event Date: " . $eventDate . "<br>";
    $display_block .=  "Event Venue: <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $eventVenue . "<br>";
    $display_block .=  "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $streetAddress . "<br>";
    $display_block .=  "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $city . ", " . $state . " " . $zipcode . "<br>";
    $display_block .=  "Phone #: " . $phoneNumber . "<br>";
    $display_block .=  "Owner Name: " . $onwerName . "<br>";
    $display_block .=  "Vehicle Name: " . $vehicleName . "<br>";

    //Close the list item.
    $display_block .=  "</li><br>";
}

//Close the unorder list of vehicle owners.
$display_block .=  "</ul>";

//Display a link to return to the main menu.
$display_block .=  "<br>";
$display_block .=  "<p style='text-align:center;font-size:2em;'><a href='addressBookMenu.html'>Main Menu</a></p>";

?>

<!DOCTYPE html>
<html>
    <head>
        <title>View Event Information</title>
        <link href="css/MovieVehicles.css" rel="stylesheet" type="text/css">
    </head>
    <body >
        <br/><br/>
        <div class="flex-container">
            <div class="center">
                <br/><br/>
                <?php echo $display_block  ?>
                <br/>
            </div>
        </div>
    </body>
</html>