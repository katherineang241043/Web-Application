<?php
session_start();

$error_message  = "";
$keep_username  = "";
$keep_password  = "";
$keep_cpassword = "";
$keep_name      = "";
$keep_email     = "";
$keep_phone     = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = mysqli_connect("localhost", "katshop", "katshop_123", "katshop");

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $uname  = trim($_POST['username']);
    $pword  = trim($_POST['password']);
    $cpword = trim($_POST['confirm_password']);
    $name   = trim($_POST['name']);
    $email  = trim($_POST['email']);
    $phone  = trim($_POST['phone']);

    $keep_username  = $uname;
    $keep_password  = $pword;
    $keep_cpassword = $cpword;
    $keep_name      = $name;
    $keep_email     = $email;
    $keep_phone     = $phone;


    if ($uname == "" || $pword == "" || $cpword == "" || $name == "" || $email == "" || $phone == "") {
        $error_message = "Please fill in all required fields.";
    } 
    elseif ($pword != $cpword) {
        $error_message = "Passwords do not match.";
    } 
    else {
        $check_query = "SELECT CustomerID FROM customers WHERE UserName = '$uname' OR Email = '$email'";
        $check_res = mysqli_query($conn, $check_query);

        if (mysqli_num_rows($check_res) > 0) {
            $error_message = "Username or Email already exists.";
        } else {
            $id_query = "SELECT MAX(CAST(CustomerID AS UNSIGNED)) AS max_id FROM customers";
            $id_result = mysqli_query($conn, $id_query);
            $row = mysqli_fetch_assoc($id_result);
            
            if ($row['max_id']) {
                $new_customer_id = $row['max_id'] + 1;
            } else {
                $new_customer_id = 1001;
            }
            $insert_query = "INSERT INTO customers (CustomerID, UserName, Name, Email, PhoneNumber, Password) 
                            VALUES ('$new_customer_id', '$uname', '$name', '$email', '$phone', '$pword')";

            if (mysqli_query($conn, $insert_query)) {
                header("Location: login.php");
                exit();
            } else {
                $error_message = "Error creating account: " . mysqli_error($conn);
            }
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
    <title>Sign Up page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

        * {
            margin: 0;
            box-sizing: border-box;
        }

        body {
            margin: 30px 70px;
            font-family: "Poppins", sans-serif;
            font-size: 14px;
            background-color: #ffffff;
        }

        .login {
            background-color: #4B2E2B;
            max-width: 480px;
            padding: 35px 48px;
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
            margin-bottom: 15px;
        }

        .login_info {
            margin-bottom: 15px;
        }

        .login_info div {
            display: flex;
            gap: 15px;
            padding: 5px 0;
            align-items: center;
        }

        .login_info label {
            width: 38%;
            font-size: 14px;
            font-weight: 600;
            color: #C08552;
            line-height: 1.2;
        }

        .input-box {
            position: relative;
            width: 62%;
        }

        .login_info input {
            background-color: #FFF8F0;
            width: 100%;
            height: 38px;
            padding: 10px;
            padding-right: 35px;
            border-radius: 8px;
            border: none;
        }

        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #4B2E2B;
            font-size: 14px;
        }

        .btn {
            background-color: #FFF8F0;
            font-size: 16px;
            font-weight: 600;
            width: 100%;
            margin: 8px 0px;
            color: #4B2E2B;
            padding: 14px 40px;
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
            padding-bottom: 15px;
        }

        .login-text {
            color: #FFF8F0;
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
        }

        .login-text a {
            color: #C08552;
            font-weight: 600;
            text-decoration: none;
        }

        .login-text a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Create Account</h1>
        <p>Please fill in the details to sign up.</p>
    </div>

    <div class="login">
        <form method="POST" autocomplete="off">
            <div class="error"><?php echo $error_message; ?></div>

            <div class="login_info">
                <div>
                    <label>Username:</label>
                    <div class="input-box">
                        <input type="text" name="username" value="<?php echo $keep_username; ?>" autocomplete="off">
                    </div>
                </div>

                <div>
                    <label>Password:</label>
                    <div class="input-box">
                        <input type="password" id="pword" name="password" value="<?php echo $keep_password; ?>" autocomplete="new-password">
                        <i class="fa-regular fa-eye toggle-password" onclick="toggleVisibility('pword', this)"></i>
                    </div>
                </div>

                <div>
                    <label>Confirm Password:</label>
                    <div class="input-box">
                        <input type="password" id="cpword" name="confirm_password" value="<?php echo $keep_cpassword; ?>" autocomplete="new-password">
                        <i class="fa-regular fa-eye toggle-password" onclick="toggleVisibility('cpword', this)"></i>
                    </div>
                </div>

                <div>
                    <label>Name:</label>
                    <div class="input-box">
                        <input type="text" name="name" value="<?php echo $keep_name; ?>" autocomplete="off">
                    </div>
                </div>

                <div>
                    <label>Email:</label>
                    <input class="input-box" style="display:none;">
                    <div class="input-box">
                        <input type="email" name="email" value="<?php echo $keep_email; ?>" autocomplete="off">
                    </div>
                </div>

                <div>
                    <label>Phone Number:</label>
                    <div class="input-box">
                        <input type="text" name="phone" value="<?php echo $keep_phone; ?>" autocomplete="off">
                    </div>
                </div>
            </div>

            <div>
                <input class="btn" type="submit" value="Sign Up">
            </div>

            <div class="login-text">
                Already have an account? <a href="login.php">Log In</a>
            </div>
        </form>
    </div>

    <script>
        function toggleVisibility(inputId, icon) {
            var input = document.getElementById(inputId);
            if (input.type === "password") {
                input.type = "text";
                icon.className = "fa-regular fa-eye-slash toggle-password";
            } else {
                input.type = "password";
                icon.className = "fa-regular fa-eye toggle-password";
            }
        }
    </script>
</body>

</html>