<div class="side-panel">
    <div class="logo-container">
        <div class="logo-circle"></div>
        <h4>Rachit Auction</h4>
    </div>
    <div class="side-panel-box">
        <h3><i class="fas fa-tachometer-alt"></i><a href="../user/user-mode.php">Dashboard</a></h3>
    </div>
    <div class="side-panel-box">
        <h3><i class="fas fa-envelope"></i><a href="contact.php">Contact Us</a></h3>
    </div>
    <div class="user-box">
        <?php
        if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
            $username = $_SESSION["username"];

            // Include the database connection details
            include 'config.php'; // Make sure this file contains your database connection settings

            // Prepare and execute a SQL query to fetch the email based on the username
            $sql = "SELECT email FROM users WHERE username='$username'";
            $result = mysqli_query($con, $sql);

            if ($result) {
                $row = mysqli_fetch_assoc($result);
                $email = $row['email'];
                echo "<h3>$username ($email)</h3>";
            } else {
                // Handle the database error here
                $error = "Database error: " . mysqli_error($con);
                error_log($error); // Log the error
                echo '<p>An error occurred while fetching the email. Please try again later.</p>';
            }
        }
        ?>

    </div>
    <div class="bottom-box">
        <h3><a href="../../logout.html">Logout</a><i class="fas fa-sign-out-alt"></i></h3>
    </div>
</div>


<link rel="stylesheet" type="text/css" href="menu.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-w5Z4jf4Gf6BJrRmcJyIOhwG8fg9ZbeixBr+9g2eOjOqsxgEMf8P+vnjaF/wmzSsbY+2LisZ8u+M6DlX3TCIS0A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />