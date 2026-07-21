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


if (empty($_POST["ISBN"]) || empty($_POST["title"]) || empty($_POST["author"]) || empty($_POST["description"]) || empty($_POST["price"])) {
    
    header("Location: addbook.php?error=Please fill in all fields.");
    exit();
} else if (!is_numeric($_POST["price"])) {

    header("Location: addbook.php?error=Price must be valid numbers.");
    exit();   
} else if (!is_numeric($_POST["ISBN"])) {

    header("Location: addbook.php?error=ISBN must be valid numbers.");
    exit();
}else if (strlen($_POST["ISBN"]) !== 13) {

    header("Location: addbook.php?error=ISBN must be 13 characters long.");
    exit();
}


$sql = "INSERT INTO booklist (ISBN, title, author, description, price) 
VALUES ('$ISBN', '$title', '$author', '$description', '$price')";

if(mysqli_query($conn, $sql)); {
  header('Location: booklist.php');
    exit();
}


mysqli_close($conn);
?>