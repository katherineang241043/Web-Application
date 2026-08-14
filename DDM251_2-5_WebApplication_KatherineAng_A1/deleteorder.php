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

if (isset($_GET['OrderID'])) {
    $order_id = intval($_GET['OrderID']);

    $sql_detail = "DELETE FROM order_details WHERE OrderID = '$order_id'";
    mysqli_query($conn, $sql_detail);

    $sql_order = "DELETE FROM orders WHERE OrderID = '$order_id'";

    if (mysqli_query($conn, $sql_order)) {
        header("Location: order.php");
        exit();
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
} else {
    header("Location: order.php");
    exit();
}

mysqli_close($conn);
?>