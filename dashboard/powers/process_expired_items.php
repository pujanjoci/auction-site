<?php
include 'config.php';

// Step 1: Fetch items that have expired and are not in the biddingincrement table
$currentTimestamp = time(); // Current timestamp
$query = "SELECT * FROM items WHERE end_time <= $currentTimestamp AND id NOT IN (SELECT item_id FROM biddingincrement)";
$result = mysqli_query($con, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($con));
}

// Step 2: Loop through the results and insert into expired_table
while ($row = mysqli_fetch_assoc($result)) {
    $name = mysqli_real_escape_string($con, $row['name']);
    $details = mysqli_real_escape_string($con, $row['details']);
    $image = mysqli_real_escape_string($con, $row['image']);

    $insertQuery = "INSERT INTO expired_table (name, details, image) VALUES ('$name', '$details', '$image')";
    $insertResult = mysqli_query($con, $insertQuery);

    if (!$insertResult) {
        die("Insertion failed: " . mysqli_error($con));
    }

    // Step 3: Remove data from items table
    $itemId = $row['id'];
    $deleteQuery = "DELETE FROM items WHERE id = $itemId";
    $deleteResult = mysqli_query($con, $deleteQuery);

    if (!$deleteResult) {
        die("Deletion failed: " . mysqli_error($con));
    }
}
