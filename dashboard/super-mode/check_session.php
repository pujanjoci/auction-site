<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../../login.html");
    exit;
}

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    $response = array('loggedin' => true);
} else {
    $response = array('loggedin' => false);
}

// Send the response as JSON
header('Content-Type: application/json');
echo json_encode($response);

// User is still stored in the session, retrieve username and user ID
$username = $_SESSION["username"];
$user_id = $_SESSION["user_id"];

// Output the username and user ID
echo "$username with $user_id is still in session";

?>