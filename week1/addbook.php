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
    <title>Add Book</title>
    <style>
        table {
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid black;
        }
    </style>
</head>
<body>
    <button><a class="link" href="booklist.php">Back</a></button>
    <table width="600">
        <tr>
            <th>ISBN</th>
            <th>Title</th>
            <th>Author</th>
            <th>Description</th>
            <th>Price</th>
        </tr>
        <tr>
            <from action="../web/runAddBook.php" method="GET">
                <td><input type=text name=ISBN></td>
                <td><input type=text name=title></td>
                <td><input type=text name=author></td>
                <td><textarea cols='50' input type=text name=description></textarea></td>
                <td><input type=text name=price></td>
                <td><input type=submit value=add></td>
            </from>
        </tr>
    </table>
</body>
</html>