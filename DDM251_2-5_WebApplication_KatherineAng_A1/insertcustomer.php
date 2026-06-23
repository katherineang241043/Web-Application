<?php
$servername = "localhost";
$username = "katshop";
$password = "katshop_123";
$dbname = "katshop";

// Create connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
    if (!$conn) {die('Connection failed: ' . mysqli_connect_error());}

$customerID = $_POST["customerID"];
$username = $_POST["username"];
$name = $_POST["name"];
$email = $_POST["email"];
$phonenumber = $_POST["phonenumber"];
$password = $_POST["password"];

$sql = "INSERT INTO customers (customerID, username, name, email, phonenumber, password)
VALUES ('$customerID', '$username', '$name', '$email', '$phonenumber', '$password')";

if (mysqli_query($conn, $sql)) {
    header("Location:customer.php");
}

mysqli_close($conn);