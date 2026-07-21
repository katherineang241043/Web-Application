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

$selected_dept = isset($_GET['dept']) ? $_GET['dept'] : 'All';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee List</title>   
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

    <div id="myBtnContainer">
        <a href="employee.php?dept=All" class="btn <?php echo ($selected_dept == 'All') ? 'active' : ''; ?>"><input type="button" value="All"></a>
        <a href="employee.php?dept=IT" class="btn <?php echo ($selected_dept == 'IT') ? 'active' : ''; ?>"><input type="button" value="IT"></a>
        <a href="employee.php?dept=HR" class="btn <?php echo ($selected_dept == 'HR') ? 'active' : ''; ?>"><input type="button" value="HR"></a>
        <a href="employee.php?dept=Finance" class="btn <?php echo ($selected_dept == 'Finance') ? 'active' : ''; ?>"><input type="button" value="Finance"></a>
        
        <a href="downloadcsv.php?dept=<?php echo urlencode($selected_dept); ?>" class="btn btn-download"><input type="button" value="Download CSV"></a>
    </div>

    </div>

    <table width="1100">
        <thead>
            <tr>
                <th width="50">No.</th>
                <th width="80">ID</th>
                <th width="300">Name</th>
                <th width="200">Department</th>
            </tr>
        </thead>
        <tbody>
        <?php

        if ($selected_dept != 'All') {
            $safe_dept = mysqli_real_escape_string($conn, $selected_dept);
            $query = "SELECT * FROM employee WHERE department = '$safe_dept'";
        } else {
            $query = "SELECT * FROM employee";
        }

        $result = mysqli_query($conn, $query);
        

        $number = 1; 

        while ($row = mysqli_fetch_assoc($result)) {
        ?>
            <tr>
                <td><?php echo $number++; ?></td>
                <td><?php echo htmlspecialchars($row['ID']); ?></td>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['department']); ?></td>
            </tr>
        <?php
        }
        
        mysqli_close($conn);
        ?>
        </tbody>
    </table>

    <br>
    <a href="booklist.php"><input type="button" value="Back"></a>
    <a href="logout.php"><input type="button" value="Logout"></a>

</body>
</html>