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
    <form action="editprofileinfo.php" method="POST">
        <table width="600">
            <tr>
                <th>New Username</th>
                <th>Password</th>
                <th>Confirm Password</th>
                <th>Year Joined</th>
            </tr>
            <tr>
                <td><input type="text" name="name" required></td>
                <td><input type="password" name="password"></td>
                <td><input type="password" name="confirmpassword"></td>
                <td><input type="text" name="yearjoin" required></td>
                <td><input type="submit" value="Submit"></td>
            </tr>
        </table>
    </form>
</body>
</html>