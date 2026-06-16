<?php
$servername = "localhost";
$username = "katherine";
$password = "20041126Ang";
$dbname = "katherine";

$conn = new mysqli($servername, $username, $password, $dbname);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book List</title>
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
            <th>ISBN</th>
            <th width="300">Title</th>
            <th width="200">Author</th>
            <th>Description</th>
            <th>Price(RM)</th>
        </tr>
        <?php

        $query = "SELECT * FROM booklist";

        $result = mysqli_query($conn, $query);

        while ($row = mysqli_fetch_assoc($result)) {
        ?>
            <tr>
                <td><?php echo $row['ISBN']; ?></td>
                <td><?php echo $row['title']; ?></td>
                <td><?php echo $row['author']; ?></td>
                <td><?php echo $row['description']; ?></td>
                <td><?php echo $row['price']; ?></td>
                <td><input type="button" value="Edit"></td>
                <td><button>Delete</button></td>
            </tr>
        <?php
        }
        mysqli_close($conn);
        ?>

        <a href=""><input type="submit" value="Profile"></a>
        <a href=""><input type="submit" value="AddBook"></a>
        <a href=""><input type="submit" value="Logout"></a>
    </table>

</body>
</html>