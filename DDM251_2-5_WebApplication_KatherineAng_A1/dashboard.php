<?php
session_start();

if (!isset($_SESSION["email"])) {
    header("Location: login.php?error=Please login first.");
    exit();
}

$conn = new mysqli("localhost", "katshop", "katshop_123", "katshop");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$total_orders = $conn->query("SELECT COUNT(*) FROM orders")->fetch_column() ?? 0;

$unsold_products_count = $conn->query("SELECT COUNT(*) FROM products WHERE ProductName NOT IN (SELECT DISTINCT ProductName FROM order_details WHERE ProductName > '')")->fetch_column() ?? 0;

$unpurchased_customers_count = $conn->query("SELECT COUNT(*) FROM customers WHERE UserName NOT IN (SELECT DISTINCT UserName FROM orders WHERE UserName > '')")->fetch_column() ?? 0;

$sql_top = "SELECT ProductName, SUM(Quantity) AS total_qty FROM order_details WHERE ProductName > '' GROUP BY ProductName ORDER BY total_qty DESC LIMIT 3";
$top_products = $conn->query($sql_top)->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Kat Shop</title>
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

        .welcome-text {
            color: #8C6239;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 4px;
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

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 20px;
            margin-bottom: 35px;
        }

        .card {
            background-color: #FFF8F0;
            border: 1px solid #C08552;
            border-radius: 18px;
            padding: 25px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .card-info h3 {
            font-size: 14px;
            font-weight: 600;
            color: #4A3331;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .card-info .number {
            font-size: 32px;
            font-weight: 900;
            color: #C08552;
        }

        .card-icon {
            font-size: 40px;
            color: #C08552;
            background-color: #E6DFD7;
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .top-section {
            background-color: #FFF8F0;
            border-radius: 18px;
            padding: 30px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            max-width: 900px;
            border: 1px solid #C08552;
        }

        .top-section h2 {
            font-size: 22px;
            font-weight: 800;
            color: #4A3331;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .top-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .top-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px 20px;
            background-color: #ffffff;
            border-radius: 10px;
            margin-bottom: 12px;
            border: 1px solid rgba(192, 133, 82, 0.3);
        }

        .top-item:last-child {
            margin-bottom: 0;
        }

        .rank-badge {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: #C08552;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 15px;
            margin-right: 15px;
        }

        .rank-1 { background-color: #FFD700; color: #4A3331; }
        .rank-2 { background-color: #C0C0C0; color: #4A3331; }
        .rank-3 { background-color: #CD7F32; color: #ffffff; }

        .item-details {
            display: flex;
            align-items: center;
            flex-grow: 1;
        }

        .item-name {
            font-weight: 700;
            color: #4A3331;
            font-size: 16px;
        }

        .item-sales {
            font-weight: 700;
            color: #C08552;
            background-color: #FFF8F0;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 14px;
            border: 1px solid #C08552;
        }

        .no-data {
            color: #888;
            font-style: italic;
        }
    </style>
</head>

<body>
    <div class="max-width">
        <div class="sidebar">
            <div class="sidebar_header">Kat Shop</div>

            <a href="dashboard.php" class="sidebar_menu active"><i class="fa-solid fa-border-all"></i>Dashboard</a>
            <a href="customer.php" class="sidebar_menu"><i class="fa-solid fa-user"></i>Customers</a>
            <a href="products.php" class="sidebar_menu"><i class="fa-solid fa-cheese"></i>Products</a>
            <a href="order.php" class="sidebar_menu"><i class="fa-solid fa-cart-shopping"></i>Orders</a>
            <a href="logout.php" class="sidebar_menu" onclick="return confirm('Are you sure you want to log out?');">
            <i class="fa-solid fa-door-open"></i>Sign Out</a>
</a>
        </div>

        <div class="main-content">
            <div class="welcome-text">
                Welcome back, <?php echo htmlspecialchars($_SESSION['email']); ?>! 👋
            </div>
            <h1>Dashboard</h1>

            <div class="stats-grid">
                <div class="card">
                    <div class="card-info">
                        <h3>Total Orders</h3>
                        <div class="number"><?php echo $total_orders; ?></div>
                    </div>
                    <div class="card-icon">
                        <i class="fa-solid fa-cart-shopping"></i>
                    </div>
                </div>

                <div class="card">
                    <div class="card-info">
                        <h3>Unsold Products</h3>
                        <div class="number"><?php echo $unsold_products_count; ?></div>
                    </div>
                    <div class="card-icon">
                        <i class="fa-solid fa-box-open"></i>
                    </div>
                </div>

                <div class="card">
                    <div class="card-info">
                        <h3>Inactive Customers</h3>
                        <div class="number"><?php echo $unpurchased_customers_count; ?></div>
                    </div>
                    <div class="card-icon">
                        <i class="fa-solid fa-user-slash"></i>
                    </div>
                </div>
            </div>

            <div class="top-section">
                <h2><i class="fa-solid fa-trophy" style="color: #FFD700;"></i> Top 3 Best Selling Items</h2>
                
                <ul class="top-list">
                    <?php if (!empty($top_products)): ?>
                        <?php 
                        $rank = 1;
                        foreach ($top_products as $item): 
                            $badge_class = "rank-" . $rank;
                        ?>
                            <li class="top-item">
                                <div class="item-details">
                                    <div class="rank-badge <?php echo $badge_class; ?>"><?php echo $rank; ?></div>
                                    <div class="item-name"><?php echo htmlspecialchars($item['ProductName']); ?></div>
                                </div>
                                <div class="item-sales"><?php echo $item['total_qty']; ?> sold</div>
                            </li>
                        <?php 
                            $rank++;
                        endforeach; 
                        ?>
                    <?php else: ?>
                        <li class="top-item no-data">No sales data available yet.</li>
                    <?php endif; ?>
                </ul>
            </div>

        </div>
    </div>
</body>

</html>