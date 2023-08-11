<?php

// Function to check user role and redirect accordingly
function checkUserRoleAndRedirect($role, $con)
{
    // Sanitize the role input to prevent SQL injection
    $sanitizedRole = mysqli_real_escape_string($con, $role);

    // Query to fetch user role from the users table
    $query = "SELECT role FROM users WHERE role = '$sanitizedRole'";
    $result = mysqli_query($con, $query);

    // Check if the query was successful
    if ($result && mysqli_num_rows($result) > 0) {
        // Redirect the user based on their role
        switch ($sanitizedRole) {
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
    } else {
        // Handle the case if the query fails or role not found
        echo "Invalid role or database error.";
        return false;
    }
}
