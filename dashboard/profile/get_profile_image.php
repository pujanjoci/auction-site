<?php

// Check if the user is logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: ../../login.html");
    exit;
}

// Include the database connection details
include 'conifg.php';

// Function to get the profile image path for a given user ID
function getProfileImage($userId, $imageDirectory, $defaultImage, $con)
{
    // Prepare the query with a placeholder for the user ID
    $query = "SELECT image FROM profile WHERE user_id = ?";
    
    // Create a prepared statement
    $stmt = mysqli_prepare($con, $query);
    
    // Bind the user ID parameter to the prepared statement
    mysqli_stmt_bind_param($stmt, "i", $userId);
    
    // Execute the prepared statement
    mysqli_stmt_execute($stmt);
    
    // Get the result set from the prepared statement
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        // User has uploaded a profile image
        $row = mysqli_fetch_assoc($result);
        $profileImage = $imageDirectory . $row['image'];
    } else {
        // User has not uploaded a profile image, use the default image
        $profileImage = $imageDirectory . $defaultImage;
    }

    // Close the prepared statement
    mysqli_stmt_close($stmt);

    // Return the profile image path
    return $profileImage;
}

// Get the user's ID
$userId = $_SESSION["user_id"];

// Set the path to the directory where the images are stored
$imageDirectory = "D:/Code/Xampp/htdocs/auction-test/dashboard/images/";

// Set the default profile image filename in case the user does not have a custom image
$defaultImage = "default_profile.png";

// Call the function to retrieve the profile image path
$profilePicture = getProfileImage($userId, $imageDirectory, $defaultImage, $con);

// Close the database connection
mysqli_close($con);

// Return the profile image path as a response
echo $profilePicture;
