<?php
session_start();

$servername = "localhost";
$username = "katshop";
$password = "katshop_123";
$dbname = "katshop";

$keep_username = "";
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if (!empty($_POST['username'])) {
        $keep_username = htmlspecialchars($_POST['username']);
    }

    if (empty($_POST['username']) || empty($_POST['password'])) {
        $error_message = "Please enter your Username and Password.";
    } else {
        $uname = $_POST['username'];
        $pword = $_POST['password'];

        $query = "SELECT * FROM `customers` WHERE `username` = '$uname'";
        $data = $conn->query($query);

        if ($data->num_rows > 0) {
            $row = $data->fetch_assoc();

            if ($pword == $row['Password']) {
                $_SESSION['email'] = $row['Email'];
                header("location:welcome.php");
                exit();
            } else {
                $error_message = "Incorrect password. Please try again.";
            }
        } else {
            $error_message = "Username not found.";
        }
    }

    $conn->close();
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login page</title>
</head>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

    * {
        margin: 0;
        box-sizing: border-box;
    }

    body {
        margin: 70px;
        font-family: "Poppins", sans-serif;
        font-size: 14px;
        background-color: #ffffff;
    }

    .login {
        background-color: #4B2E2B;
        max-width: 480px;
        padding: 52px;
        margin: auto;
        border-radius: 20px;
    }

    h1 {
        color: #C08552;
        font-size: 45px;
        font-weight: 800;
    }

    .header {
        text-align: center;
        margin-bottom: 35px;
    }

    .login_info {
        margin-bottom: 40px;
    }

    .login_info div {
        display: flex;
        gap: 20px;
        padding: 10px 0;
        align-items: center;
    }

    .login_info label {
        width: 28%;
        font-size: 18px;
        font-weight: 600;
        color: #C08552;
    }

    .login_info input {
        background-color: #FFF8F0;
        width: 67%;
        height: 40px;
        padding: 10px;
        border-radius: 8px;
        border: none;
    }


    .btn {
        background-color: #FFF8F0;
        font-size: 16px;
        font-weight: 600;
        width: 100%;
        margin: 10px 0px;
        color: #4B2E2B;
        padding: 15px 40px;
        border: none;
        border-radius: 10px;
    }

    .error {
        color: #ff0000;
        font-weight: 400;
        text-align: center;
        padding-bottom: 20px;
    }

</style>

<body>
    <div class="header">
        <h1>Welcome</h1>
        <p>Please log in to continue.</p>
    </div>

    <div class="login">
        <form target="_self" method="POST">
            <div class="error"><?php echo $error_message; ?></div>
            <div class="login_info">
                <div>
                    <label>Username:</label><input type="text" name="username" value="<?php echo $keep_username; ?>">
                </div>

                <div>
                    <label>Password:</label><input type="password" name="password">
                </div>
            </div>

            <div><input class="btn" type="submit" value="Sign In"></div>
        </form>
    </div>
</body>

</html>