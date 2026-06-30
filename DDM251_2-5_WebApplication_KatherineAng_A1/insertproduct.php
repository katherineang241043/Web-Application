<?php
$servername = "localhost";
$username = "katshop";
$password = "katshop_123";
$dbname = "katshop";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die('Connection failed: ' . mysqli_connect_error());
}

$productID = $_POST["productID"];
$productName = $_POST["productName"];
$price = $_POST["price"];
$quantity = $_POST["quantity"];

$sql = "INSERT INTO products (ProductID, ProductName, Price, Quantity)
        VALUES ('$productID', '$productName', '$price', '$quantity')";

if (mysqli_query($conn, $sql)) {
    header("Location:products.php");
    exit();
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

mysqli_close($conn);
?>