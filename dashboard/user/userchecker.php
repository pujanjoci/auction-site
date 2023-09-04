<?php

// Function to check user role and redirect accordingly
function checkUserRoleAndRedirect($role, $con)
{
    $sanitizedRole = mysqli_real_escape_string($con, $role);

    $query = "SELECT role FROM users WHERE role = '$sanitizedRole'";
    $result = mysqli_query($con, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        // Redirect the user based on their role
        switch ($sanitizedRole) {
            case "admin":
                header("Location: dashboard/admin/admin.php");
                exit;
            case "staff":
                header("Location: dashboard/staff/staff.php");
                exit;
            case "user":
                header("Location: dashboard/user/user-mode.php");
                exit;
            default:
                break;
        }
    } else {
        echo "Invalid role or database error.";
        return false;
    }
}
