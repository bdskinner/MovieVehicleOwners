<?php

$vehicles = file_get_contents('VehicleListing.json');

$display_block = "<h1>Famous Movie<br>Vehicle Listing</h1>";
$vehicleObj = json_decode($vehicles);
foreach($vehicleObj -> vehicles as $car)
{
    //Get the values for each vehicle in the file.
    $vehicleName = $car->VehicleName;
    $movieTitle = $car->MovieTitle;
    $year = $car->Year;
    $make = $car->Make;
    $model = $car->Model;
    $firstName = $car->FirstName;
    $lastName = $car->LastName;

    //Build a string to display the vehicle information on the screen.
    $display_block .= "<ul>";
    $display_block .= "<li>";
    $display_block .= "Vehicle Name: " . $vehicleName . "<br>";
    $display_block .= "Movie Title: " . $movieTitle . "<br>";
    $display_block .= "Year: " . $year . "<br>";
    $display_block .= "Make: " . $make . "<br>";
    $display_block .= "Model: " . $model . "<br>";
    $display_block .= "Owner: " . $firstName . " " . $lastName . "<br>";
    $display_block .= "</li>";
    $display_block .= "</ul>";
}

//Display a link to return to the main menu.
$display_block .=  "<br>";
$display_block .=  "<p style='text-align:center;font-size:2em;'><a href='addressBookMenu.html'>Main Menu</a></p>";

?>

<!DOCTYPE html>
<html>
    <head>
        <title>View Vehicle Listing</title>
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