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

$customer_id = $_POST["CustomerID"];
$new_username = $_POST["username"];
$new_name = $_POST["name"];
$new_password = $_POST["password"];
$new_email = $_POST["email"];
$new_phone = $_POST["phonenumber"];

$sql = "UPDATE customers SET 
        UserName='$new_username', 
        Name='$new_name', 
        Password='$new_password', 
        Email='$new_email', 
        PhoneNumber='$new_phone' 
        WHERE CustomerID='$customer_id'";

if (mysqli_query($conn, $sql)) {
    header("Location: customer.php");
    exit();
} else {
    echo "Error updating record: " . mysqli_error($conn);
}

mysqli_close($conn);
?>