<?php

// Function to get the highest increment value for a specific item_id
function getHighestIncrementValue($con, $item_id)
{
    $sql = "SELECT MAX(increment_value) AS highest_increment FROM biddingincrement WHERE item_id = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "i", $item_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $highest_increment);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
    return $highest_increment;
}

// Function to move data from biddingincrement to biddinghistory
function moveToBiddingHistory($con, $item_id, $user_id, $bid_amount, $created_at)
{
    $sql = "INSERT INTO biddinghistory (item_id, user_id, bid_amount, created_at) VALUES (:item_id, :user_id, :bid_amount, :created_at)";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':item_id', $item_id, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':bid_amount', $bid_amount, PDO::PARAM_INT);
    $stmt->bindParam(':created_at', $created_at, PDO::PARAM_STR);
    $stmt->execute();
}

// Function to delete data from biddingincrement
function deleteFromBiddingIncrement($con, $item_id)
{
    $sql = "DELETE FROM biddingincrement WHERE item_id = :item_id";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':item_id', $item_id, PDO::PARAM_INT);
    $stmt->execute();
}

// Assuming you have retrieved the current date and time as $current_datetime

// Fetch the current date and time
$current_datetime = date("Y-m-d H:i:s");

$sql = "SELECT i.id AS item_id, i.end_time, bi.user_id, bi.increment_value, bi.created_at
        FROM items i
        INNER JOIN biddingincrement bi ON i.id = bi.item_id
        WHERE i.end_time <= ?
        ORDER BY bi.increment_value DESC
        LIMIT 1";

$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, "s", $current_datetime);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $item_id = $row['item_id'];
    $user_id = $row['user_id'];
    $bid_amount = $row['increment_value'];
    $created_at = $row['created_at'];

    moveToBiddingHistory($con, $item_id, $user_id, $bid_amount, $created_at);
    deleteFromBiddingIncrement($con, $item_id);
}

$query = "SELECT bh.id, u.username, i.name, bh.bid_amount, bh.item_id
          FROM biddinghistory bh
          INNER JOIN users u ON bh.user_id = u.id
          INNER JOIN items i ON bh.item_id = i.id";
$history_result = mysqli_query($con, $query);
