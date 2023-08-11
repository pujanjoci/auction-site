<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_bid'])) {
    $itemId = $_POST['item_id'];
    $newPrice = $_POST['new_price'];
    $userId = $_SESSION['user_id'];

    // Check if the user has already bid for the item
    $query = "SELECT * FROM biddingincrement WHERE item_id = $itemId AND user_id = $userId";
    $result = mysqli_query($con, $query);
    $hasBid = mysqli_num_rows($result) > 0;

    if ($hasBid) {
        // User has already bid, update the existing bid
        $query = "UPDATE biddingincrement SET increment_value = $newPrice WHERE item_id = $itemId AND user_id = $userId";
        $result = mysqli_query($con, $query);
        if ($result) {
            echo "<script>alert('Bid updated successfully');</script>";
        } else {
            echo "<script>alert('Failed to update bid');</script>";
        }
    } else {
        // User is placing a new bid, insert a new row
        $query = "INSERT INTO biddingincrement (item_id, user_id, increment_value) VALUES ($itemId, $userId, $newPrice)";
        $result = mysqli_query($con, $query);
        if ($result) {
            echo "<script>alert('Bid placed successfully');</script>";
        } else {
            echo "<script>alert('Failed to place bid');</script>";
        }
    }

    // Update the highest_bid for the item
    $updateHighestBidQuery = "UPDATE biddingincrement AS b1
        SET highest_bid = (
            SELECT MAX(increment_value)
            FROM biddingincrement AS b2
            WHERE b2.item_id = b1.item_id
        )
        WHERE b1.increment_value = (
            SELECT MAX(increment_value)
            FROM biddingincrement AS b3
            WHERE b3.item_id = b1.item_id
        )";
    $updateHighestBidResult = mysqli_query($con, $updateHighestBidQuery);
    if ($updateHighestBidResult) {
        echo "<script>alert('Highest bid updated successfully');</script>";
    } else {
        echo "<script>alert('Failed to update highest bid');</script>";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Dashboard</title>
    <link rel="stylesheet" type="text/css" href="super.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-w5Z4jf4Gf6BJrRmcJyIOhwG8fg9ZbeixBr+9g2eOjOqsxgEMf8P+vnjaF/wmzSsbY+2LisZ8u+M6DlX3TCIS0A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
</head>

<body>
    <div class="content">
        <h2>Items in Auction</h2>
        <?php foreach ($data as $row) : ?>
            <div class="item">
                <div class="image">
                    <?php
                    $imageId = $row["image"];
                    $thumbnailUrl = "thumbnail.php?imageId=" . $imageId;
                    ?>
                    <img src="<?php echo $thumbnailUrl; ?>" alt="Item Image">
                </div>

                <div class="details">
                    <h3><?php echo $row["name"]; ?></h3>
                    <p>Description: <?php echo $row["description"]; ?></p>
                </div>

                <div class="bids-container">
                    <div class="bids">
                    <?php
                    $itemId = $row["id"];
                    $userId = $_SESSION['user_id'];

                    // Fetch the starting_price from items table
                    $startingPrice = $row["starting_price"];

                    // Check if the user has already bid for the item
                    $query = "SELECT * FROM biddingincrement WHERE item_id = $itemId AND user_id = $userId";
                    $result = mysqli_query($con, $query);
                    $hasBid = mysqli_num_rows($result) > 0;

                    if ($hasBid) {
                        // Fetch the highest increment value for the given item ID
                        $query = "SELECT MAX(increment_value) AS max_increment FROM biddingincrement WHERE item_id = $itemId";
                        $result = mysqli_query($con, $query);
                        $row = mysqli_fetch_assoc($result);
                        $max_increment = $row["max_increment"];

                        if ($max_increment && $max_increment > $startingPrice) {
                            // Check if the user has the highest increment value
                            $query = "SELECT * FROM biddingincrement WHERE item_id = $itemId AND user_id != $userId AND increment_value > $max_increment";
                            $result = mysqli_query($con, $query);
                            $hasHigherIncrement = mysqli_num_rows($result) === 0;

                            if ($hasHigherIncrement) {
                                echo "User is the highest bidder";
                             } else {
                                $amountToDisplay = max($startingPrice, $max_increment);
                        ?>
                        
                            <p>NRP: <?php echo $amountToDisplay; ?></p>
                            <form method="POST" action="">
                                <input type="hidden" name="item_id" value="<?php echo $itemId; ?>">
                                <input type="number" name="new_price" placeholder="Enter new price" min="<?php echo $startingPrice; ?>" step="0.01" required>
                                <button type="submit" name="submit_bid" class="bid-button">Bid Now</button>
                            </form>
                        <?php
                            // Skip the remaining code for this item
                            continue;
                            }
                        }
                    }
                    ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>

</html>