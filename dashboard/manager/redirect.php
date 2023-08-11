<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: ../../login.html");
    exit;
}

$currentDirectory = __DIR__;
$targetDirectory = "D:/Code/Xampp/htdocs/auction-test/dashboard/super-mode";

if (!is_dir($targetDirectory) || strpos($currentDirectory, $targetDirectory) === false) {
    header("Location: super-mode.php");
    exit;
} else {
    header("Location: ../../login.html");
    exit;
}
