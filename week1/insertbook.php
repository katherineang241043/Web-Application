<?php
$servername = "localhost";
$username = "katherine";
$password = "20041126Ang";
$dbname = "katherine";

// Create connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
    if (!$conn) {die('Connection failed: ' . mysqli_connect_error());}

$ISBN = $_POST["ISBN"];
$title = $_POST["title"];
$author = $_POST["author"];
$description = $_POST["description"];
$price = $_POST["price"];

$sql = "INSERT INTO booklist (ISBN, title, author, description, price) 
VALUES ('$ISBN', '$title', '$author', '$description', '$price')";

if(mysqli_query($conn, $sql)); {
  header('Location: booklist.php');
}

mysqli_close($conn);
?>