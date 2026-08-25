<?php
session_start();
if (!isset($_SESSION["email"])) {
    header("Location: login.php?error=Please login first.");
    exit();
}

$conn = mysqli_connect("localhost", "katshop", "katshop_123", "katshop");


$id_res = mysqli_query($conn, "SELECT MAX(CAST(OrderID AS UNSIGNED)) AS max_id FROM orders");
$id_row = mysqli_fetch_array($id_res);
$auto_order_id = ($id_row['max_id']) ? $id_row['max_id'] + 1 : 1001;


$users_result = mysqli_query($conn, "SELECT * FROM customers");
$products_result = mysqli_query($conn, "SELECT * FROM products");


$products = array();
while ($row = mysqli_fetch_array($products_result)) {
    $products[] = $row;
}


if (isset($_POST['username'])) {
    $username = $_POST['username'];
    $p_ids = $_POST['product_id'];
    $qtys = $_POST['quantity'];


    $user_res = mysqli_query($conn, "SELECT Name FROM customers WHERE UserName = '$username'");
    $user_data = mysqli_fetch_array($user_res);
    $name_parts = explode(" ", $user_data['Name'], 2);
    $fn = $name_parts[0];
    $ln = isset($name_parts[1]) ? $name_parts[1] : '';

    $today = date("Y-m-d H:i:s");


    $sql1 = "INSERT INTO orders (OrderID, UserName, FirstName, LastName, OrderDate) VALUES ('$auto_order_id', '$username', '$fn', '$ln', '$today')";
    mysqli_query($conn, $sql1);

    for ($i = 0; $i < count($p_ids); $i++) {
        $pid = $p_ids[$i];
        $qty = $qtys[$i];

        if (!empty($pid) && !empty($qty)) {
            $p_res = mysqli_query($conn, "SELECT ProductName, Price FROM products WHERE ProductID = '$pid'");
            $p_data = mysqli_fetch_array($p_res);

            $pname = $p_data['ProductName'];
            $price = $p_data['Price'];

            $sql2 = "INSERT INTO order_details (OrderID, ProductName, Quantity, ProductPrice) VALUES ('$auto_order_id', '$pname', '$qty', '$price')";
            mysqli_query($conn, $sql2);
        }
    }

    header("Location: order.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Order - Kat Shop</title>
    <script src="https://kit.fontawesome.com/1619a0e9db.js" crossorigin="anonymous"></script>
</head>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;800;1,900&display=swap');

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
        background-color: #FFF8F0;
        padding: 30px;
        border-radius: 15px;
        max-width: 900px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }

    .form-group {
        margin-bottom: 20px;
    }

    label {
        font-weight: 700;
        color: #4B2E2B;
    }

    select, input[readonly] {
        padding: 10px 15px;
        border: 1px solid #C08552;
        border-radius: 8px;
        background-color: #ffffff;
        color: #4B2E2B;
        font-family: "Poppins", sans-serif;
        font-size: 15px;
        outline: none;
    }

    input[readonly] {
        background-color: #e9e0d6;
        color: #7a6865;
        cursor: not-allowed;
    }

    .product-row {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 15px;
        background-color: #ffffff;
        padding: 15px;
        border-radius: 10px;
        border: 1px solid #E6DFD7;
    }

    .btn-action-group {
        margin-top: 35px;
        margin-bottom: 15px;
        display: flex;
        gap: 12px;
    }

    .btn-btn {
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        font-family: "Poppins", sans-serif;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: 0.2s;
        text-decoration: none;
        display: inline-block;
    }

    .btn-add {
        background-color: #1e8233;
        color: #FFF8F0;
    }

    .btn-add:hover {
        background-color: #2f9a46;
    }

    .btn-delete-item {
        background-color: #cc0000;
        color: white;
    }

    .btn-delete-item:hover {
        background-color: #d52f40;
    }

    .btn-submit {
        background-color: #4B2E2B;
        color: #FFF8F0;
        padding: 12px 30px;
        font-size: 16px;
    }

    .btn-submit:hover {
        background-color: #E6DFD7;
    }

    .btn-back {
        background-color: transparent;
        border: 1px solid #C08552;
        color: #C08552;
        padding: 12px 30px;
        font-size: 16px;
        font-weight: 700;
    }

    .btn-back:hover {
        background-color: rgba(192, 133, 82, 0.5);
        border-color: transparent;
    }

    .custom-hr {
        border: 0;
        border-top: 1px solid #E6DFD7;
        margin: 20px 0 20px 0;
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
            <h1>Create Order</h1>

            <div class="form-container">
                <form action="" method="POST">

                    <div class="form-group">
                        <label for="order_id" style="display:inline-block; width: 100px;">OrderID:</label>
                        <input type="text" id="order_id" name="order_id" value="<?php echo $auto_order_id; ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label for="username" style="display:inline-block; width: 100px;">Username:</label>
                        <select name="username" id="username" required>
                            <option value=""><-- Selected Username --></option>
                            <?php while ($u = mysqli_fetch_array($users_result)) { ?>
                                <option value="<?php echo $u['UserName']; ?>"><?php echo $u['UserName']; ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div id="product-container">
                        <div class="product-row">
                            <label>Product:</label>
                            <select name="product_id[]" required>
                                <option value=""><-- Selected Product --></option>
                                <?php foreach ($products as $p) { ?>
                                    <option value="<?php echo $p['ProductID']; ?>">
                                        <?php echo $p['ProductName'] . " (RM " . number_format($p['Price'], 2) . ")"; ?>
                                    </option>
                                <?php } ?>
                            </select>

                            <label style="margin-left: 10px;">Quantity:</label>
                            <select name="quantity[]" required>
                                <option value=""><-- Selected Quantity --></option>
                                <?php for ($q = 1; $q <= 20; $q++) { ?>
                                    <option value="<?php echo $q; ?>"><?php echo $q; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="btn-action-group">
                        <button type="button" class="btn-btn btn-add" onclick="addProductRow()"><i class="fa-solid fa-plus"></i> ADD</button>
                        <button type="button" class="btn-btn btn-delete-item" onclick="removeProductRow()"><i class="fa-solid fa-trash"></i> DELETE PRODUCT</button>
                    </div>

                    <hr class="custom-hr">

                    <div style="display: flex; gap: 15px;">
                        <button type="submit" class="btn-btn btn-submit">SUBMIT</button>
                        <a href="order.php" class="btn-btn btn-back">BACK TO ORDER LIST</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function addProductRow() {
            const container = document.getElementById('product-container');
            const firstRow = container.querySelector('.product-row');
            const newRow = firstRow.cloneNode(true);
            
            const selects = newRow.querySelectorAll('select');
            selects.forEach(select => select.selectedIndex = 0);
            
            container.appendChild(newRow);
        }

        function removeProductRow() {
            const container = document.getElementById('product-container');
            const rows = container.querySelectorAll('.product-row');
            if (rows.length > 1) {
                container.removeChild(rows[rows.length - 1]);
            } else {
                alert('At least one product is required!');
            }
        }
    </script>
</body>

</html>