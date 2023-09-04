<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include the database connection details
    include 'config.php';

    // Collect the form data and sanitize input
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $hashedPassword = md5($password);

    // Prepare a SQL statement to retrieve user information
    $sql = "SELECT id, role FROM users WHERE username=? AND password=?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ss", $username, $hashedPassword);

    if ($stmt->execute()) {
        $stmt->bind_result($user_id, $role);

        if ($stmt->fetch()) {
            // Password is correct, set session variables
            $_SESSION["loggedin"] = true;
            $_SESSION["username"] = $username;
            $_SESSION["id"] = $user_id;

            // Close the statement
            $stmt->close();

            // Include userchecker.php code here
            function checkUserRoleAndRedirect($role, $con)
            {
                // Check if the user's role matches the expected roles
                switch ($role) {
                    case "admin":
                        header("Location: dashboard/admin/admin.php?user_id=" . $_SESSION["id"]);
                        exit;
                    case "staff":
                        header("Location: dashboard/staff/staff.php?user_id=" . $_SESSION["id"]);
                        exit;
                    case "user":
                        header("Location: dashboard/user/user-mode.php?user_id=" . $_SESSION["id"]);
                        exit;
                    default:
                        $error = "Invalid role or database error.";
                        error_log($error); // Log the error
                        echo '<script>alert("' . $error . '"); window.location.href = "login.html";</script>';
                        exit();
                }
            }

            checkUserRoleAndRedirect($role, $con);
        } else {
            $error = "Invalid username or password";
            error_log($error); // Log the error
            echo '<script>alert("' . $error . '"); window.location.href = "login.html";</script>';
            exit();
        }
    } else {
        // Handle database query execution error here
        $error = "Database query error";
        error_log($error); // Log the error
        echo '<script>alert("' . $error . '"); window.location.href = "login.html";</script>';
        exit();
    }
}
