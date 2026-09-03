<?php 
session_start(); 
 
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
$full_name = ""; 
$email = ""; 
$phone = ""; 
 
if ($_SERVER["REQUEST_METHOD"] == "POST") { 
    $full_name = trim($_POST["full_name"]); 
    $email = trim($_POST["email"]); 
    $phone = trim($_POST["phone"]); 
    $new_password = $_POST["password"]; 
    $confirm_password = $_POST["confirm_password"]; 
 
    if (empty($full_name) || empty($email) || empty($phone) || empty($new_password) || empty($confirm_password)) { 
        $error_message = "Please fill in all required fields."; 
    } else if (!ctype_digit($phone)) { 
        $error_message = "Phone number must contain numbers only."; 
    } else if ($new_password != $confirm_password) { 
        $error_message = "Passwords do not match."; 
    } else if (strlen($new_password) < 6) { 
        $error_message = "Password must contain at least 6 characters."; 
    } else { 
        $safe_name = mysqli_real_escape_string($conn, $full_name); 
        $safe_email = mysqli_real_escape_string($conn, $email); 
        $safe_phone = mysqli_real_escape_string($conn, $phone); 
        $safe_password = mysqli_real_escape_string($conn, $new_password); 
 
        $check_query = "SELECT * FROM users WHERE email = '$safe_email'"; 
        $check_result = mysqli_query($conn, $check_query); 
 
        if (mysqli_num_rows($check_result) > 0) { 
            $error_message = "This email is already registered."; 
        } else { 
            $insert_query = "INSERT INTO users (full_name, email, phone, password) 
                             VALUES ('$safe_name', '$safe_email', '$safe_phone', '$safe_password')"; 
 
            if (mysqli_query($conn, $insert_query)) { 
                header("Location: index.php?success=Account created successfully. Please login."); 
                exit(); 
            } else { 
                $error_message = "Unable to create account. Please try again."; 
            } 
        } 
    } 
} 
?> 

<!DOCTYPE html> 
<html lang="en"> 

<head> 
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 

    <title>Register | Pop & Reveal</title> 

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="style.css"> 
</head> 

<body class="auth-body register-body"> 

    <div class="simple-auth-card"> 

        <a class="brand dark-brand" href="index.php"> 
            <span class="brand-box">?</span> 
            <span>POP & REVEAL</span> 
        </a> 
 
        <p class="eyebrow">JOIN THE CLUB</p> 

        <h1>Create your collector account</h1> 

        <p class="muted">
            Start with the basic information required by the assignment.
        </p> 
 
        <?php if ($error_message != "") { ?> 

            <div class="message error-message">
                <?php echo $error_message; ?>
            </div> 

        <?php } ?> 
 
        <form method="POST" action="register.php" class="two-column-form" autocomplete="off"> 

            <div class="form-group"> 

                <label for="full_name">Full Name</label> 

                <input 
                    id="full_name" 
                    type="text" 
                    name="full_name" 
                    value="<?php echo htmlspecialchars($full_name); ?>" 
                    autocomplete="off" 
                    required
                > 

            </div> 
 
            <div class="form-group"> 

                <label for="email">Email Address</label> 

                <input 
                    id="email" 
                    type="email" 
                    name="email" 
                    value="<?php echo htmlspecialchars($email); ?>" 
                    autocomplete="off" 
                    required
                > 

            </div> 
 
            <div class="form-group"> 

                <label for="phone">Phone Number</label> 

                <input 
                    id="phone" 
                    type="tel" 
                    name="phone" 
                    value="<?php echo htmlspecialchars($phone); ?>" 
                    inputmode="numeric" 
                    pattern="[0-9]+" 
                    title="Please enter numbers only." 
                    autocomplete="off" 
                    required
                > 

            </div> 
 
            <div class="form-group"> 

                <label for="password">Password</label> 

                <div class="password-box">

                    <input 
                        id="password" 
                        type="password" 
                        name="password" 
                        minlength="6" 
                        autocomplete="new-password" 
                        required
                    >

                    <i 
                        class="fa-solid fa-eye password-eye" 
                        onclick="showPassword('password', this)">
                    </i>

                </div>

            </div> 
 
            <div class="form-group"> 

                <label for="confirm_password">Confirm Password</label> 

                <div class="password-box">

                    <input 
                        id="confirm_password" 
                        type="password" 
                        name="confirm_password" 
                        minlength="6" 
                        autocomplete="new-password" 
                        required
                    >

                    <i 
                        class="fa-solid fa-eye password-eye" 
                        onclick="showPassword('confirm_password', this)">
                    </i>

                </div>

            </div> 
 
            <div class="form-group button-at-bottom"> 

                <button class="btn btn-primary full-button" type="submit">
                    Register Account
                </button> 

            </div> 

        </form> 
 
        <p class="form-switch">

            Already registered? 

            <a href="index.php">
                Back to login
            </a>

        </p> 

    </div>


    <script>

        function showPassword(inputId, eye) {

            var passwordInput = document.getElementById(inputId);

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