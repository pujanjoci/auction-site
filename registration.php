<?php
include 'config.php';
require 'vendor/autoload.php'; // Include PHPMailer

use PHPMailer\PHPMailer\PHPMailer; // Add this line to import PHPMailer

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = filter_var($_POST['username']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = md5($_POST['password']);

    // Check if the email or username already exists in the users table
    $checkUserQuery = "SELECT username, email FROM users WHERE username = ? OR email = ?";
    $checkUserStmt = $con->prepare($checkUserQuery);
    $checkUserStmt->bind_param("ss", $username, $email);
    $checkUserStmt->execute();
    $checkUserStmt->store_result();

    if ($checkUserStmt->num_rows > 0) {
        header('Location: register.php?error=useremailerror');
        exit;
    }

    $checkEmailQuery = "SELECT email FROM registration WHERE email = ?";
    $checkEmailStmt = $con->prepare($checkEmailQuery);
    $checkEmailStmt->bind_param("s", $email);
    $checkEmailStmt->execute();
    $checkEmailStmt->store_result();

    if (
        $checkEmailStmt->num_rows > 0
    ) {
        header('Location: register.php?error=registrationerror');
        exit;
    }


    // Generate a random 6-digit verification code
    $verificationCode = mt_rand(100000, 999999);
    $currentDateTime = date('Y-m-d H:i:s');

    // Insert into the registration table
    $insertRegistrationQuery = "INSERT INTO registration (username, password, email) VALUES (?, ?, ?)";
    $insertRegistrationStmt = $con->prepare($insertRegistrationQuery);
    $insertRegistrationStmt->bind_param("sss", $username, $password, $email);

    if ($insertRegistrationStmt->execute()) {
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
                $mail->Username = 'Your@mail.com';
                $mail->Password = 'Your_App_Password';
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
            echo 'Error inserting verification code into the verify table';
        }
    } else {
        echo 'Error inserting registration data into the registration table';
    }

    // Close the statements
    $insertRegistrationStmt->close();
    $insertVerifyStmt->close();
    $checkUserStmt->close();
}
