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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $order_id = isset($_POST['OrderID']) ? intval($_POST['OrderID']) : 0;
    $selected_products = isset($_POST['product_id']) ? $_POST['product_id'] : [];
    $selected_quantities = isset($_POST['quantity']) ? $_POST['quantity'] : [];

    if ($order_id <= 0) {
        header("Location: order.php");
        exit();
    }

    foreach ($selected_products as $p) {
        if (empty($p)) {
            header("Location: editorder.php?OrderID=$order_id&error=Please select product for all rows.");
            exit();
        }
    }

    foreach ($selected_quantities as $q) {
        if (empty($q)) {
            header("Location: editorder.php?OrderID=$order_id&error=Please select quantity for all rows.");
            exit();
        }
    }

    $non_empty = array_filter($selected_products);
    if (count($non_empty) !== count(array_unique($non_empty))) {
        header("Location: editorder.php?OrderID=$order_id&error=Product cannot be same.");
        exit();
    }

    $products = [];
    $product_query = "SELECT * FROM products";
    $product_result = @mysqli_query($conn, $product_query);
    if (!$product_result) {
        $product_query = "SELECT * FROM product";
        $product_result = @mysqli_query($conn, $product_query);
    }
    while ($row = mysqli_fetch_assoc($product_result)) {
        $products[] = $row;
    }

    $del_stmt = $conn->prepare("DELETE FROM order_details WHERE OrderID = ?");
    $del_stmt->bind_param("i", $order_id);
    $del_stmt->execute();

    for ($i = 0; $i < count($selected_products); $i++) {
        $p_id = $selected_products[$i];
        $qty = intval($selected_quantities[$i]);

        $p_name = "";
        $p_price = 0;

        foreach ($products as $p) {
            $cur_p_id = $p['ProductID'] ?? $p['product_id'] ?? 0;
            if ($cur_p_id == $p_id) {
                $p_name = $p['ProductName'] ?? $p['Product Name'] ?? $p['product_name'] ?? $p['Name'] ?? 'Product';
                $p_price = $p['Price'] ?? $p['ProductPrice'] ?? $p['product_price'] ?? 0;
                break;
            }
        }

        $ins_stmt = $conn->prepare("INSERT INTO order_details (OrderID, ProductName, Quantity, ProductPrice) VALUES (?, ?, ?, ?)");
        $ins_stmt->bind_param("isid", $order_id, $p_name, $qty, $p_price);
        $ins_stmt->execute();
    }

    header("Location: readorder.php?OrderID=$order_id");
    exit();
}
?>