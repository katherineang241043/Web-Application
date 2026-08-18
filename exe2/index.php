<?php
date_default_timezone_set('Asia/Kuala_Lumpur');
session_start();

$servername = "localhost";
$username = "game";
$password = "game123";
$dbname = "game";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["name"])) {
        $error_msg = "Please fill in Name.";
    } else if (empty($_POST["email"])) {
        $error_msg = "Please fill in Email.";
    } else if (empty($_POST["age"])) {
        $error_msg = "Please fill in Age.";
    } else {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $age = $_POST['age'];

        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $code = '';
        for ($i = 0; $i < 6; $i++) {
            $code .= $characters[rand(0, strlen($characters) - 1)];
        }  
        $uniqueCode = $code . date('YmdHis') . "_" . $code;

        $stmt = $conn->prepare("INSERT INTO users (UID, name, email, age) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $uniqueCode, $name, $email, $age);

        if ($stmt->execute()) {
            $_SESSION['UID'] = $uniqueCode;

            header('Location: game.php');
            exit();
        } else {
            $error_msg = "Database Error: " . $conn->error;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Form</title>
  <style>
    * { font-size: 20px; }
    body { display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
    h2 { margin-top: 15px; margin-bottom: 8px; }
    input[type="text"], input[type="email"], input[type="number"] { padding: 4px; width: 250px; }
    input[type="submit"] { margin-top: 20px; padding: 4px 12px; cursor: pointer; }
    .error { color: red; }
  </style>
</head>
<body>
  <div id="email">
    <?php if (!empty($error_msg)) echo "<p class='error'>$error_msg</p>"; ?>
    <form target="_self" method="POST">
      <h2>Name:</h2>
      <input type="text" name="name" required>
      <br />
      <h2>Email:</h2>
      <input type="email" name="email" required>
      <br />
      <h2>Age:</h2>
      <input type="number" name="age" required>
      <br />
      <input type="submit" value="Submit">
    </form>
  </div>
</body>
</html>