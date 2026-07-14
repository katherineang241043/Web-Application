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

$product_id = $_POST["ProductID"];
$product_name = trim($_POST["productname"]);
$price = trim($_POST["price"]);
$quantity = trim($_POST["stock"]);


if ($product_name === "" || $price === "" || $quantity === "" || !is_numeric($price) || floatval($price) <= 0) {
    
    $_SESSION['old_post'] = $_POST;
    
    if ($product_name === "" || $price === "" || $quantity === "") {
        header("Location: editproduct.php?error=All fields are required and cannot be empty&ProductID=" . $product_id);
    } else {
        header("Location: editproduct.php?error=Price must be a valid positive number&ProductID=" . $product_id);
    }
    exit();
}


$sql = "UPDATE products SET 
        ProductName='$product_name', 
        Price='$price', 
        Quantity='$quantity' 
        WHERE ProductID='$product_id'";

if (mysqli_query($conn, $sql)) {
    header("Location: products.php");
    exit();
} else {
    echo "Error updating record: " . mysqli_error($conn);
}

mysqli_close($conn);
?>