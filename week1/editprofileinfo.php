<?php
$servername = "localhost";
$username = "katherine";
$password = "20041126Ang";
$dbname = "katherine";
session_start();

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Name = mysqli_real_escape_string($conn, trim($_POST["name"]));
    $Email = mysqli_real_escape_string($conn, trim($_POST["email"]));
    $YearJoin = mysqli_real_escape_string($conn, trim($_POST["yearjoin"]));

    $sql = "UPDATE student SET name='$Name', email='$Email', yearjoin='$YearJoin' WHERE email='" . mysqli_real_escape_string($conn, $_SESSION['email']) . "'";

    if (mysqli_query($conn, $sql)) {
        echo "Record updated successfully";
        $_SESSION['email'] = $Email;
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>
