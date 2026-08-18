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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Management</title>
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

    .table-container {
        width: 100%;
        overflow-x: auto;
        margin-bottom: 25px;
    }

    table {
        width: 100%;
        max-width: 1200px;
        border-collapse: collapse;
        background-color: #FFF8F0;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }

    th, td {
        padding: 14px 20px;
        text-align: left;
        vertical-align: middle;
    }

    th {
        background-color: #4B2E2B; 
        color: #C08552; 
        font-weight: 700;
        font-size: 15px;
        letter-spacing: 0.5px;
    }

    td {
        border-bottom: 1px solid #E6DFD7; 
        color: #4B2E2B;
        font-size: 14px;
    }

    tr:last-child td {
        border-bottom: none;
    }

    .action-group {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        white-space: nowrap;
    }

    .btn-action {
        padding: 6px 16px;
        border: none;
        border-radius: 6px;
        font-family: "Poppins", sans-serif;
        font-weight: 600;
        font-size: 13px;
        cursor: pointer;
        transition: 0.2s;
        text-decoration: none;
        display: inline-block;
        line-height: 1.5;
    }

    .btn-read {
        background-color: #007bff;
        color: #ffffff;
    }

    .btn-read:hover {
        background-color: #0056b3;
    }

    .btn-edit {
        background-color: #C08552;
        color: #ffffff;
    }

    .btn-edit:hover {
        background-color: #a46f40;
    }

    .btn-delete {
        background-color: #d20000;
        color: #ffffff;
    }

    .btn-delete:hover {
        background-color: #cc0000;
    }

    .bottom-buttons {
        text-align: left;
        margin-top: 20px;
    }

    .btn-main {
        display: inline-block;
        background-color: #4B2E2B;
        color: #FFF8F0;
        text-decoration: none;
        padding: 12px 30px;
        font-weight: 600;
        border-radius: 8px;
        margin-right: 15px;
        transition: 0.2s;
    }

    .btn-main:hover {
        background-color: #C08552;
        color: #FFF8F0;
    }
</style>

<body>
    <div class="max-width">
        <div class="sidebar">
            <div class="sidebar_header">Kat Shop</div>

            <a href="dashboard.php" class="sidebar_menu"><i class="fa-solid fa-border-all"></i>Dashboard</a>

            <a href="customer.php" class="sidebar_menu"><i class="fa-solid fa-user"></i>Customers</a>

            <a href="products.php" class="sidebar_menu"><i class="fa-solid fa-cheese"></i>Products</a>

            <a href="order.php" class="sidebar_menu active"><i class="fa-solid fa-cart-shopping"></i>Orders</a>

            <a href="logout.php" class="sidebar_menu" onclick="return confirm('Are you sure you want to log out?');">
            <i class="fa-solid fa-door-open"></i>Sign Out</a>
        </div>

        <div class="main-content">
            <h1>Order List</h1>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th width="60">ID</th>
                            <th width="160">UserName</th>
                            <th width="140">First Name</th>
                            <th width="140">Last Name</th>
                            <th>Order Date</th>
                            <th width="260" style="text-align: center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT * FROM orders";
                        $result = mysqli_query($conn, $query);

                        if ($result && mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                                <tr>
                                    <td><?php echo $row['OrderID']; ?></td>
                                    <td><?php echo $row['UserName']; ?></td>
                                    <td><?php echo $row['FirstName']; ?></td>
                                    <td><?php echo $row['LastName']; ?></td>
                                    <td><?php echo $row['OrderDate']; ?></td>
                                    <td>
                                        <div class="action-group">
                                            <a href="readorder.php?OrderID=<?php echo $row['OrderID']; ?>" class="btn-action btn-read">Read</a>
                                            <a href="editorder.php?OrderID=<?php echo $row['OrderID']; ?>" class="btn-action btn-edit">Edit</a>
                                            <a href="deleteorder.php?OrderID=<?php echo $row['OrderID']; ?>" class="btn-action btn-delete" onclick="return confirm('Are you sure you want to delete this order?')">Delete</a>
                                        </div>
                                    </td>
                                </tr>
                        <?php
                            }
                        } else {
                            echo '<tr><td colspan="6" style="text-align:center;">No orders found.</td></tr>';
                        }
                        mysqli_close($conn);
                        ?>
                    </tbody>
                </table>
            </div>

            <div class="bottom-buttons">
                <a href="addorder.php" class="btn-main"><i class="fa-solid fa-cart-plus" style="margin-right: 8px;"></i>Add Order</a>
            </div>
        </div>
    </div>
</body>

</html>