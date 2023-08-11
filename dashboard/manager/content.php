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
                        include 'config.php';

                        // Check if the user is logged in
                        if (isset($_SESSION['user_id'])) {
                            // Get the user ID
                            $userId = $_SESSION['user_id'];

                            // Get the item ID
                            $itemId = $row["id"];

                            // Retrieve the bidding data for the item
                            $stmt = $con->prepare("
                                SELECT i.user_id, i.increment_value, t.starting_price
                                FROM biddingincrement i
                                LEFT JOIN items t ON i.item_id = t.id
                                WHERE i.user_id = ? AND i.item_id = ?
                            ");
                        

                            $stmt->bind_param("ss", $userId, $itemId);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            $bidData = $result->fetch_assoc();
                            $highestBidderId = $bidData['user_id'];
                            $startingPrice = $bidData['starting_price'];
                            $incrementValue = $bidData['increment_value'];
                            $bidPrice = max($startingPrice, $incrementValue);
                        }

                            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_button'])) {
                            

                            if ($highestBidderId === $userId) {
                                echo "You are the highest bidder. You cannot place another bid.";
                            } else {
                                echo "Bid placed successfully!";
                            }
                        } else {
                            $bidPrice = 0; // Default bid price before the user logs in
                        }
                        ?>

                        <div class="price">NRP <?php echo $bidPrice; ?></div>

                        <?php if (isset($_SESSION['user_id'])) : ?>
                            <form method="POST" action="">
                                <input type="hidden" name="item_id" value="<?php echo $row["id"]; ?>">
                                <input type="number" name="new_price" placeholder="Enter new price" min="<?php echo $bidPrice; ?>" step="0.01" required>
                                <button type="submit" name="submit_bid" class="bid-button">Bid Now</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <script>
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