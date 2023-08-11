<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    // User is not logged in, redirect to login page
    header("Location: ../../login.html");
    exit;
}

// Get the session username
$username = $_SESSION["username"];

// Retrieve the profile image from the database
include 'conifg.php'; // Include the database connection details

// Include the get_profile_image.php file
include '../profile/get_profile_image.php';

// Query the profile table based on the user ID
$sql = "SELECT image FROM profile WHERE user_id = (SELECT id FROM users WHERE username = '$username')";
$result = mysqli_query($con, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    // Fetch the profile image
    $row = mysqli_fetch_assoc($result);
    $image = $row['image'];
} else {
    // Default profile image if no image is found
    $image = "dashboard/images/avatar1.png";
}

// Retrieve the profile image for the user
$profilePicture = getProfileImage($userId, $imageDirectory, $defaultImage, $con);

// Close the database connection
mysqli_close($con);

?>

<!DOCTYPE html>
<html>

<head>
    <title>Side Panel and Navbar Example</title>
    <link rel="stylesheet" type="text/css" href="user.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-w5Z4jf4Gf6BJrRmcJyIOhwG8fg9ZbeixBr+9g2eOjOqsxgEMf8P+vnjaF/wmzSsbY+2LisZ8u+M6DlX3TCIS0A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
</head>

<body>
    <div class="side-panel">

        <div class="profile-container">
            <div class="profile-circle">
                <a href="../profile/profile.php">
                    <img src="../profile/get_profile_image.php" alt="Profile Picture">
                </a>
            </div>
            <h4><?php echo $_SESSION["username"]; ?></h4>
        </div>

        <div class="side-panel-box">
            <h3><i class="fas fa-tachometer-alt"></i><a href="dashboard.html">Dashboard</a></h3>
        </div>
        <div class="side-panel-box">
            <h3><i class="fas fa-envelope"></i><a href="contact.html">Contact Us</a></h3>
        </div>
        <div class="side-panel-box">
            <h3><i class="fas fa-check-circle"></i><a href="sold.html">Sold Items</a></h3>
        </div>
        <div class="side-panel-box">
            <h3><i class="fas fa-hourglass-end"></i><a href="expired.html">Expired Items</a></h3>
        </div>
        <div class="side-panel-box">
            <h3><i class="fas fa-cog"></i><a href="settings.html">Settings</a></h3>
        </div>
        <div class="bottom-box">
            <h3><a href="../../logout.html">Logout</a><i class="fas fa-sign-out-alt"></i></h3>
        </div>
    </div>

    <div class="navbar">
        <div class="search-box">
            <input type="text" placeholder="Search...">
            <i class="fas fa-search"></i>
        </div>
    </div>

    <div class="content">
        <h1>Welcome, <?php echo $username; ?>!</h1>
        <div class="profile-image">
            <img src="<?php echo $image; ?>" alt="Profile Picture">
        </div>

        <!-- Change the directory path to store the uploaded images -->
        <form action="upload.php" method="POST" enctype="multipart/form-data">
            <label for="image">Upload Profile Picture:</label>
            <input type="file" name="image" id="image">
            <input type="submit" value="Upload">
        </form>
    </div>

    <script>
        // Check if the session is active using AJAX
        setInterval(function() {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var response = JSON.parse(this.responseText);
                    if (!response.loggedin) {
                        window.location.href = "../../login.html";
                    }
                }
            };
            xhttp.open("GET", "check_session.php", true);
            xhttp.send();
        }, 5000);
    </script>
</body>

</html>