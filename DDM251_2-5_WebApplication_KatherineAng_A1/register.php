<?php
session_start();

$error_message = "";
$keep_username   = "";
$keep_name       = "";
$keep_email      = "";
$keep_phone      = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = new mysqli("localhost", "katshop", "katshop_123", "katshop");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $uname = trim($_POST['username'] ?? '');
    $pword = trim($_POST['password'] ?? '');
    $name  = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');

    $keep_username   = htmlspecialchars($uname);
    $keep_name       = htmlspecialchars($name);
    $keep_email      = htmlspecialchars($email);
    $keep_phone      = htmlspecialchars($phone);

    if (empty($uname) || empty($pword) || empty($name) || empty($email) || empty($phone)) {
        $error_message = "Please fill in all required fields.";
    } else {
        $check_stmt = $conn->prepare("SELECT CustomerID FROM customers WHERE UserName = ? OR Email = ?");
        $check_stmt->bind_param("ss", $uname, $email);
        $check_stmt->execute();
        $check_res = $check_stmt->get_result();

        if ($check_res->num_rows > 0) {
            $error_message = "Username or Email already exists.";
        } else {
            $id_query = "SELECT MAX(CAST(CustomerID AS UNSIGNED)) AS max_id FROM customers";
            $id_result = $conn->query($id_query);
            $row = $id_result->fetch_assoc();
            
            if ($row['max_id']) {
                $new_customer_id = $row['max_id'] + 1;
            } else {
                $new_customer_id = 1001;
            }

            $stmt = $conn->prepare("INSERT INTO customers (CustomerID, UserName, Name, Email, PhoneNumber, Password) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("isssss", $new_customer_id, $uname, $name, $email, $phone, $pword);

            if ($stmt->execute()) {
                header("Location: login.php");
                exit();
            } else {
                $error_message = "Error creating account: " . $conn->error;
            }
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
    <title>Sign Up page</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

        * {
            margin: 0;
            box-sizing: border-box;
        }

        body {
            margin: 40px 70px;
            font-family: "Poppins", sans-serif;
            font-size: 14px;
            background-color: #ffffff;
        }

        .login {
            background-color: #4B2E2B;
            max-width: 480px;
            padding: 40px 48px;
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
            margin-bottom: 20px;
        }

        .login_info {
            margin-bottom: 20px;
        }

        .login_info div {
            display: flex;
            gap: 15px;
            padding: 6px 0;
            align-items: center;
        }

        .login_info label {
            width: 38%;
            font-size: 15px;
            font-weight: 600;
            color: #C08552;
            line-height: 1.2;
        }

        .login_info input {
            background-color: #FFF8F0;
            width: 62%;
            height: 38px;
            padding: 10px;
            border-radius: 8px;
            border: none;
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
        <form target="_self" method="POST">
            <div class="error"><?php echo $error_message; ?></div>

            <div class="login_info">
                <div>
                    <label>Username:</label>
                    <input type="text" name="username" value="<?php echo $keep_username; ?>" required>
                </div>

                <div>
                    <label>Password:</label>
                    <input type="password" name="password" required>
                </div>

                <div>
                    <label>Name:</label>
                    <input type="text" name="name" value="<?php echo $keep_name; ?>" required>
                </div>

                <div>
                    <label>Email:</label>
                    <input type="email" name="email" value="<?php echo $keep_email; ?>" required>
                </div>

                <div>
                    <label>Phone Number:</label>
                    <input type="text" name="phone" value="<?php echo $keep_phone; ?>" required>
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
</body>

</html>