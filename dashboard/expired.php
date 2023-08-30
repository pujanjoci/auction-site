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

?>
<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }

        h2 {
            background-color: #7e3c3c93;
            color: #fff;
            padding: 10px;
            text-align: center;
            margin-left: 13.4%;
            border: 1px solid #ccc;
            border-radius: 15px;
        }

        table {
            width: 70%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 5px 100px rgba(0, 0, 0, 0.1);
            margin-left: 20%;
            border-radius: 15px;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
            border-right: 1px solid #ccc;
            border-bottom: 1px solid #ccc;
        }

        td.remove {
            background-color: transparent;
            border: none;
        }

        th {
            color: grey;
        }

        th.remove {
            background-color: transparent;
        }

        tr:hover {
            background-color: #ddd;
        }

        img {
            max-width: 100px;
            max-height: 100px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        th:last-child,
        td:last-child {
            border-right: none;
            border-bottom: none;
        }
    </style>
</head>

<body>

    <?php
    include "config.php";
    include "remove_item.php";
    include "process_expired_items.php";

    $sql = "SELECT * FROM expired_table";
    $result = $con->query($sql);
    ?>

    <h2>Expired Items</h2>

    <table>
        <tr>
            <th>Name</th>
            <th>Details</th>
            <th>Image</th>
            <th class="remove"></th>
        </tr>


        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $name = $row["Name"];
                $details = $row["Details"];
                $imageFilename = $row["Image"]; // Assuming the Image column stores image filenames
                $imagePath = "images/" . $imageFilename; // Update the image path

                echo "<tr>";
                echo "<td>" . htmlspecialchars($name) . "</td>";
                echo "<td>" . htmlspecialchars($details) . "</td>";
                echo "<td><img src='" . htmlspecialchars($imagePath) . "' alt='" . htmlspecialchars($name) . "'></td>";
                echo "<td class='remove'>";
                echo "<form method='post'>";
                echo "<input type='hidden' name='item_id' value='" . $row["id"] . "'>";
                echo "<button type='submit' name='remove_button'>Remove</button>";
                echo "</form>";
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No expired items found</td></tr>";
        }
        ?>
    </table>

</body>

</html>