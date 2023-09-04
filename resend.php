<?php
include 'config.php';
require 'vendor/autoload.php'; // Include PHPMailer

use PHPMailer\PHPMailer\PHPMailer; // Add this line to import PHPMailer

// Check if the email parameter is present in the URL
if (isset($_GET['email'])) {
    // Retrieve the email address from the URL and decode it
    $email = urldecode($_GET['email']);

    // Check if a verification code has already been sent for this email
    $checkVerificationQuery = "SELECT verification_code FROM verify WHERE email = ?";
    $checkVerificationStmt = $con->prepare($checkVerificationQuery);
    $checkVerificationStmt->bind_param("s", $email);
    $checkVerificationStmt->execute();
    $checkVerificationStmt->store_result();

    if ($checkVerificationStmt->num_rows > 0) {
        // Verification code already sent
        header('Location: verifyform.php?email=' . urlencode($email));
        exit;
    }

    // Generate a new random 6-digit verification code
    $verificationCode = mt_rand(100000, 999999);

    // Get the current date and time in the desired format (e.g., YYYY-MM-DD HH:MM:SS)
    $currentDateTime = date('Y-m-d H:i:s');

    // Insert the verification code and created_at into the verify table
    $insertVerifyQuery = "INSERT INTO verify (email, verification_code, created_at) VALUES (?, ?, ?)";
    $insertVerifyStmt = $con->prepare($insertVerifyQuery);
    $insertVerifyStmt->bind_param("sis", $email, $verificationCode, $currentDateTime);

    if ($insertVerifyStmt->execute()) {
        try {
            // Create a PHPMailer instance
            $mail = new PHPMailer(true);

            // SMTP configuration for Gmail
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'rachitauction@gmail.com';
            $mail->Password = 'yogmyaihjmimbcfb';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            // Email content
            $mail->setFrom('rachitauction@gmail.com', 'Rachit Auction');
            $mail->addAddress($email);
            $mail->Subject = 'Verification Code';
            $mail->Body = "Your verification code is: $verificationCode";

            // Send the email
            if ($mail->send()) {
                // Redirect to verifyform.php with the email as a query parameter
                header('Location: verifyform.php?email=' . urlencode($email));
                exit;
            } else {
                echo 'Error sending email: ' . $mail->ErrorInfo;
            }
        } catch (Exception $e) {
            echo 'Error creating PHPMailer instance: ' . $e->getMessage();
        }
    } else {
        echo 'Error inserting verification code into the database.';
    }

    // Close the statements
    $checkVerificationStmt->close();
    $insertVerifyStmt->close();
} else {
    echo 'Email parameter missing in the URL.';
}
