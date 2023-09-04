<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: ../../login.html");
    exit;
}

// Include the config.php file for database connection
require_once "config.php";

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

// Fetch data from the table in the database
$sql = "SELECT * FROM items";
$result = $con->query($sql);

$data = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Dashboard</title>
    <link rel="stylesheet" type="text/css" href="admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-w5Z4jf4Gf6BJrRmcJyIOhwG8fg9ZbeixBr+9g2eOjOqsxgEMf8P+vnjaF/wmzSsbY+2LisZ8u+M6DlX3TCIS0A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
</head>

<body>
    <?php include "smenu.php"; ?>

    <?php include "details.php"; ?>

    <?php include "../powers/process_expired_items.php"; ?>

    <?php include "../powers/process_sold.php"; ?>

    <script>
        setInterval(function() {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    console.log("Response content:", this.responseText);
                    var response = JSON.parse(this.responseText);

                    if (!response.loggedin) {
                        window.location.href = "../../login.html";
                    }
                }
            };
            xhttp.open("GET", "check_session.php", true);
            xhttp.send();
        }, 5000);

        var xhttpSecond = new XMLHttpRequest();
        xhttpSecond.onreadystatechange = function() {
            if (xhttpSecond.readyState === 4) { // Only proceed if the request is complete
                if (xhttpSecond.status === 200) { // Check if the response status is OK
                    var responseJSON = xhttpSecond.responseText;

                    try {
                        var parsedData = JSON.parse(responseJSON); // Attempt to parse the JSON

                        // Successfully parsed the JSON, you can use parsedData here

                    } catch (error) {
                        console.error("Error parsing JSON:", error);
                        // Handle the error appropriately, e.g., display an error message to the user
                    }

                } else {
                    console.error("HTTP Request Error:", xhttpSecond.status);
                    // Handle the error appropriately, e.g., display an error message to the user
                }
            }
        };
        setTimeout(function() {
            location.reload();
        }, 30000);
    </script>
</body>

</html>