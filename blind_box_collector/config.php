<?php
$servername = "localhost";
$username = "popmart_collector";
$password = "pop123";
$dbname = "popmart_collector";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>
