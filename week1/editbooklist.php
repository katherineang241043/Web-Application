<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
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
    <button><a href="booklist.php">Back</a></button>
    <form action="editbooklistinfo.php" method="POST">
        <table width="600">
            <tr>
                <th>ISBN</th>
                <th>title</th>
                <th>author</th>
                <th>description</th>
                <th>price</th>
            </tr>
            <tr>
                <td><input type="text" name="ISBN" value="<?php echo $_GET['ISBN']; ?>" readonly></td>
                <td><input type="text" name="title"></td>
                <td><input type="text" name="author"></td>
                <td><input type="text" name="description"></td>
                <td><input type="text" name="price"></td>
                <td><input type="submit" value="Submit"></td>
            </tr>
        </table>
    </form>
</body>
</html>