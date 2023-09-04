<?php
$targetDirectory = 'D:/Code/Xampp/htdocs/auction-test/dashboard/location.php';

// Get the current script's location
$currentLocation = __DIR__;

// Check if the current location matches the target directory
if ($currentLocation !== $targetDirectory) {
    header("Location: ../login.html");
    exit;
} else {
    // If the location matches, display an alert with the current location
    echo "<script>alert('Current location is: " . $currentLocation . "');</script>";
}
