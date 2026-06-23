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
    <title>Customer List</title>
</head>
<style>
    table {
        border-collapse: collapse;
    }

    table, th, td {
        border: 1px solid black;
    }
</style>

<body>
    <table width="1100">
        <tr>
            <th>CustomerID</th>
            <th width="300">UserName</th>
            <th width="200">Name</th>
            <th>Email</th>
            <th>PhoneNumber</th>
        </tr>
        <?php

        $query = "SELECT * FROM customer";

        $result = mysqli_query($conn, $query);

        while ($row = mysqli_fetch_assoc($result)) {
        ?>
            <tr>
                <td><?php echo $row['customerID']; ?></td>
                <td><?php echo $row['username']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['phonenumber']; ?></td>
                <td><input type="button" value="Edit"></td>
                <td><button>Delete</button></td>
            </tr>
        <?php
        }
        mysqli_close($conn);
        ?>

        <a href="addcustomer.php"><input type="submit" value="AddCustomer"></a>
        <a href="logout.php"><input type="submit" value="Logout"></a>
    </table>

</body>
</html>