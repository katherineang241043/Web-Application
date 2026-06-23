<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Customer</title>
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
    <button><a class="link" href="customer.php">Back</a></button>
    <table width="600">
        <tr>
            <th>CustomerID</th>
            <th>Username</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone Number</th>
        </tr>
        <tr>
            <form action="insertcustomer.php" method="POST">
                <td><input type=text name=customerID></td>
                <td><input type=text name=username></td>
                <td><input type=text name=name></td>
                <td><input type=text name=email></td>
                <td><input type=text name=phonenumber></td>
                <td><input type=submit value="Add"></td>
            </form>
        </tr>
    </table>
</body>
</html>