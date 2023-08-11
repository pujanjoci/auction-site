<?php
// Include the configuration file
require_once 'config.php';

// Function to store item_id and user_id in the session
function storeSessionData($itemId, $userId)
{
    // Store the data in the session variables
    $_SESSION['item_id'] = $itemId;
    $_SESSION['user_id'] = $userId;
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the item_id and user_id from the form
    $itemId = $_POST['item_id'];
    $userId = $_POST['user_id'];

    // Store the data in the session
    storeSessionData($itemId, $userId);
}

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
    <style>
        body {
            margin: 0;
            padding: 0;
        }

        .container {
            width: 84%;
            background-color: #00000057;
            margin-left: 15%;
            margin-top: -25px;
            border-radius: 25px;
        }

        h1 {
            text-align: center;
            font-size: 50px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #fff;
        }

        .grid-container {
            margin-top: 20px;
            display: grid;
            grid-template-columns: repeat(2, minmax(20px, 0.48fr));
            grid-gap: 20px;
            margin-left: 15%;

        }

        .item-box {
            border: 1px solid #ccc;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
            padding: 10px;
            position: relative;
            border-radius: 15px;
        }

        .item-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .item-image {
            width: 40%;
            max-height: 150px;
            object-fit: cover;
            margin-bottom: 10px;
            border-radius: 10px;
        }

        .item-description {
            margin-bottom: 10px;
        }

        .bid-button {
            position: absolute;
            top: 10px;
            right: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Item Details</h1>
    </div>

    <div class="grid-container">
        <?php
        // Display item details
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $itemId = $row['id'];
                $itemName = $row['name'];
                $itemImage = $row['image'];
                $itemDescription = $row['description'];
                $highestPrice = $row['highest_price'];

                // Use the highest price if available, otherwise fallback to starting price
                $price = $highestPrice !== null ? $highestPrice : $row['starting_price'];
        ?>

                <div class="item-box">
                    <h2 class="item-name"><?php echo $itemName; ?></h2>
                    <img class="item-image" src="../images/<?php echo $itemImage; ?>" alt="<?php echo $itemName; ?>">
                    <p class="item-description"><?php echo $itemDescription; ?></p>
                    <p>Price: NRP <?php echo $price; ?>/-</p>
                    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <input type="hidden" name="item_id" value="<?php echo $itemId; ?>">
                        <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
                        <input class="bid-button" type="submit" value="Bid">
                    </form>
                </div>

        <?php
            }
        } else {
            echo "No items found.";
        }
        ?>
    </div>

    <!-- JavaScript code for handling the bid button click and AJAX -->
    <script>
        // Get all the bid buttons
        const bidButtons = document.querySelectorAll('.bid-button');

        // Add click event listener to each bid button
        bidButtons.forEach(button => {
            button.addEventListener('click', () => {
                const itemId = button.previousElementSibling.previousElementSibling.value;
                fetchDetails(itemId);
            });
        });

        // Function to fetch item details using AJAX
        function fetchDetails(itemId) {
            // Create an XMLHttpRequest object
            const xhr = new XMLHttpRequest();

            // Set up the request
            xhr.open('GET', 'process.php?item_id=' + itemId, true);

            // Define the callback function
            xhr.onload = function() {
                if (xhr.status === 200) {
                    // Display the details received from the server
                    const details = xhr.responseText;
                    // Redirect to the progress.php page or perform any other necessary action
                    window.location.href = 'process.php?item_id=' + itemId;
                }
            };

            // Send the request
            xhr.send();
        }
    </script>
</body>

</html>