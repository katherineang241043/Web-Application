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

if (isset($_GET['ProductID'])) {
    $product_id = $_GET['ProductID'];
    $sql = "DELETE FROM products WHERE ProductID = '$product_id'";

    if (mysqli_query($conn, $sql)) {
        header("Location: products.php");
        exit();
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
    } else {
        header("Location: products.php");
        exit();
    }

mysqli_close($conn);
?>