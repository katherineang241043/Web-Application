<?php
$servername = "localhost";
$username = "kshop";
$password = "kshop123";
$dbname = "kshop";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
if (isset($_POST['email']) && isset($_POST['password'])) {
  if (empty($_POST["email"])){
    echo("Please fill in Email.");
  } else if (empty($_POST["password"])){
    echo("Please fill in Password.");
  }

session_start();

  $query = "SELECT * FROM customers WHERE email='" . $_POST["email"] . "' && password='" . $_POST["password"] . "'";
  $result = mysqli_query($conn, $query) or die ("Couldn't execute query");

  $numrow = mysqli_num_rows($result);

  if ($numrow > 0) {
    $_SESSION['email'] = $_POST['email'];
    header('Location: booking.php');
  } else {
    echo "No user found";
  }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <style>
     * {
      font-size: 20px;
    }

    body {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
  </style>


</head>
<body>
  <div id="email">
    <form target="_self" method="POST">
      <h2>Enter your Email:</h2>
      <input type="text" name="email">
      <br />
      <h2>Password</h2>
      <input type="password" name="password">
      <input type="submit" value="Login">
    </form>
  </div>
</body>
</html>