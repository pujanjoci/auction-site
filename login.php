<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include the database connection details
    include 'config.php';

    // Include the userchecker.php file
    include 'userchecker.php';

    // Collect the form data
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Hash the user's input password using MD5
    $hashedPassword = md5($password);

    // Prepare the SQL statement to check if the user exists
    $sql = "SELECT * FROM users WHERE username='$username' AND password='$hashedPassword'";
    $result = mysqli_query($con, $sql);

    if (!$result) {
        // Error occurred while executing the SQL query
        $error = "Database error: " . mysqli_error($con);
        error_log($error); // Log the error
        echo '<script>alert("An error occurred. Please try again later."); window.location.href = "login.html";</script>';
        exit();
    }

    // Check if the user exists in the database
    $count = mysqli_num_rows($result);
    if ($count == 1) {
        // User is registered, create a session and redirect to the dashboard
        $_SESSION["loggedin"] = true;
        $_SESSION["username"] = $username;



        // Fetch the user's role from the database
        $row = mysqli_fetch_assoc($result);
        $role = $row['role'];

        // Call the function from the userchecker.php file to check user role and redirect accordingly
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
        } else {
            // User does not have the required role, display an error message
            $error = "You do not have permission to access this page";
            error_log($error); // Log the error
            echo '<script>alert("' . $error . '"); window.location.href = "login.html";</script>';
            exit();
        }
    } else {
        // User does not exist or password is incorrect, display an error message
        $error = "Invalid username or password";
        error_log($error); // Log the error
        echo '<script>alert("' . $error . '"); window.location.href = "login.html";</script>';
        exit();
    }

    // Close the database connection
    mysqli_close($con);
}
?>
```