<?php
// Include the database connection from config.php
require_once('config.php');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize user inputs
    $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if the username or email already exists in the database
    $query = "SELECT * FROM users WHERE username = ? OR email = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User or email already exists, show an alert and redirect to register.html
        echo '<script>alert("Email or Username already exists");</script>';
        echo '<script>window.location.href = "register.html";</script>';
    } else {
        // Insert the user values into the database
        $insertQuery = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $insertStmt = $con->prepare($insertQuery);
        $insertStmt->bind_param("sss", $username, $email, $password);

        if ($insertStmt->execute()) {
            // Registration successful, redirect to login.html
            echo '<script>window.location.href = "login.html";</script>';
        } else {
            // Error occurred while inserting, redirect to register.html
            echo '<script>alert("An error occurred while registering.");</script>';
            echo '<script>window.location.href = "register.html";</script>';
        }
    }

    // Close the statement
    $stmt->close();
}
