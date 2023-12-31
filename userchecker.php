<?php
function checkUserRoleAndRedirect($role, $con)
{
    $sanitizedRole = mysqli_real_escape_string($con, $role);

    // Prepare a SQL statement to check the role
    $query = "SELECT role FROM users WHERE role = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $sanitizedRole);
    $stmt->execute();
    $stmt->store_result();

    // Check if the query was successful
    if ($stmt->num_rows > 0) {
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

    // Close the statement
    $stmt->close();
}
