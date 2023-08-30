<?php
require_once 'config.php';

function storeSessionData($itemId, $userId)
{
    $_SESSION['item_id'] = $itemId;
    $_SESSION['user_id'] = $userId;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $itemId = $_POST['item_id'];
    $userId = $_POST['user_id'];
    storeSessionData($itemId, $userId);
}

$search = isset($_GET['search-term']) ? $_GET['search-term'] : '';

if (!empty($search)) {
    $escapedSearch = mysqli_real_escape_string($con, $search);
    $query = "
        SELECT items.*, MAX(biddingincrement.increment_value) AS highest_price
        FROM items
        LEFT JOIN biddingincrement ON items.id = biddingincrement.item_id
        WHERE items.name LIKE '%$escapedSearch%' OR items.description LIKE '%$escapedSearch%'
        GROUP BY items.id
    ";
} else {
    $query = "
        SELECT items.*, MAX(biddingincrement.increment_value) AS highest_price
        FROM items
        LEFT JOIN biddingincrement ON items.id = biddingincrement.item_id
        GROUP BY items.id
    ";
}

$result = $con->query($query);
?>

<!DOCTYPE html>
<html>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Noto+Serif:wght@200&display=swap" rel="stylesheet">

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
            font-family: 'Noto Serif', serif;
            color: #fff;
        }

        .grid-container {
            margin-top: 20px;
            display: grid;
            grid-template-columns: repeat(4, minmax(10px, 0.48fr));
            grid-gap: 20px;
            margin-left: 15%;

        }

        .item-box {
            border: 1px solid #ccc;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
            padding: 5px;
            position: relative;
            border-radius: 15px;
        }

        .item-name {
            font-size: 14px;
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

        .item-description-container {
            max-height: 4.5em;
            /* Adjust this value to control the number of lines */
            overflow: hidden;
        }


        .bid-button {
            position: absolute;
            bottom: 15px;
            right: 15px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Items</h1>
    </div>

    <div class="grid-container">
        <?php
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
                    <div class="item-description-container">
                        <p class="item-description"><?php echo $itemDescription; ?></p>
                    </div>
                    <p>Price: NRP <?php echo $price; ?>/-</p>
                    <button class="bid-button" data-itemid="<?php echo $itemId; ?>">Bid</button>
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
        // JavaScript code for handling the bid button click and AJAX
        const bidButtons = document.querySelectorAll('.bid-button');

        bidButtons.forEach(button => {
            button.addEventListener('click', () => {
                const itemId = button.getAttribute('data-itemid');
                const userId = <?php echo $_SESSION['user_id']; ?>;
                submitBid(itemId, userId);
            });
        });

        function submitBid(itemId, userId) {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', 'process.php?item_id=' + itemId, true);

            xhr.onload = function() {
                if (xhr.status === 200) {
                    const details = xhr.responseText;
                    window.location.href = 'process.php?item_id=' + itemId;
                }
            };

            xhr.send();
        }
    </script>
</body>

</html>