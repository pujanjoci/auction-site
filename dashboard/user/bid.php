<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect the user to the login page or handle the case when the user is not logged in
    // ...
}

// Include the configuration file
require_once 'config.php';

// Retrieve the user ID from the session
$user_id = $_SESSION['user_id'];

// Retrieve the item ID from the URL
$item_id = $_GET['item_id'];

// Retrieve the bid amount from the form submission
$bid_amount = $_POST['bid_amount'];

// Validate the bid amount
$queryItemPrice = "SELECT starting_price FROM items WHERE id = ?";
$stmtItemPrice = $con->prepare($queryItemPrice);
$stmtItemPrice->bind_param("i", $item_id);
$stmtItemPrice->execute();
$resultItemPrice = $stmtItemPrice->get_result();

if ($resultItemPrice->num_rows > 0) {
    $rowItemPrice = $resultItemPrice->fetch_assoc();
    $starting_price = $rowItemPrice['starting_price'];

    if ($bid_amount <= $starting_price) {
        // Handle invalid bid amount (lower or equal to the starting price), such as showing an error message
        $error_message = "Bid amount should be higher than the starting price.";
        // Redirect back to item details page with error message
        echo "<script>alert('$error_message'); window.location.href = 'process.php?item_id=$item_id';</script>";
        exit();
    }
}

$queryMaxIncrement = "SELECT MAX(increment_value) AS max_increment FROM biddingincrement WHERE item_id = ?";
$stmtMaxIncrement = $con->prepare($queryMaxIncrement);
$stmtMaxIncrement->bind_param("i", $item_id);
$stmtMaxIncrement->execute();
$resultMaxIncrement = $stmtMaxIncrement->get_result();

if ($resultMaxIncrement->num_rows > 0) {
    $rowMaxIncrement = $resultMaxIncrement->fetch_assoc();
    $max_increment = $rowMaxIncrement['max_increment'];

    if ($bid_amount <= $max_increment) {
        // Handle invalid bid amount (lower or equal to the highest increment value), such as showing an alert message
        $error_message = "Bid amount should be higher than the highest increment value.";
        echo "<script>alert('$error_message'); window.location.href = 'process.php?item_id=$item_id';</script>";
        exit();
    }

}

// Prepare the query to insert the bid into the biddingincrement table
$query = "
    INSERT INTO biddingincrement (increment_value, created_at, user_id, item_id)
    VALUES (?, NOW(), ?, ?)
";

// Prepare the statement
$stmt = $con->prepare($query);

// Bind the parameters
$stmt->bind_param("dii", $bid_amount, $user_id, $item_id);

// Execute the statement
if ($stmt->execute()) {
    // Bid successfully inserted
    // Redirect to progress.php with the item_id in the URL
    header("Location: process.php?item_id=$item_id");
    exit();
} else {
    // Failed to insert the bid
    $message = "Failed to insert the bid. Please try again.";
    header("Location: process.php?item_id=$item_id&message=" . urlencode($message));
    exit();
}

// Close the statements
$stmtItemPrice->close();
$stmtMaxIncrement->close();
$stmt->close();

// Close the database connection
$con->close();
