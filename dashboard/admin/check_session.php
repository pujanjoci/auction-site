<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../../login.html");
    exit;
}

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    $response = array(
        'loggedin' => true,
        'message' => $_SESSION["username"] . " with " . $_SESSION["user_id"] . " is still in session"
    );
} else {
    $response = array('loggedin' => false);
}

// Send the response as JSON
header('Content-Type: application/json');
echo json_encode($response);
