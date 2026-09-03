<?php 
session_start(); 
 
if (isset($_SESSION["email"])) { 
    header("Location: dashboard.php"); 
    exit(); 
} 
 
$servername = "localhost"; 
$username = "popmart_collector"; 
$password = "pop123"; 
$dbname = "popmart_collector"; 
 
$conn = new mysqli($servername, $username, $password, $dbname); 
 
if ($conn->connect_error) { 
    die("Connection failed: " . $conn->connect_error); 
} 
 
mysqli_set_charset($conn, "utf8mb4"); 
 
$error_message = ""; 
$keep_email = ""; 
 
if ($_SERVER["REQUEST_METHOD"] == "POST") { 
    $keep_email = $_POST["email"]; 
    $login_password = $_POST["password"]; 
 
    if (empty($keep_email) || empty($login_password)) { 
        $error_message = "Please enter both email and password."; 
    } else { 
        $safe_email = mysqli_real_escape_string($conn, $keep_email); 
        $query = "SELECT * FROM users WHERE email = '$safe_email'"; 
        $result = mysqli_query($conn, $query); 
 
        if (mysqli_num_rows($result) > 0) { 
            $user = mysqli_fetch_assoc($result); 
 
            if ($login_password == $user["password"]) { 
                $_SESSION["user_id"] = $user["id"]; 
                $_SESSION["full_name"] = $user["full_name"]; 
                $_SESSION["email"] = $user["email"]; 
 
                header("Location: dashboard.php"); 
                exit(); 
            } else { 
                $error_message = "Incorrect password. Please try again."; 
            } 
        } else { 
            $error_message = "No such user. Please register an account first."; 
        } 
    } 
} 
?> 
<!DOCTYPE html> 
<html lang="en"> 
<head> 
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>Login | Pop & Reveal</title> 

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="style.css?v=2"> 
</head> 
<body class="auth-body"> 
    <div class="auth-layout"> 
        <section class="auth-art"> 
            <div class="mini-label">COLLECT • REVEAL • REPEAT</div> 
            <h1>Your next surprise is waiting.</h1> 
            <p>Sign in, tell us your favourite series, and build your own digital blind box collection.</p> 
 
            <div class="box-scene"> 
                <div class="floating-card card-one">LABUBU</div> 
                <div class="big-box"><span>?</span></div> 
                <div class="floating-card card-two">SECRET</div> 
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
 
                <?php if (isset($_GET["warning"])) { ?> 
                    <div class="message warning-message"><?php echo htmlspecialchars($_GET["warning"]); ?></div> 
                <?php } ?> 
 
                <?php if (isset($_GET["success"])) { ?> 
                    <div class="message success-message"><?php echo htmlspecialchars($_GET["success"]); ?></div> 
                <?php } ?> 
 
                <?php if ($error_message != "") { ?> 
                    <div class="message error-message"><?php echo $error_message; ?></div> 
                <?php } ?> 
 
                <form method="POST" action="index.php"> 
                    <div class="form-group"> 
                        <label for="email">Email Address</label> 
                        <input id="email" type="email" name="email" value="<?php echo htmlspecialchars($keep_email); ?>" placeholder="name@example.com" required> 
                    </div> 
 
                    <div class="form-group"> 
                        <label for="password">Password</label> 

                        <div class="password-box">
                            <input id="password" type="password" name="password" placeholder="Enter your password" required>

                            <i class="fa-solid fa-eye password-eye" onclick="showPassword()"></i>
                        </div>
                    </div> 
 
                    <button class="btn btn-primary full-button" type="submit">Login & Start Collecting</button> 
                </form> 
 
                <p class="form-switch">New collector? <a href="register.php">Create an account</a></p> 
            </div> 
        </section> 
    </div> 


    <script>

        function showPassword() {

            var passwordInput = document.getElementById("password");
            var eye = document.querySelector(".password-eye");

            if (passwordInput.type == "password") {

                passwordInput.type = "text";

                eye.classList.remove("fa-eye");
                eye.classList.add("fa-eye-slash");

            } else {

                passwordInput.type = "password";

                eye.classList.remove("fa-eye-slash");
                eye.classList.add("fa-eye");

            }

        }

    </script>

</body> 
</html>