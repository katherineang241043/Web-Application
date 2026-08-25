<?php
session_start();

$keep_username = "";
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = mysqli_connect("localhost", "katshop", "katshop_123", "katshop");

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $uname = trim($_POST['username']);
    $pword = trim($_POST['password']);
    $keep_username = $uname;


    if ($uname == "" && $pword == "") {
        $error_message = "Please enter your Username and Password.";
    } elseif ($uname == "") {
        $error_message = "Please enter your Username.";
    } elseif ($pword == "") {
        $error_message = "Please enter your Password.";
    } else {
        $sql = "SELECT Email, Password FROM customers WHERE UserName = '$uname'";
        $result = mysqli_query($conn, $sql);

        if ($row = mysqli_fetch_assoc($result)) {
            if ($pword == $row['Password']) {
                $_SESSION['email'] = $row['Email'];
                header("Location: dashboard.php");
                exit();
            } else {
                $error_message = "Incorrect password. Please try again.";
            }
        } else {
            $error_message = "Username not found.";
        }
    }
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
            margin-bottom: 10px;
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
            margin: 5px 0px;
            color: #4B2E2B;
            padding: 15px 40px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        .btn:hover {
            background-color: #E6D5C3;
            transform: none;
        }

        .error {
            color: #ff0000;
            font-weight: 400;
            text-align: center;
            padding-bottom: 20px;
        }

        .signup-text {
            color: #FFF8F0;
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
        }

        .signup-text a {
            color: #C08552;
            font-weight: 600;
            text-decoration: none;
        }

        .signup-text a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Welcome</h1>
        <p>Please log in to continue.</p>
    </div>

    <div class="login">
        <form method="POST" autocomplete="off">
            <div class="error"><?php echo $error_message; ?></div>
            
            <div class="login_info">
                <div>
                    <label>Username:</label>
                    <input type="text" name="username" value="<?php echo $keep_username; ?>" autocomplete="off">
                </div>

                <div>
                    <label>Password:</label>
                    <div style="position: relative; width: 67%; padding: 0; gap: 0;">
                        <input type="password" id="pword" name="password" autocomplete="new-password" style="width: 100%; padding-right: 35px;">
                        <i class="fa-regular fa-eye" onclick="toggleVisibility('pword', this)" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #4B2E2B;"></i>
                    </div>
                </div>
            </div>

            <div>
                <input class="btn" type="submit" value="Sign In">
            </div>

            <div class="signup-text">
                Don't have an account? <a href="register.php">Sign Up</a>
            </div>
        </form>
    </div>

    <script>
        function toggleVisibility(inputId, icon) {
            var input = document.getElementById(inputId);
            if (input.type === "password") {
                input.type = "text";
                icon.className = "fa-regular fa-eye-slash";
            } else {
                input.type = "password";
                icon.className = "fa-regular fa-eye";
            }
        }
    </script>
</body>

</html>