<?php
session_start();

// Define active menu item
$homeActive = 'class="active"'; // Home menu item is active
$courseSelection = "";
$currentRegistration = "";
$logIn = "";

// Define menu Log In / Log Out title
if(isset($_SESSION["validation"]))
{
    $logStatus ="Log Out";
} else {
    $logStatus ="Log In";
}


include ("./Common/Header.php");


?>

<div class="container">
    <h1>Welcome to Online Registration </h1>
        <div class="row">
            <p class="col-sm-6">If you have never used this before, you have to <a href="NewUser.php">sign up</a> first.</p>
        </div>
        <div class="row">
            <p class="col-sm-6">If you have already signed up, you can <a href="Login.php">login</a> now.</p>
        </div>
</div>
<?php include ("./Common/Footer.php"); ?>