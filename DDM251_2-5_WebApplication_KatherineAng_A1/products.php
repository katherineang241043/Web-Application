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
    <title>Products</title>
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
        max-width: 1100px;
        border-collapse: collapse;
        background-color: #FFF8F0;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }

    th, td {
        padding: 14px 20px;
        text-align: left;
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

    .btn-action {
        padding: 6px 15px;
        border: none;
        border-radius: 6px;
        font-family: "Poppins", sans-serif;
        font-weight: 600;
        font-size: 13px;
        cursor: pointer;
        transition: 0.2s;
    }

    .btn-edit {
        background-color: #C08552;
        color: #ffffff;
        margin-right: 5px;
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

            <a href="welcome.php" class="sidebar_menu"><i class="fa-solid fa-border-all"></i>Dashboard</a>

            <a href="customer.php" class="sidebar_menu"><i class="fa-solid fa-user"></i>Customers</a>

            <a href="products.php" class="sidebar_menu active"><i class="fa-solid fa-cheese"></i>Products</a>

            <div class="sidebar_menu"><i class="fa-solid fa-door-open"></i>Sign Out</div>
        </div>

        <div class="main-content">
            <h1>Product Management</h1>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ProductID</th>
                            <th width="350">Product Name</th>
                            <th>Price (RM)</th>
                            <th>Quantity</th>
                            <th width="180" style="text-align: center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT * FROM products";
                        $result = mysqli_query($conn, $query);

                        while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                            <tr>
                                <td><?php echo $row['ProductID']; ?></td>
                                <td><?php echo $row['ProductName']; ?></td>
                                <td><?php echo $row['Price']; ?></td>
                                <td><?php echo $row['Quantity']; ?></td>
                                <td style="text-align: center;">
                                    
                                    <form action="editproduct.php" method="POST" style="display: inline-block; margin: 0; padding: 0;">
                                        <input type="hidden" name="ProductID" value="<?php echo $row['ProductID']; ?>">
                                        <input type="submit" value="Edit" class="btn-action btn-edit">
                                    </form>

                                    <a href="deleteproduct.php?ProductID=<?php echo $row['ProductID']; ?>" class="btn-action btn-delete" style="text-decoration: none; display: inline-block;" onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                                    
                                </td>
                            </tr>
                        <?php
                        }
                        mysqli_close($conn);
                        ?>
                    </tbody>
                </table>
            </div>

            <div class="bottom-buttons">
                <a href="addproduct.php" class="btn-main"><i class="fa-solid fa-plus" style="margin-right: 8px;"></i>Add Product</a>
            </div>
        </div>
    </div>
</body>

</html>