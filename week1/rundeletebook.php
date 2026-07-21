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


//$ISBN = $_GET["ISBN"];

$sql="DELETE FROM booklist WHERE ISBN='" . $_GET["ISBN"] . "'";

if ($conn->query($sql) === TRUE) 
   header("Location: booklist.php");

$conn->close();