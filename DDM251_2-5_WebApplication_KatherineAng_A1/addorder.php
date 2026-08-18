<?php
session_start();

if (!isset($_SESSION["email"])) {
    header("Location: login.php?error=Please login first.");
    exit();
}

$conn = new mysqli("localhost", "katshop", "katshop_123", "katshop");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$users = $conn->query("SELECT * FROM customers")->fetch_all(MYSQLI_ASSOC);
$products = $conn->query("SELECT * FROM products")->fetch_all(MYSQLI_ASSOC);

$errors = [];
$selected_username = $_POST['username'] ?? '';
$selected_products = $_POST['product_id'] ?? [''];
$selected_quantities = $_POST['quantity'] ?? [''];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($selected_username)) $errors['username'] = "please seleted your username.";
    if (in_array('', $selected_products)) $errors['product'] = "please seleted your product.";
    if (in_array('', $selected_quantities)) $errors['quantity'] = "please seleted your quantity.";
    

    $valid_p = array_filter($selected_products);
    if (count($valid_p) !== count(array_unique($valid_p))) {
        $errors['duplicate'] = "product cannot be same.";
    }


    if (empty($errors)) {
        $u_stmt = $conn->prepare("SELECT Name FROM customers WHERE UserName = ?");
        $u_stmt->bind_param("s", $selected_username);
        $u_stmt->execute();
        $user_data = $u_stmt->get_result()->fetch_assoc();
        
        $name_parts = explode(" ", $user_data['Name'] ?? '', 2);
        $fn = $name_parts[0];
        $ln = $name_parts[1] ?? '';

        $order_date = date("Y-m-d H:i:s");
        $stmt = $conn->prepare("INSERT INTO orders (UserName, FirstName, LastName, OrderDate) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $selected_username, $fn, $ln, $order_date);

        if ($stmt->execute()) {
            $new_order_id = $conn->insert_id;
            $detail_stmt = $conn->prepare("INSERT INTO order_details (OrderID, ProductName, Quantity, ProductPrice) VALUES (?, ?, ?, ?)");
            $p_stmt = $conn->prepare("SELECT ProductName, Price FROM products WHERE ProductID = ?");

            for ($i = 0; $i < count($selected_products); $i++) {
                $p_id = $selected_products[$i];
                $qty = intval($selected_quantities[$i]);

                if (!empty($p_id) && $qty > 0) {
                    $p_stmt->bind_param("i", $p_id);
                    $p_stmt->execute();
                    $p_info = $p_stmt->get_result()->fetch_assoc();

                    $detail_stmt->bind_param("isid", $new_order_id, $p_info['ProductName'], $qty, $p_info['Price']);
                    $detail_stmt->execute();
                }
            }

            header("Location: order.php");
            exit();
        } else {
            $errors['db'] = "Error creating order: " . $conn->error;
        }
    }
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

    select {
        padding: 10px 15px;
        border: 1px solid #C08552;
        border-radius: 8px;
        background-color: #ffffff;
        color: #4B2E2B;
        font-family: "Poppins", sans-serif;
        font-size: 15px;
        outline: none;
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

    .error-text {
        color: #ff0000;
        font-size: 15px;
        font-weight: 500;
        margin-top: 8px;
        margin-bottom: 8px;
        display: block;
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
                <?php if (isset($errors['db'])): ?>
                    <div class="error-text"><?php echo $errors['db']; ?></div>
                <?php endif; ?>

                <form action="addorder.php" method="POST">
                    <div class="form-group">
                        <label for="username" style="display:inline-block; width: 100px;">Username:</label>
                        <select name="username" id="username">
                            <option value=""><-- Selected Username --></option>
                            <?php foreach ($users as $user): 
                                $uname = $user['UserName'] ?? $user['Username'] ?? '';
                                $selected = ($selected_username == $uname) ? 'selected' : '';
                            ?>
                                <option value="<?php echo htmlspecialchars($uname); ?>" <?php echo $selected; ?>>
                                    <?php echo htmlspecialchars($uname); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>


                        <?php if (isset($errors['username'])): ?>
                            <div class="error-text"><?php echo $errors['username']; ?></div>
                        <?php endif; ?>
                    </div>


                    <div id="product-container">
                        <?php 
                        $row_count = max(count($selected_products), 1);
                        for ($idx = 0; $idx < $row_count; $idx++): 
                            $cur_p = $selected_products[$idx] ?? '';
                            $cur_q = $selected_quantities[$idx] ?? '';
                        ?>
                            <div class="product-row">
                                <label>Product:</label>
                                <select name="product_id[]">
                                    <option value=""><-- Selected Product --></option>
                                    <?php foreach ($products as $prod): 
                                        $pid = $prod['ProductID'] ?? $prod['product_id'] ?? '';
                                        $pname = $prod['ProductName'] ?? $prod['Product Name'] ?? $prod['product_name'] ?? $prod['Name'] ?? 'Product';
                                        $pprice = $prod['Price'] ?? $prod['ProductPrice'] ?? $prod['product_price'] ?? 0;
                                        $p_selected = ($cur_p == $pid) ? 'selected' : '';
                                    ?>
                                        <option value="<?php echo $pid; ?>" <?php echo $p_selected; ?>>
                                            <?php echo htmlspecialchars($pname) . " (RM " . number_format($pprice, 2) . ")"; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>

                                <label style="margin-left: 10px;">Quantity:</label>
                                <select name="quantity[]">
                                    <option value=""><-- Selected Quantity --></option>
                                    <?php for ($q = 1; $q <= 20; $q++): 
                                        $q_selected = ($cur_q == $q) ? 'selected' : '';
                                    ?>
                                        <option value="<?php echo $q; ?>" <?php echo $q_selected; ?>><?php echo $q; ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        <?php endfor; ?>
                    </div>

                    <?php if (isset($errors['product'])): ?>
                        <div class="error-text"><?php echo $errors['product']; ?></div>
                    <?php endif; ?>

                    <?php if (isset($errors['quantity'])): ?>
                        <div class="error-text"><?php echo $errors['quantity']; ?></div>
                    <?php endif; ?>

                    <?php if (isset($errors['duplicate'])): ?>
                        <div class="error-text"><?php echo $errors['duplicate']; ?></div>
                    <?php endif; ?>

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