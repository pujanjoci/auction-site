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
            include "../admin/menu.php";
            break;
        case "staff":
            include "../staff/menu.php";
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

<html>

<head>
    <title>Contact Us</title>
</head>

<body background="" bgcolor="#ffffff" style="background-size:100% 100%">

    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@200&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Mali:wght@300&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Neucha&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Amatic+SC&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Merienda&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Merienda+One&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="contact.css">

    <div class="talk">
        <h1>Discover the Ultimate Auction Experience!</h1>
    </div>
    <div class="para">
        <p>Join us at the prestigious Rachit Auction House, where extraordinary treasures await you. Step into a world of luxury, elegance, and excitement as we present an exclusive collection of rare artifacts, fine art, exquisite jewelry, vintage collectibles, and much more.

            At the Rachit Auction House, we bring together discerning collectors, passionate enthusiasts, and connoisseurs from around the globe. Our curated auctions offer a remarkable opportunity to acquire unique and coveted pieces that embody history, craftsmanship, and artistic brilliance.

            Immerse yourself in the ambiance of anticipation as the auctioneer takes center stage, guiding you through the bidding process. Feel the thrill as bids escalate, rivaling the crescendo of applause. With every paddle raise, you enter into a captivating competition to own something truly exceptional.

            Our expert team of specialists ensures the authenticity and provenance of each item, providing you with complete peace of mind. From exquisite antiques to contemporary masterpieces, our diverse range of offerings caters to every passion and desire.

            Whether you're a seasoned collector or a newcomer to the world of auctions, our knowledgeable staff is here to guide you through the journey. We believe that every auction experience should be accompanied by impeccable service, tailored advice, and personalized attention.

            Join us at the Rachit Auction House and embark on an exhilarating adventure filled with discovery, luxury, and the joy of acquiring extraordinary treasures. Don't miss your chance to become part of the legacy. Register today and elevate your collecting to new heights.

            The Rachit Auction House - Where Extraordinary Finds Await!</p>
    </div>


    <div class="form">
        <form>
            <h2>Send Messages</h2>
            <div class="inputbox">
                <input type="text" name="name" required="required">
                <span>First Name</span>
            </div>
            <div class="inputbox">
                <input type="text" name="email" required="required">
                <span>Email</span>
            </div>
            <div class="inputbox">
                <textarea oninput="checkDescriptionLength(this)" required="required"></textarea>
                <span id="messages">Message</span>
                <span id="description-counter"></span>
            </div>
            <div class="inputbox">
                <input type="submit" name="Submit" value="Send">
            </div>
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