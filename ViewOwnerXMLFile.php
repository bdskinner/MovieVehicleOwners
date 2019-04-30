<?php

//Load the XML file.
$xmlList = simplexml_load_file("OwnerListing.xml") or die("Error: Cannot Open File");

//Add the heading for the owner list.
$display_block =  "<h1>Movie Vehicle<br>Owner Listing</h1>";
$display_block .=  "<ul>";

//Loop through the contents of the file and read the information.
foreach($xmlList->owner as $o)
{
	//Get the information for the individual vehicle.
	$ownerID = $o->OwnerID;
	$firstName = $o->FirstName;
	$lastName = $o->LastName;	
	$streetAddress = $o->StreetAddress;
	$city = $o->City;
	$state = $o->State;
    $zipcode = $o->Zipcode;
    $phoneNumber = $o->PhoneNumber;
    $vehicleName = $o->VehicleName;

    //Start a new list item.
    $display_block .=  "<li>";

    //Format the data from the XML File to display the vehicle information on screen.
    $display_block .=  $firstName . " " . $lastName . "<br>";
    $display_block .=  $streetAddress . "<br>";
    $display_block .=  $city . ", " . $state . " " . $zipcode . "<br>";
    $display_block .=  "Phone #: " . $phoneNumber . "<br>";
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
        <title>View Owner Information</title>
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