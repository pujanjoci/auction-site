<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: ../login.html");
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

function checkUserRoleAndIncludeMenu($role)
{
    // Check the user's role and include the appropriate menu file
    switch ($role) {
        case "admin":
            include "admin/menu.php";
            break;
        case "staff":
            include "staff/menu.php";
            break;
        case "user":
            include "user/menu.php";
            break;
        default:
            // Handle invalid or unknown roles
            return false;
    }
}

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
        // User's role found, include the menu based on the role
        checkUserRoleAndIncludeMenu($role);
    } else {
        // User ID not found, handle the error appropriately
        // For example, redirect to an error page or display an error message
        echo "Error: User ID not found.";
    }

    // Close the statement
    $stmt->close();
} else {
    // User ID not found, handle the error appropriately
    // For example, redirect to an error page or display an error message
    echo "Error: User ID not found.";
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $itemName = isset($_POST['name']) ? $_POST['name'] : '';
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    $image = isset($_FILES['image']['name']) ? $_FILES['image']['name'] : '';
    $startingPrice = isset($_POST['starting_price']) ? $_POST['starting_price'] : '';
    $sellerName = isset($_POST['seller']) ? $_POST['seller'] : '';
    $endTime = isset($_POST['end_time']) ? $_POST['end_time'] : '';

    // Perform any necessary validation on the form data

    // Check if required fields are not empty
    if (!empty($itemName) && !empty($sellerName)) {
        // Generate a unique filename based on timestamp
        $filename = time() . "_" . $_FILES['image']['name'];

        // Set the target folder path
        $folder = "images/" . $filename;

        // Move the uploaded file to the target folder
        move_uploaded_file($_FILES['image']['tmp_name'], $folder);

        // Prepare and execute the SQL query
        $sql = "INSERT INTO items (name, description, image, starting_price, seller, end_time, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $con->prepare($sql);
        $stmt->bind_param(
            "ssssss",
            $itemName,
            $description,
            $filename,
            $startingPrice,
            $sellerName,
            $endTime
        );

        if ($stmt->execute()) {
            // Item added successfully
            echo "<script>alert('Item added successfully.');</script>";
        } else {
            // Error occurred while adding item
            echo "<script>alert('Error: " . $stmt->error . "');</script>";
        }

        // Close the statement
        $stmt->close();
    } else {
        // Display error message if required fields are empty
        echo "<script>alert('Error: Please fill in all required fields.');</script>";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Add Items</title>
    <link rel="stylesheet" type="text/css" href="additems.css">
</head>

<body>
    <div class="content">
        <h2>Add Items</h2>
        <form method="POST" action="" enctype="multipart/form-data">
            <div class="form-group">
                <label for="item_name">Item Name</label>
                <input type="text" name="name" id="name" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" oninput="checkDescriptionLength(this)" required></textarea>
                <span id="description-counter"></span>
            </div>
            <div class="form-group">
                <label for="image">Image</label>
                <input type="file" name="image" id="image" required>
            </div>
            <div class="form-group">
                <label for="starting_price">Starting Price</label>
                <input type="number" name="starting_price" id="starting_price" required>
            </div>
            <div class="form-group">
                <label for="end_time">End Time</label>
                <input type="datetime-local" name="end_time" id="end_time" required>
            </div>
            <div class="form-group">
                <label for="seller_name">Seller Name</label>
                <input type="text" name="seller" id="seller" required>
            </div>
            <button type="submit" class="submit-button">Add Item</button>
        </form>
    </div>

    <script>
        function checkDescriptionLength(textarea) {
            var maxLength = 184;
            var description = textarea.value;
            var remainingChars = maxLength - description.length;

            if (remainingChars < 0) {
                document.getElementById("description-counter").textContent = "Exceeded character limit!";
            } else {
                document.getElementById("description-counter").textContent = remainingChars + " characters remaining";
            }
        }
    </script>


</body>

</html>