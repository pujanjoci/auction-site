<!DOCTYPE html>
<html>

<head>
    <title>Expired Items</title>
    <link rel="stylesheet" type="text/css" href="expired.css">
</head>

<body>
    <?php
    // Include necessary files
    require_once('config.php');
    require_once('menu.php');
    require_once('userchecker.php');

    // Query to fetch expired items from the database
    $query = "SELECT Name, Details, Image FROM expired_table";
    $result = mysqli_query($conn, $query);

    // Check if any expired items were found
    if (mysqli_num_rows($result) > 0) {
        echo "<table>";
        echo "<tr><th>Name</th><th>Details</th><th>Image</th></tr>";

        // Loop through each expired item and display its details
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['Name'] . "</td>";
            echo "<td>" . $row['Details'] . "</td>";
            echo "<td><img src='" . $row['Image'] . "'></td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "No expired items found.";
    }

    // Close the database connection
    mysqli_close($conn);
    ?>
</body>

</html>