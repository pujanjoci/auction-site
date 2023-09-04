<?php
session_start();

// Check if the user is logged in
if (isset($_SESSION['username'])) {
    // User is logged in

    // Retrieve the username from the session
    $username = $_SESSION['username'];

    // Perform a database query or use any other data source
    // to fetch the user_id based on the username
    $user_id = getUserIDFromUsername($username);

    // Check if user_id is found
    if ($user_id !== null) {
        // Use the user_id as needed
        echo "Welcome, user ID: " . $user_id;
    } else {
        echo "Error: Unable to retrieve user ID.";
    }
} else {
    // User is not logged in
    echo "Please log in.";
}

// Function to retrieve the user ID from the database
function getUserIDFromUsername($username)
{
        // Establish a database connection
        $host = "localhost";
        $db = "project";
        $user = "root";
        $password = "";

        $conn = mysqli_connect($host, $user, $password, $db);
        if (!$conn) {
            die("Database connection failed: " . mysqli_connect_error());
        }

        // Prepare and execute the query
        $query = "SELECT id FROM users WHERE username = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);

        // Retrieve the result
        mysqli_stmt_bind_result($stmt, $user_id);
        mysqli_stmt_fetch($stmt);

        // Close the statement and connection
        mysqli_stmt_close($stmt);
        mysqli_close($conn);

        return $user_id;
}

?>
