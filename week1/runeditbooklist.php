<?php
session_start();

$servername = "localhost";
$username = "katherine";
$password = "20041126Ang";
$dbname = "katherine";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$ISBN = $_POST["ISBN"];
$new_title = $_POST["title"];
$new_author = $_POST["author"];
$new_description = $_POST["description"];
$new_price = $_POST["price"];


{
    $update = "UPDATE booklist SET title='" . $_POST["title"] . "', author='" . $_POST["author"] . "', description='" . $_POST["description"] . "', price='" . $_POST["price"] . "' WHERE ISBN='" . $_SESSION["ISBN"] . "'";  }


$sql = "UPDATE booklist SET 
        Title='$new_title', 
        Author='$new_author', 
        Description='$new_description', 
        Price='$new_price' 
        WHERE ISBN='$ISBN'";

if (mysqli_query($conn, $sql)) {
    header("Location: booklist.php");
    exit();
} else {
    echo "Error updating record: " . mysqli_error($conn);
}

mysqli_close($conn);
?>