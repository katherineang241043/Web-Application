<?php
session_start();

// 如果已经登录就直接去后台
if (isset($_SESSION["email"])) {
    header("Location: dashboard.php");
    exit();
}

$servername = "localhost";
$username = "popmart_collector";
$password = "pop123";
$dbname = "popmart_collector";

$error_message = "";
$keep_email = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // 留存用户输入的email 
    if (!empty($_POST['email'])) {
        $keep_email = htmlspecialchars($_POST['email']);
    }

    // 空值校验
    if (empty($_POST["email"]) || empty($_POST["password"])) {
        $error_message = "Please enter both email and password.";
    } else {
        $email = $_POST["email"];
        $pword = $_POST["password"];

        // 登录验证
        $query = "SELECT * FROM `users` WHERE `Email` = '$email'";
        $data = $conn->query($query);

        if ($data->num_rows > 0) {
            $row = $data->fetch_assoc();

            // 直接明文密码比对
            if ($pword == $row['password']) {
                $_SESSION['email'] = $row['email'];
                $_SESSION['full_name'] = $row['username'];
                
                header("Location: dashboard.php");
                exit();
            } else {
                $error_message = "Incorrect password. Please try again.";
            }
        } else {
            $error_message = "No such user. Please register an account first.";
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
    <title>Login | Pop &amp; Reveal</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="auth-body">
    <div class="auth-layout">
        
      
        <section class="auth-art">
            <div class="mini-label">COLLECT • REVEAL • REPEAT</div>
            <h1>Your next surprise is waiting.</h1>
            <p>Sign in, tell us your favourite series, and build your own digital blind box collection.</p>
            
            <!-- box-scene里面的 -->
            <div class="box-scene">
                <div class="floating-card card-one">LABUBU</div>
                <div class="big-box"><span>?</span></div>
                <div class="floating-card card-two">HIRONO</div>
            </div>
        </section>

        <section class="auth-panel">
            <div class="auth-form-wrap">
                
                <a class="brand dark-brand" href="index.php">
                    <span class="brand-box">?</span>
                    <span>POP & REVEAL</span>
                </a>
                
                <p class="eyebrow">MEMBER LOGIN</p>
                <h2>Welcome back!</h2>
                <p class="muted">Open a surprise and continue your collection.</p>

                <!-- 提示消息状态 -->
                <?php if (isset($_GET["warning"])) { ?>
                    <div class="message warning-message"><?php echo htmlspecialchars($_GET["warning"]); ?></div>
                <?php } ?>

                <?php if (isset($_GET["success"])) { ?>
                    <div class="message success-message"><?php echo htmlspecialchars($_GET["success"]); ?></div>
                <?php } ?>

                <!-- 登录错误提示 -->
                <?php if ($error_message !== "") { ?>
                    <div class="message error-message"><?php echo $error_message; ?></div>
                <?php } ?>


                <form method="POST" action="index.php">
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input id="email" type="email" name="email" value="<?php echo htmlspecialchars($keep_email); ?>" placeholder="name@example.com" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input id="password" type="password" name="password" placeholder="Enter your password" required>
                    </div>
                    
                    <button class="btn btn-primary full-button" type="submit">Login &amp; Start Collecting</button>
                </form>

                
                <p class="form-switch">New collector? <a href="register.php">Create an account</a></p>
            </div>
        </section>
        
    </div>
</body>
</html>