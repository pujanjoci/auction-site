<?php
// Start the session
session_start();

// Check if the item_id is provided in the URL
if (isset($_GET['item_id'])) {
    $itemId = $_GET['item_id'];

    // Display the item_id
    echo "Item ID: " . $itemId . "<br>";
} else {
    echo "No item ID provided in the URL.<br>";
}

// Check if the user_id is stored in the session
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    // Display the user_id
    echo "User ID: " . $userId;
} else {
    echo "No user ID stored in the session.";
}
