<?php

//Include Files.
include 'ConnectToDB.php';

//connect to database.
doDB();

//Build the SQL statement to retrieve the vehicle information from the database.
$sql = "SELECT VehicleName, MovieTitle, Year, Make, Model, FirstName, LastName FROM Vehicle INNER JOIN Owner ON Vehicle.OwnerID = Owner.OwnerID;";
$result = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));

//Arrays to hold the data.
$response = array();  
$posts = array();    

//Check the number of records returned by the query.
if (mysqli_num_rows($result) > 0) 
{
    //Loop through the records returned from the query.
    while($row = $result -> fetch_assoc())
    {
        //Get each value for the individual record.
        $vehicleName = $row['VehicleName'];
        $movieTitle = $row['MovieTitle'];
        $year = $row['Year'];
        $make = $row['Make'];
        $model = $row['Model'];
        $firstName = $row['FirstName'];
        $lastName = $row['LastName'];

        //Store the individual values from the record in the posts array.
       $posts[]=array('VehicleName'=>$vehicleName, 'MovieTitle'=>$movieTitle, 'Year'=>$year, 'Make' => $make, 'Model' => $model, 'FirstName' => $firstName, 'LastName' => $lastName);
    }
    $response['vehicles'] = $posts;

    //Create the file to store the information.
    $filePtr = fopen('VehicleListing.json', 'w');
    fwrite($filePtr, json_encode($response));
    fclose($filePtr);

    //Create a message for the user to say the file was created.
    $display_block = "<h2>The Vehicle Listing file has been created.</h2>";
    $display_block .= "<p style='text-align:center;font-size:2em;'><a href='ViewVehicleJSONFile.php'>View Vehicle List</a><br>";
    $display_block .= "<a href='addressBookMenu.html'>Main Menu</a></p>";
}
else
{
    //If no records were found display an error message to the user.
    $display_block = "<h2>No vehicle information was found.</h2>";
    $display_block .= "<p style='text-align:center;font-size:2em;'><a href='addressBookMenu.html'>Main Menu</a></p>";
}






?>

<!DOCTYPE html>
<html>
    <head>
        <title>Create Vehicle Listing (JSON)</title>
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