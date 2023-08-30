<?php
include('config.php');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Display a message to indicate that PHP is running
echo "<p>PHP is running!</p>";

// Attempt to establish a database connection
try {
    $con = mysqli_connect($hostname, $username, $password, $database);

    // Check if the connection was successful
    if (!$con) {
        throw new Exception("Failed to connect to the database: " . mysqli_connect_error());
    } else {
        echo "<p>Database connected successfully!</p>";

        // Retrieve and display data from the user table (for testing purposes)
        $query = "SELECT * FROM users";
        $result = mysqli_query($con, $query);

        if ($result) {
            echo "<p>User table data:</p>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "Username: " . $row['username'] . ", Email: " . $row['email'] . "<br>";
            }
        } else {
            echo "<p>Error retrieving user data: " . mysqli_error($con) . "</p>";
        }
    }

    if (isset($_POST['submit'])) {
        // Your existing code for form submission and database interaction
        // ...
    }
} catch (Exception $e) {
    echo "An error occurred: " . $e->getMessage();
}
