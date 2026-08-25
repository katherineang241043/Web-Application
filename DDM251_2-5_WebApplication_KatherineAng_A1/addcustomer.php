<?php
session_start();
$conn = mysqli_connect("localhost", "katshop", "katshop_123", "katshop");


$id_res = mysqli_query($conn, "SELECT MAX(CAST(CustomerID AS UNSIGNED)) AS max_id FROM customers");
$id_row = mysqli_fetch_assoc($id_res);
$auto_customer_id = ($id_row['max_id']) ? $id_row['max_id'] + 1 : 1001;


if (isset($_POST['username'])) {
    $uname = $_POST['username'];
    $pword = $_POST['password'];
    $name  = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phonenumber'];

    $sql = "INSERT INTO customers (CustomerID, UserName, Password, Name, Email, PhoneNumber) 
            VALUES ('$auto_customer_id', '$uname', '$pword', '$name', '$email', '$phone')";

    if (mysqli_query($conn, $sql)) {
        header("Location: customer.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Customer</title>
    <script src="https://kit.fontawesome.com/1619a0e9db.js" crossorigin="anonymous"></script>
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
            border: none;
            font-family: "Poppins", sans-serif;
            font-size: 14px;
            color: #4B2E2B;
        }

        .form-group input[readonly] {
            background-color: #e9e0d6;
            color: #7a6865;
            cursor: not-allowed;
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
            border: none;
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
        }
    </style>
</head>

<body>
    <div class="max-width">
        <div class="sidebar">
            <div class="sidebar_header">Kat Shop</div>

            <a href="dashboard.php" class="sidebar_menu"><i class="fa-solid fa-border-all"></i>Dashboard</a>

            <a href="customer.php" class="sidebar_menu active"><i class="fa-solid fa-user"></i>Customers</a>

            <a href="product.php" class="sidebar_menu"><i class="fa-solid fa-cheese"></i>Products</a>
            
            <a href="order.php" class="sidebar_menu"><i class="fa-solid fa-cart-shopping"></i>Orders</a>

            <a href="logout.php" class="sidebar_menu" onclick="return confirm('Are you sure you want to log out?');">
            <i class="fa-solid fa-door-open"></i>Sign Out</a>
        </div>

        <div class="main-content">
            <h1>Add Customer</h1>

            <div class="form-container">
                <form method="POST" autocomplete="off">
                    
                    <div class="form-group">
                        <label>CustomerID:</label>
                        <input type="text" name="customerID" value="<?php echo $auto_customer_id; ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label>Username:</label>
                        <input type="text" name="username" required autocomplete="off">
                    </div>

                    <div class="form-group">
                        <label>Password:</label>
                        <div style="position: relative; width: 75%; padding: 0; margin: 0;">
                            <input type="password" id="pword" name="password" required autocomplete="new-password" style="width: 100%; padding-right: 35px;">
                            <i class="fa-regular fa-eye" onclick="toggleVisibility('pword', this)" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #4B2E2B;"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Name:</label>
                        <input type="text" name="name" required autocomplete="off">
                    </div>

                    <div class="form-group">
                        <label>Email:</label>
                        <input type="email" name="email" required autocomplete="off">
                    </div>

                    <div class="form-group">
                        <label>Phone Number:</label>
                        <input type="text" name="phonenumber" required autocomplete="off">
                    </div>

                    <div class="button-group">
                        <a href="customer.php" class="btn btn-back">Cancel</a>
                        <input type="submit" class="btn btn-submit" value="Add Customer">
                    </div>

                </form>
            </div>
        </div>
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