<?php
$servername = "localhost";
$username = "katshop";
$password = "katshop_123";
$dbname = "katshop";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome!</title>
    <script src="https://kit.fontawesome.com/1619a0e9db.js" crossorigin="anonymous"></script>
</head>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

    * {
        margin: 0;
        box-sizing: border-box;
    }

    body {
        text-align: center;
        font-family: "Poppins", sans-serif;
        background-color: #ffffff;
        font-size: 16px;
    }

    h1 {
        font-size: 50px;
        font-weight: 900;
        color: #C08552;
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
    }

    .sidebar_menu {
        padding: 20px 28px;
    }

    .sidebar_menu:hover{
        color: #83dae7;
    }

    .sidebar_header {
        padding: 30px 0px 30px 0px;
        font-size: 23px;
        text-align: center;
    }

    .sidebar i{
        font-size: 20px;
        margin-right: 10px;
    }

    section {
        padding: 30px;
    }

</style>

<body>
    <div class="max-width">
        <div class="sidebar">
            <div class="sidebar_header">Kat Shop</div>

            <div class="sidebar_menu" href=""><i class="fa-solid fa-border-all"></i>Dashboard</div>

            <div class="sidebar_menu" href="customer.php"><i class="fa-solid fa-user"></i>Customers</div>

            <div class="sidebar_menu" href=""><i class="fa-solid fa-cheese"></i>Products</div>

            <div class="sidebar_menu" href=""><i class="fa-solid fa-door-open"></i>Sign Out</div>
        </div>

        <section><h1>Welcome</h1></section>

    </div>
</body>

</html>