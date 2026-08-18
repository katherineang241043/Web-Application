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

$order_id = isset($_GET['OrderID']) ? $_GET['OrderID'] : 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details - Kat Shop</title>
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

    .total-row {
        font-weight: 800;
        font-size: 16px;
        background-color: #f7ede2;
    }

    .total-row td {
        border-bottom: none;
    }

    .bottom-buttons {
        text-align: left;
        margin-top: 25px;
        display: flex;
        gap: 15px;
    }

    .btn-main {
        display: inline-block;
        background-color: #4B2E2B;
        color: #FFF8F0;
        text-decoration: none;
        padding: 12px 28px;
        font-weight: 600;
        border-radius: 8px;
        transition: 0.2s;
        border: none;
        cursor: pointer;
    }

    .btn-main:hover {
        background-color: #C08552;
        color: #FFF8F0;
    }

    .btn-edit {
        background-color: #C08552;
        color: #FFF8F0;
    }

    .btn-edit:hover {
        background-color: #a46f40;
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
            <h1>Order Details</h1>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th width="90">Order ID</th>
                            <th width="130">Detail ID</th>
                            <th width="140">Username</th>
                            <th>Product</th>
                            <th width="180">Order Date</th>
                            <th width="90" style="text-align: center;">Quantity</th>
                            <th width="120" style="text-align: right;">Price ($)</th>
                            <th width="130" style="text-align: right;">Total Price ($)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT od.*, o.UserName, o.OrderDate 
                                  FROM order_details od 
                                  JOIN orders o ON od.OrderID = o.OrderID 
                                  WHERE od.OrderID = '$order_id'";
                        
                        $result = mysqli_query($conn, $query);
                        $grand_total = 0;

                        if ($result && mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $quantity = $row['Quantity'];
                                $price = $row['ProductPrice'];
                                $subtotal = $quantity * $price;
                                $grand_total += $subtotal;
                        ?>
                                <tr>
                                    <td><?php echo $row['OrderID']; ?></td>
                                    <td><?php echo $row['OrderDetailID']; ?></td>
                                    <td><?php echo $row['UserName']; ?></td>
                                    <td><?php echo $row['ProductName']; ?></td>
                                    <td><?php echo $row['OrderDate']; ?></td>
                                    <td style="text-align: center;"><?php echo $quantity; ?></td>
                                    <td style="text-align: right;"><?php echo number_format($price, 2); ?></td>
                                    <td style="text-align: right;"><?php echo number_format($subtotal, 2); ?></td>
                                </tr>
                        <?php
                            }
                        ?>
                            <tr class="total-row">
                                <td colspan="7" style="text-align: right;">Total:</td>
                                <td style="text-align: right; color: #d20000;"><?php echo number_format($grand_total, 2); ?></td>
                            </tr>
                        <?php
                        } else {
                            echo '<tr><td colspan="8" style="text-align:center;">No order details found for this order.</td></tr>';
                        }
                        mysqli_close($conn);
                        ?>
                    </tbody>
                </table>
            </div>

            <div class="bottom-buttons">
                <a href="order.php" class="btn-main"><i class="fa-solid fa-arrow-left" style="margin-right: 8px;"></i>BACK TO ORDER LIST</a>
                <a href="editorder.php?OrderID=<?php echo $order_id; ?>" class="btn-main btn-edit"><i class="fa-solid fa-pen-to-square" style="margin-right: 8px;"></i>EDIT</a>
            </div>
        </div>
    </div>
</body>

</html>