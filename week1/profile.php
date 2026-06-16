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
    <title>Profile</title>
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
    <table width="700">
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Year Joined</th>
        </tr>
        <?php

        $query = "SELECT * FROM student";

        $result = mysqli_query($conn, $query);

        while ($row = mysqli_fetch_assoc($result)) {
        ?>
            <tr>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['yearjoin']; ?></td>
                <td><input type="button" value="Edit"></td>
            </tr>
        <?php
        }
        mysqli_close($conn);
        ?>

        <a href="booklist.php"><input type="submit" value="Back"></a>
    </table>

</body>
</html>