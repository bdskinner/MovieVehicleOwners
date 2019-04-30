<?php

//Include Files.
include 'ConnectToDB.php';

//check for required fields from the form
if (($_POST['username']=="") || ($_POST['password']=="")) {
    header("Location: userlogin.html");
    exit;
}

$display_block="";

//connect to server and select database
doDB();

//use mysqli_real_escape_string to clean the input
$safe_username = mysqli_real_escape_string($mysqli, $_POST['username']);
$safe_password = mysqli_real_escape_string($mysqli, $_POST['password']);

//create and issue the query
$sql = "SELECT f_name, l_name FROM auth_users WHERE username = '".$safe_username."' AND password = '".$safe_password."'";

$result = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));

//get the number of rows in the result set; should be 1 if a match
if (mysqli_num_rows($result) == 1) 
{
    header("Location: addressBookMenu.html");
	exit;
} 
else 
{
    //If the user ID or password was invalid redirect back to login form.
    $display_block = "<p style='text-align:center;color:red;font-size:2em;'>Please contact IT, your username and password are not valid</p>";
    $display_block .= "<p style='text-align:center;font-size:2em;'><a href='Login.html'> Return to login</a></p>";
}

//close connection to MySQL
mysqli_close($mysqli);
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Limelight Login Page</title>
        <link href="css/MovieVehicles.css" rel="stylesheet" type="text/css">
    </head>
    <body>
        <br><br>
        <div class="center">
            <br><br>
            <?php echo $display_block; ?>
            <br><br>
        </div>
    </body>
</html>
