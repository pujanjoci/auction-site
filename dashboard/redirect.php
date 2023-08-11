<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: ../../login.html");
    exit;
}

include 'config.php';
include 'userchecker.php';

// Get the session username
$username = $_SESSION["username"];

// Prepare and execute the query
$query = "SELECT id FROM users WHERE username = ?";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);

// Retrieve the result
mysqli_stmt_bind_result($stmt, $user_id);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

// Store the user_id in the session
$_SESSION['user_id'] = $user_id;

// Retrieve the user's role from the database based on their user ID
$userId = $_SESSION['user_id'] ?? null; // Assuming you have a user ID stored in a session variable
if ($userId) {
    // Prepare and execute a SQL statement to retrieve the user's role
    $sql = "SELECT role FROM users WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $userId);
    $stmt->execute();
    $stmt->bind_result($role);

    // Check if the query returned a result
    if ($stmt->fetch()) {
        // User's role found, redirect based on the role
        if (checkUserRoleAndRedirect($role, $con)) {
            // Redirect user to appropriate page based on their role
            switch ($role) {
                case "super":
                    header("Location: dashboard/super-mode/super-mode.php");
                    exit;
                case "admin":
                    header("Location: dashboard/admin/admin.php");
                    exit;
                case "manager":
                    header("Location: dashboard/manager/manager.php");
                    exit;
                case "staff":
                    header("Location: dashboard/staff/staff.php");
                    exit;
                case "user":
                    header("Location: dashboard/user/user-mode.php");
                    exit;
                default:
                    // Handle unknown roles here
                    break;
            }
        }
    } else {
        // User's role not found, handle the error appropriately
        // For example, redirect to an error page or display an error message
        echo "Error: User role not found.";
    }

    // Close the statement
    $stmt->close();
} else {
    // User ID not found, handle the error appropriately
    // For example, redirect to an error page or display an error message
    echo "Error: User ID not found.";
}
