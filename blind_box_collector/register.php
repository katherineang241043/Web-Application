<?php
session_start();

$servername = "localhost";
$username = "popmart_collector";
$password = "pop123";
$dbname = "popmart_collector";

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if (empty($_POST["full_name"]) || empty($_POST["email"]) || empty($_POST["phone"]) || empty($_POST["password"]) || empty($_POST["confirm_password"])) {
        $error_message = "Please fill in all required fields.";
    } elseif ($_POST["password"] !== $_POST["confirm_password"]) {
        $error_message = "Passwords do not match.";
    } elseif (strlen($_POST["password"]) < 6) {
        $error_message = "Password must contain at least 6 characters.";
    } else {
        $fullname = $_POST["full_name"];
        $email = $_POST["email"];
        $phone = $_POST["phone"];
        $pword = $_POST["password"];

        // 检查邮箱是否已注册
        $query = "SELECT * FROM `users` WHERE `email` = '$email'";
        $data = $conn->query($query);

        if ($data->num_rows > 0) {
            $error_message = "This email is already registered.";
        } else {
            // 存密码到users表里
            $sql = "INSERT INTO `users` (`username`, `email`, `phone`, `password`) 
                    VALUES ('$fullname', '$email', '$phone', '$pword')";
                    
            if ($conn->query($sql) === TRUE) {
                header("Location: index.php?success=Account created successfully. Please login.");
                exit();
            } else {
                $error_message = "Unable to create account. Please try again.";
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
    <title>Register | Pop &amp; Reveal</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="auth-body register-body">
    <div class="simple-auth-card">
        <a class="brand dark-brand" href="index.php"><span class="brand-box">?</span><span>POP &amp; REVEAL</span></a>
        <p class="eyebrow">JOIN THE CLUB</p>
        <h1>Create your collector account</h1>
        <p class="muted">Start with the basic information required by the assignment.</p>

        <?php if ($error_message !== "") { ?>
            <div class="message error-message"><?php echo $error_message; ?></div>
        <?php } ?>


        <form method="POST" action="register.php" class="two-column-form">
            <div class="form-group">
                <label for="full_name">Full Name</label>
                <input id="full_name" type="text" name="full_name" value="<?php echo isset($_POST['full_name']) ? htmlspecialchars($_POST['full_name']) : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email Address</label>
                <input id="email" type="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input id="phone" type="tel" name="phone" value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" minlength="6" required>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input id="confirm_password" type="password" name="confirm_password" minlength="6" required>
            </div>
            <div class="form-group button-at-bottom">
                <button class="btn btn-primary full-button" type="submit">Register Account</button>
            </div>
        </form>
        <p class="form-switch">Already registered? <a href="index.php">Back to login</a></p>
    </div>
</body>
</html>