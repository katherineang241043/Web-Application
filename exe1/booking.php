<?php
$servername = "localhost";
$username = "kshop";
$password = "kshop123";
$dbname = "kshop";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();

$selected_date = isset($_GET['date']) ? $_GET['date'] : 'All';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Room Booking</title>   
    <style>
    table {
        border-collapse: collapse;
    }

    table, th, td {
        border: 1px solid black;
    }

    .btn input[type="button"] {
        cursor: pointer;
    }

    .active input[type="button"] {
        font-weight: bold;
        background-color: #ddd;
    }
    </style>
</head>

<body>

    <h2>Available Rooms for Booking</h2>

    <div id="myBtnContainer">
        <a href="booking.php?date=All" class="btn <?php echo ($selected_date == 'All') ? 'active' : ''; ?>"><input type="button" value="All"></a>
        <a href="booking.php?date=2026-07-27" class="btn <?php echo ($selected_date == '2026-07-27') ? 'active' : ''; ?>"><input type="button" value="27/7"></a>
        <a href="booking.php?date=2026-07-28" class="btn <?php echo ($selected_date == '2026-07-28') ? 'active' : ''; ?>"><input type="button" value="28/7"></a>
        <a href="booking.php?date=2026-07-29" class="btn <?php echo ($selected_date == '2026-07-29') ? 'active' : ''; ?>"><input type="button" value="29/7"></a>
    </div>

    <br>

    <table width="1100">
        <thead>
            <tr>
                <th width="50">No.</th>
                <th width="80">Room No.</th>
                <th width="200">Room Type</th>
                <th width="150">Price</th>
                <th width="150">Status</th>
                <th width="120">Action</th>
            </tr>
        </thead>
        <tbody>
        <?php

        if ($selected_date != 'All') {
            $safe_date = mysqli_real_escape_string($conn, $selected_date);
            $query = "SELECT * FROM rooms WHERE RoomDate = '$safe_date'";
        } else {
            $query = "SELECT * FROM rooms";
        }

        $result = mysqli_query($conn, $query);

        $number = 1; 

        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
            ?>
                <tr>
                   <td align="center"><?php echo $number++; ?></td>
                    <td align="center"><?php echo htmlspecialchars($row['RoomNumber']); ?></td>
                    <td><?php echo htmlspecialchars($row['RoomType']); ?></td>
                    <td>RM<?php echo htmlspecialchars($row['Price']); ?></td>
                    <td align="center"><?php echo htmlspecialchars($row['Status']); ?></td>
                    <td align="center">
                        <?php if ($row['Status'] == 'Available'): ?>
                            <a href="confirmbooking.php?room_id=<?php echo urlencode($row['ID']); ?>&date=<?php echo urlencode($selected_date); ?>">
                                <input type="button" value="Book Now">
                            </a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php
            }
        } else {
            ?>
            <tr>
                <td colspan="6" align="center">No rooms available for this date.</td>
            </tr>
            <?php
        }
        
        mysqli_close($conn);
        ?>
        </tbody>
    </table>

    <br>
    <a href="profile.php"><input type="button" value="My Bookings"></a>
    <a href="logout.php"><input type="button" value="Logout"></a>

</body>
</html>