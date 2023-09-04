<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $digit1 = $_POST["digit1"];
    $digit2 = $_POST["digit2"];
    $digit3 = $_POST["digit3"];
    $digit4 = $_POST["digit4"];
    $digit5 = $_POST["digit5"];
    $digit6 = $_POST["digit6"];

    // User submitted a verification code

    // Combine the digits to form the 6-digit code
    $verificationCode = $digit1 . $digit2 . $digit3 . $digit4 . $digit5 . $digit6;

    $email = $_POST['email'];

    // Check if the provided verification code matches the one in the database and is still valid within 24 hours
    $sql = "SELECT * FROM verify WHERE email='$email' AND verification_code='$verificationCode' AND created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)";
    $result = $con->query($sql);

    if ($result->num_rows > 0) {
        // Verification successful

        // Join the 'verify' table and 'registration' table based on email
        $joinSql = "INSERT INTO users (username, password, email)
                    SELECT r.username, r.password, r.email
                    FROM registration AS r
                    INNER JOIN verify AS v ON r.email = v.email
                    WHERE r.email='$email' AND v.verification_code='$verificationCode'";

        if ($con->query($joinSql)) {
            // Mark the email as verified in the 'users' table (optional)
            $updateSql = "UPDATE users SET verified='true' WHERE email='$email'";
            $con->query($updateSql);

            // Delete the rows from 'verify' and 'registration' tables
            $deleteSql = "DELETE FROM verify WHERE email='$email'";
            $con->query($deleteSql);

            $deleteSql = "DELETE FROM registration WHERE email='$email'";
            $con->query($deleteSql);

            // Redirect to login.html
            header('Location: login.html');
            exit; // Stop script execution
        } else {
            echo 'Error while inserting user data. Please try again.';
        }
    } else {
        // Verification failed
        echo 'Invalid verification code or code has expired. Please try again.';
    }
}
