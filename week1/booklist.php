<?php
$servername = "localhost";
$username = "katherine";
$password = "20041126Ang";
$dbname = "katherine";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

session_start();

if(!isset($_SESSION["email"])) {
    header("Location: index.php");
}
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
                <td><button><a class="link" href="editbooklist.php?ISBN=<?php echo $row['ISBN']; ?>">Edit</a></button></td>
                <td><button><a class="link" href="rundeletebook.php?ISBN=<?php echo $row['ISBN']; ?>" onclick="return confirm('Are you sure you want to delete (<?php echo $row['ISBN']; ?>) book?')">Delete</a></button></td>
            </tr>
        <?php
        }
        mysqli_close($conn);
        ?>

        <a href="profile.php"><input type="submit" value="Profile"></a>
        <a href="addbook.php"><input type="submit" value="AddBook"></a>
        <a href="logout.php"><input type="submit" value="Logout"></a>
    </table>

    <script> 
    function myFunction(ISBN) { 
        return confirm("Are you sure you want to delete this = " + ISBN + " book?"); } 
    </script>

</body>
</html>