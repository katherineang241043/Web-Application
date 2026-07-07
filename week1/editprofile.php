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
    <button><a href="profile.php">Back</a></button>
    <form action="runeditprofile.php" method="POST">
        <table width="600">
            <tr>
                <th>New Username</th>
                <th>Password</th>
                <th>Confirm Password</th>
                <th>Year Joined</th>
            </tr>
            <tr>
                <td><input type="text" name="name" required></td>
                <td><input type="password" name="password" required minlength="6"></td>
                <td><input type="password" name="confirmpassword" required minlength="6"></td>
                <td><input type="number" min="1900" max="<?php echo date("Y"); ?>" step="1" name="yearjoin" required maxlength="4"></td>
                <td><input type="submit" value="Submit"></td>
            </tr>
        </table>
        <?php
                if (isset($_GET['error'])){
                    echo '<div style="color:red; margin: 5px 0;">' . $_GET['error'] . '</div>';
                }
            ?>
    </form>
</body>
</html>