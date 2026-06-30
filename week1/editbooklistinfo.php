<?php
$servername = "localhost";
$username = "katherine";
$password = "20041126Ang";
$dbname = "katherine";
session_start();
$login_email = $_SESSION["email"];
// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ISBN = $_POST["ISBN"];
    $New_title = $_POST["title"];
    $New_author = $_POST["author"];
    $New_description = $_POST["description"];
    $New_price = $_POST["price"];

    $sql = "UPDATE booklist SET title='$New_title', author='$New_author', description='$New_description', price='$New_price' WHERE ISBN='$ISBN'";

    if (mysqli_query($conn, $sql)) {
        echo "Record updated successfully";
         header('Location: booklist.php');
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>
