<?php
include('config.php');

// Attempt to establish a database connection
$con = mysqli_connect($hostname, $username, $password, $database);

// Check if the connection was successful
if (!$con) {
    die("Failed to connect to the database: " . mysqli_connect_error());
}

$message = ""; // Initialize the message variable

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the username or email already exists
    $checkExistingQuery = "SELECT * FROM users WHERE username = ? OR email = ?";
    $stmt = mysqli_prepare($con, $checkExistingQuery);
    mysqli_stmt_bind_param($stmt, "ss", $username, $email);
    mysqli_stmt_execute($stmt);
    $checkExistingResult = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($checkExistingResult) > 0) {
        $message = "Username or email already exists. Please choose a different username or email.";
    } else {
        // Hash the password using MD5
        $hashedPassword = md5($password);

        // Insert the user into the database
        $insertQuery = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($con, $insertQuery);
        mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hashedPassword);
        mysqli_stmt_execute($stmt);

        $message = "Registration successful! Please proceed to <a href='login.html'>login</a>.";

        // Redirect to login.html after successful registration
        header("Location: login.html");
        exit;
    }
}
