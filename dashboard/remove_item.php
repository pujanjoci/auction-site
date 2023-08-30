<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['item_id'])) {
        $itemId = $_POST['item_id'];

        // Update references in biddinghistory table
        $updateQuery = "UPDATE biddinghistory SET item_id = NULL WHERE item_id = ?";
        $updateStmt = mysqli_prepare($con, $updateQuery);
        mysqli_stmt_bind_param($updateStmt, "i", $itemId);

        if (mysqli_stmt_execute($updateStmt)) {
            // Insert the item into the archive_table
            $archiveQuery = "INSERT INTO archive_table (id, Name, Details, Image) SELECT id, Name, Details, Image FROM expired_table WHERE id = ?";
            $archiveStmt = mysqli_prepare($con, $archiveQuery);
            mysqli_stmt_bind_param($archiveStmt, "i", $itemId);

            if (mysqli_stmt_execute($archiveStmt)) {
                // Delete the item from the expired_table
                $deleteQuery = "DELETE FROM expired_table WHERE id = ?";
                $deleteStmt = mysqli_prepare($con, $deleteQuery);
                mysqli_stmt_bind_param($deleteStmt, "i", $itemId);

                if (mysqli_stmt_execute($deleteStmt)) {
                    header("Location: expired.php");
                    exit();
                } else {
                    echo "Error deleting item: " . mysqli_error($con);
                }

                mysqli_stmt_close($deleteStmt);
            } else {
                echo "Error archiving item: " . mysqli_error($con);
            }

            mysqli_stmt_close($archiveStmt);
        } else {
            echo "Error updating references: " . mysqli_error($con);
        }

        mysqli_stmt_close($updateStmt);
    }
}
