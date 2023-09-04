<?php
include 'config.php';
require 'vendor/autoload.php'; // Include PHPMailer

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize user inputs
    $username = filter_var($_POST['username']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = md5($_POST['password']);

    // Generate a random 6-digit verification code
    $verificationCode = mt_rand(100000, 999999);

    // Insert the verification code into the database
    $insertCodeQuery = "INSERT INTO verify (username, email, password, verification_code) VALUES (?, ?, ?, ?)";
    $insertCodeStmt = $con->prepare($insertCodeQuery);
    $insertCodeStmt->bind_param("ssss", $username, $email, $password, $verificationCode);

    if ($insertCodeStmt->execute()) {
        // Create a PHPMailer instance
        $mail = new PHPMailer(true);

        // SMTP configuration for Gmail
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'rachitauction@gmail.com';
        $mail->Password = '@Pujan Joshi';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;


        // Email content
        $mail->setFrom('rachitauction@gmail.com', 'Rachit Auction');
        $mail->addAddress($email);
        $mail->Subject = 'Verification Code';
        $mail->Body = "Your verification code is: $verificationCode";

        // Send the email
        if ($mail->send()) {
            echo 'Verification code sent successfully!';
        } else {
            echo 'Error sending email: ' . $mail->ErrorInfo;
        }
    } else {
        echo 'Error inserting verification code into the database';
    }

    // Close the statement
    $insertCodeStmt->close();
}
