<?php
session_start();
$login_email = $_SESSION['email'];

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

$new_name = $_POST["name"];
$new_password = $_POST["password"];
$confirm_password = $_POST["confirmpassword"];
$new_yearjoin = $_POST["yearjoin"];

if ($new_password !== $confirm_password) {
    header("Location: editprofile.php?error=Passwords do not match");
    exit;
}

/* if (empty($_POST["password"]) || empty($_POST["confirmpassword"]) || empty($_POST["name"]) || empty($_POST["yearjoin"])) {
    
    header("Location: editProfile.php?error=Please fill in all fields.");


} else if ($_POST["yearjoin"] > date("Y")) {
    
    header("Location: editProfile.php?error=Year joined must within this year");

} else if ($_POST["confirmpassword"] != $_POST["password"]) {
    
    header("Location: editProfile.php?error=Confirm password must be the same with the password.");
} else if (strlen($_POST["password"]) < 6) {
    
    header("Location: editProfile.php?error=Password must be more than 6 characters.");

} else {
    
    $update = "UPDATE student SET password='" . $_POST["password"] . "', name='" . $_POST["name"] . "', yearjoin='" . $_POST["yearjoin"] . "' WHERE email='" . $_SESSION["email"] . "'";  
 */
    $sql = "UPDATE student SET name='$new_name', password='$new_password', yearjoin='$new_yearjoin' WHERE email='$login_email'";

    if (mysqli_query($conn, $sql)) {
        header("Location: profile.php");
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }

mysqli_close($conn);
?>