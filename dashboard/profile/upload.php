<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    // User is not logged in, redirect to login page
    header("Location: ../../login.html");
    exit;
}

// Get the session username
$username = $_SESSION["username"];

// Check if the file was uploaded without errors
if (isset($_FILES["image"]) && $_FILES["image"]["error"] == UPLOAD_ERR_OK) {
    $image_tmp = $_FILES["image"]["tmp_name"];

    // Set the destination directory
    $target_dir = "D:/Code/Xampp/htdocs/auction-test/dashboard/images/";
    // Generate a unique file name for the uploaded image
    $target_file = $target_dir . uniqid() . "_" . basename($_FILES["image"]["name"]);

    // Move the uploaded image to the target directory
    if (move_uploaded_file($image_tmp, $target_file)) {
        // Image uploaded successfully, update the profile image in the database
        include 'conifg.php'; // Include the database connection details

        // Query to update the profile table with the new image
        $update_sql = "UPDATE profile SET image = '$target_file' WHERE user_id = (SELECT id FROM users WHERE username = '$username')";
        $update_result = mysqli_query($con, $update_sql);

        if ($update_result) {
            // Image path updated in the database
            echo "Profile picture uploaded successfully.";
        } else {
            // Failed to update image path in the database
            echo "Failed to update profile picture.";
        }

        // Close the database connection
        mysqli_close($con);
    } else {
        // Failed to move the uploaded image to the target directory
        echo "Failed to upload profile picture.";
    }
} else {
    // No file was uploaded or an error occurred
    echo "Invalid file upload.";
}

// Redirect back to profile.php after 5 seconds
echo '<script>
    setTimeout(function() {
        window.location.href = "profile.php";
    }, 5000);
</script>';
