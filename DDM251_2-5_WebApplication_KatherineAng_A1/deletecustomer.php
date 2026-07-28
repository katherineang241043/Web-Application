<?php
session_start();

$servername = "localhost";
$username = "katshop";
$password = "katshop_123";
$dbname = "katshop";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_GET['CustomerID'])) {
    $customer_id = $_GET['CustomerID'];
    $sql = "DELETE FROM customers WHERE CustomerID = '$customer_id'";

    if (mysqli_query($conn, $sql)) {
        header("Location: customer.php");
        exit();
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
    } else {
        header("Location: customer.php");
        exit();
    }

mysqli_close($conn);
?>