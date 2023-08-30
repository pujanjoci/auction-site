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
    <title>Sold Items History</title>
    <link rel="stylesheet" type="text/css" href="sold.css">
</head>

<body>

    <?php include "process_sold.php"; ?>
    <h2>Sold Items History</h2>
    <table border="1">
        <tr>
            <th>User</th>
            <th>Item</th>
            <th>Bid Amount</th>
            <th>Action</th>
        </tr>
        <?php while ($history_row = mysqli_fetch_assoc($history_result)) { ?>
            <tr>
                <td><?php echo htmlspecialchars($history_row['username']); ?></td>
                <td><?php echo htmlspecialchars($history_row['name']); ?></td>
                <td><?php echo htmlspecialchars($history_row['bid_amount']); ?></td>
                <td>
                    <form method="post" action="">
                        <input type="hidden" name="item_id" value="<?php echo $history_row['item_id']; ?>">
                        <button type="submit" name="remove_item">Remove</button>
                    </form>
                </td>
            </tr>
        <?php } ?>
    </table>
    <?php
    // Check if the "remove_item" button was clicked
    if (isset($_POST['remove_item'])) {
        // Get the item_id from the form
        $item_id_to_remove = $_POST['item_id'];

        // You can add additional validation here if needed

        // Perform the deletion
        $delete_query = "DELETE FROM biddinghistory WHERE item_id = ?";
        $delete_stmt = mysqli_prepare($con, $delete_query);
        mysqli_stmt_bind_param($delete_stmt, "i", $item_id_to_remove);

        if (mysqli_stmt_execute($delete_stmt)) {
            // Deletion successful
        } else {
            // Deletion failed
            // You might want to provide an error message
            echo "Failed to remove item.";
        }

        // Close the statement
        mysqli_stmt_close($delete_stmt);
    }
    ?>

    <script>
        setTimeout(function() {
            location.reload();
        }, 30000);
    </script>
</body>

</html>