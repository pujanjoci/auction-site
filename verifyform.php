<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verification Code</title>
    <style>
        /* Center the form and its elements */
        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            /* Adjust the height as needed */
            text-align: center;
        }

        /* Style the verification code input fields */
        .verification-code input {
            margin: 5px;
            padding: 10px;
            width: 40px;
            text-align: center;
        }

        /* Style the email input field */
        .verification-code input[type="email"] {
            margin-bottom: 10px;
        }

        /* Style the Verify Code button */
        button[type="submit"] {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }

        input[type="text"] {
            width: 30px;
            text-align: center;
        }
    </style>
</head>

<body>
    <form action="verify.php" method="post">
        <div class="verification-code">
            <input type="email" name="email" placeholder="Email" required></br>
            <input type="text" name="digit1" maxlength="1" pattern="[0-9]" required>
            <input type="text" name="digit2" maxlength="1" pattern="[0-9]" required>
            <input type="text" name="digit3" maxlength="1" pattern="[0-9]" required>
            <input type="text" name="digit4" maxlength="1" pattern="[0-9]" required>
            <input type="text" name="digit5" maxlength="1" pattern="[0-9]" required>
            <input type="text" name="digit6" maxlength="1" pattern="[0-9]" required>
        </div>
        <button type="submit">Verify Code</button>
    </form>


    <!-- Add Re-send code button -->
    <form action="resend.php" method="post">
        <button type="submit" name="resend_code">Re-send code</button>
    </form>
</body>

</html>