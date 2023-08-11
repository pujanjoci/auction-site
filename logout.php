<?php
session_start(); // Start the session

// Check if the user is logged in
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    // Unset all session variables
    session_unset();

    // Destroy the session
    session_destroy();
}

// Redirect to the login page
header("Location: login.html");
exit;
?>