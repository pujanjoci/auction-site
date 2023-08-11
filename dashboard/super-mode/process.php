<?php
// Include the configuration file
require_once 'config.php';

include 'smenu.php';

// Query to fetch items from the table with the highest price
$query = "
SELECT items.*, MAX(biddingincrement.increment_value) AS highest_price
FROM items
LEFT JOIN biddingincrement ON items.id = biddingincrement.item_id
GROUP BY items.id
";

$result = $con->query($query);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Item Details</title>
    <link rel="stylesheet" href="process.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@500&family=Chakra+Petch:ital@1&family=Playfair+Display&family=Roboto+Condensed:wght@300&family=Tilt+Prism&family=Tsukimi+Rounded&display=swap" rel="stylesheet">
</head>

<body>
    <div class="container">
        <?php
        // Check if the item_id parameter exists in the URL
        if (isset($_GET['item_id'])) {
            $itemId = $_GET['item_id'];

            // Retrieve the item details from the database based on the item_id
            $query = "SELECT *, MAX(biddingincrement.increment_value) AS highest_price FROM items LEFT JOIN biddingincrement ON items.id = biddingincrement.item_id WHERE items.id = ?";
            $stmt = $con->prepare($query);
            $stmt->bind_param('i', $itemId);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();

                // Display the item details
                $itemName = $row['name'];
                $itemImage = $row['image'];
                $itemDescription = $row['description'];
                $highestPrice = $row['highest_price'];

                // Use the highest price if available, otherwise fallback to starting price
                $price = $highestPrice !== null ? $highestPrice : $row['starting_price'];
        ?>
                <div class="item-details">
                    <h3 class="item-name"><?= truncateString($itemName, 4) ?></h3>
                    <img class="item-image" src="../images/<?= $itemImage ?>">
                    <div class="item-description">
                        <p class="desc"><?= $itemDescription ?></p>
                    </div>
                    <p class="price">Price: NRP <?= $price ?>/-</p>
                </div>

                <!-- Add the bid form -->
                <form method="post" action="bid.php?item_id=<?= $itemId ?>">
                    <input type="hidden" name="item_id" value="<?= $itemId ?>">
                    <div class="bid-input">
                        <input type="text" name="bid_amount" pattern="[0-9]+" placeholder="Amount" required>
                        <input class="bid-button" type="submit" value="Bid">
                    </div>
                </form>
        <?php
            } else {
                echo 'Item not found.';
            }
        } else {
            echo 'Invalid item ID.';
        }
        ?>

        <div class="leaderboard">
            <h3>Highest-Bid</h3>

            <?php
            // Include the configuration file (no need to include it again)
            // require_once 'config.php';

            if (isset($_GET['item_id'])) {
                // Retrieve item details based on item_id from the URL
                $itemId = $_GET['item_id'];
                $queryItemDetails = "SELECT seller, starting_price FROM items WHERE id = $itemId";
                $resultItemDetails = $con->query($queryItemDetails);

                if ($resultItemDetails->num_rows > 0) {
                    $rowItemDetails = $resultItemDetails->fetch_assoc();
                    $seller = $rowItemDetails['seller'];
                    $startingPrice = $rowItemDetails['starting_price'];
                }

                // Retrieve highest and second-highest increment values for the specified item_id
                $queryBidding = "SELECT bi.increment_value, u.id, u.username FROM biddingincrement bi INNER JOIN users u ON bi.user_id = u.id WHERE bi.item_id = $itemId ORDER BY bi.increment_value DESC LIMIT 2";
                $resultBidding = $con->query($queryBidding);

                if ($resultBidding->num_rows > 0) {
                    while ($rowBidding = $resultBidding->fetch_assoc()) {
                        $incrementValue = $rowBidding['increment_value'];
                        $userId = $rowBidding['id'];
                        $userName = $rowBidding['username'];

                        echo  $userName . " - " . $incrementValue . "/-" . "<br>";
                    }
                } else {
                    echo "This item has no bid.";
                }
            }

            function truncateString($string, $wordCount)
            {
                $words = explode(' ', $string);
                if (count($words) > $wordCount) {
                    $truncatedWords = array_slice($words, 0, $wordCount);
                    return implode(' ', $truncatedWords) . '...';
                }
                return $string;
            }

            ?>
        </div>
    </div>
</body>

</html>