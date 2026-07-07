<?php
session_start();

$servername = "localhost";
$username = "katshop";
$password = "katshop_123";
$dbname = "katshop";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$customer_id = "";
$username_val = "";
$name_val = "";
$password_val = "";
$email_val = "";
$phone_val = "";

if (isset($_POST['CustomerID'])) {
    $customer_id = $_POST['CustomerID'];
    
    $query = "SELECT * FROM customers WHERE CustomerID = '$customer_id'";
    $result = mysqli_query($conn, $query);
    
    if ($row = mysqli_fetch_assoc($result)) {
        $username_val = $row['UserName'];
        $name_val = $row['Name'];
        $password_val = $row['Password'];
        $email_val = $row['Email'];
        $phone_val = $row['PhoneNumber'];
    }
} else {
    header("Location: customer.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Customer</title>
    <script src="https://kit.fontawesome.com/1619a0e9db.js" crossorigin="anonymous"></script>
</head>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

    * {
        margin: 0;
        box-sizing: border-box;
    }

    body {
        font-family: "Poppins", sans-serif;
        background-color: #ffffff;
        font-size: 16px;
    }

    h1 {
        font-size: 40px;
        font-weight: 900;
        color: #C08552;
        margin-bottom: 25px;
        text-align: left;
    }

    .sidebar {
        background-color: #4B2E2B;
        color: #FFF8F0;
        font-weight: 600;
        height: 94vh;
        padding: 10px;
        border-radius: 15px;
        text-align: left;
        position: fixed;
        top: 20px;
        left: 25px;
        display: flex;
        flex-direction: column;
    }

    .sidebar_header {
        padding: 30px 0px 30px 0px;
        font-size: 23px;
        text-align: center;
    }

    .sidebar_menu {
        display: flex;
        align-items: center;
        padding: 20px 28px;
        text-decoration: none !important;
        color: #FFF8F0;
    }

    .sidebar_menu.active {
        color: #83dae7;
    }

    .sidebar_menu:hover {
        color: #83dae7;
        cursor: pointer;
    }

    .sidebar i {
        font-size: 20px;
        margin-right: 10px;
    }

    .main-content {
        margin-left: 290px;
        padding: 40px;
    }

    .form-container {
        background-color: #4B2E2B; 
        max-width: 600px;
        padding: 40px;
        border-radius: 20px;
        box-shadow: 0 8px 16px rgba(0,0,0,0.1);
    }

    .form-group {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
        gap: 20px;
    }

    .form-group label {
        width: 25%;
        font-size: 16px;
        font-weight: 600;
        color: #C08552;
        text-align: left;
    }

    .form-group input {
        background-color: #FFF8F0;
        width: 75%;
        height: 42px;
        padding: 10px 15px;
        border-radius: 8px;
        border: 2px solid transparent;
        font-family: "Poppins", sans-serif;
        font-size: 14px;
        color: #4B2E2B;
    }

    .form-group input:focus {
        outline: 2px solid #83dae7;
    }

    .button-group {
        display: flex;
        justify-content: flex-end;
        gap: 15px;
        margin-top: 30px;
    }

    .btn {
        padding: 12px 30px;
        font-size: 15px;
        font-weight: 600;
        border: 2px solid transparent; 
        border-radius: 8px;
        cursor: pointer;
        font-family: "Poppins", sans-serif;
        text-decoration: none;
        transition: 0.2s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .btn-submit {
        background-color: #FFF8F0;
        color: #4B2E2B;
        width: 40%;
    }

    .btn-submit:hover {
        background-color: #C08552;
        color: #FFF8F0;
    }

    .btn-back {
        background-color: transparent;
        border: 2px solid #C08552;
        color: #C08552;
    }

    .btn-back:hover {
        background-color: #C08552;
        color: #FFF8F0;
        border: 2px solid #C08552;

    .error-msg {
        color: #ff4d4d;
        margin-bottom: 15px;
        font-weight: 600;
        text-align: left;
    }
</style>

<body>
    <div class="max-width">
        <div class="sidebar">
            <div class="sidebar_header">Kat Shop</div>
            <a href="welcome.php" class="sidebar_menu"><i class="fa-solid fa-border-all"></i>Dashboard</a>
            <a href="customer.php" class="sidebar_menu active"><i class="fa-solid fa-user"></i>Customers</a>
            <a href="product.php" class="sidebar_menu"><i class="fa-solid fa-cheese"></i>Products</a>
            <div class="sidebar_menu"><i class="fa-solid fa-door-open"></i>Sign Out</div>
        </div>

        <div class="main-content">
            <h1>Edit Customer</h1>

            <div class="form-container">
                <?php
                if (isset($_GET['error'])){
                    echo '<div class="error-msg">' . $_GET['error'] . '</div>';
                }
                ?>

                <form action="runeditcustomer.php" method="POST" onsubmit="return validatePassword()">
                    
                    <input type="hidden" name="CustomerID" value="<?php echo $customer_id; ?>">

                    <div class="form-group">
                        <label>Username:</label>
                        <input type="text" name="username" value="<?php echo $username_val; ?>" required autocomplete="off">
                    </div>

                    <div class="form-group">
                        <label>Password:</label>
                        <input type="password" id="password" name="password" value="<?php echo $password_val; ?>" required minlength="6" autocomplete="off">
                    </div>

                    <div class="form-group">
                        <label>Confirm Password:</label>
                        <input type="password" id="confirmpassword" name="confirmpassword" value="<?php echo $password_val; ?>" required minlength="6" autocomplete="off">
                    </div>

                    <div class="form-group">
                        <label>Name:</label>
                        <input type="text" name="name" value="<?php echo $name_val; ?>" required autocomplete="off">
                    </div>

                    <div class="form-group">
                        <label>Email:</label>
                        <input type="email" name="email" value="<?php echo $email_val; ?>" required autocomplete="off">
                    </div>

                    <div class="form-group">
                        <label>Phone Number:</label>
                        <input type="text" name="phonenumber" value="<?php echo $phone_val; ?>" required autocomplete="off">
                    </div>

                    <div class="button-group">
                        <a href="customer.php" class="btn btn-back">Cancel</a>
                        <input type="submit" class="btn btn-submit" value="Save Changes">
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script>
    function validatePassword() {
        var password = document.getElementById("password").value;
        var confirmPassword = document.getElementById("confirmpassword").value;
        
        if (password !== confirmPassword) {
            alert("Passwords do not match! Please check again.");
            return false;
        }
        return true;
    }
    </script>
</body>

</html>