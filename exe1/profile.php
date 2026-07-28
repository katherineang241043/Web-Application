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

$message = "";
$message_type = "";

if (isset($_POST['cancel_booking'])) {
    $booking_id = intval($_POST['booking_id']);
    $room_id = intval($_POST['room_id']);
    $delete_sql = "DELETE FROM bookings WHERE id = '$booking_id' OR ID = '$booking_id'";
    
    if (mysqli_query($conn, $delete_sql)) {
        $update_room = "UPDATE rooms SET Status = 'Available' WHERE id = '$room_id' OR ID = '$room_id'";
        mysqli_query($conn, $update_room);

        $message = "Booking cancelled successfully!";
        $message_type = "success";
    } else {
        $message = "Error cancelling booking: " . mysqli_error($conn);
        $message_type = "error";
    }
}

$query = "SELECT bookings.*, 
                 rooms.RoomNumber, 
                 rooms.RoomType, 
                 rooms.Price 
          FROM bookings 
          LEFT JOIN rooms ON bookings.RoomID = rooms.id OR bookings.RoomID = RoomID
          ORDER BY bookings.ID DESC";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings</title>
    <style>

        body { 
        font-family: Arial, 
        sans-serif; margin: 20px; 
        }

        table { 
        border-collapse: collapse; 
        margin-top: 10px; 
        }

        table, th, td { 
        border: 1px solid black; 
        padding: 8px; 
        }

        .btn input[type="button"], input[type="submit"] { 
        cursor: pointer; 
        }

        .alert { 
        padding: 10px; 
        margin-bottom: 15px; 
        width: 1080px; 
        box-sizing: border-box; 
        }

        .alert.error { 
        background-color: #f8d7da; 
        color: #721c24; 
        border: 1px solid #f5c6cb; 
        }

        .alert.success { 
        background-color: #d4edda; 
        color: #155724; 
        border: 1px solid #c3e6cb; 
        }

    </style>
</head>

<body>

    <h2>My Bookings</h2>

    <?php if (!empty($message)): ?>
        <div class="alert <?php echo $message_type; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <table width="1100">
        <thead>
            <tr bgcolor="#f2f2f2">
                <th width="50">No.</th>
                <th width="150">Customer Name</th>
                <th width="120">Contact</th>
                <th width="80">Room No.</th>
                <th width="150">Room Type</th>
                <th width="100">Price</th>
                <th width="120">Check-in</th>
                <th width="120">Check-out</th>
                <th width="110">Action</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $number = 1;

        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $booking_id = isset($row['id']) ? $row['id'] : $row['ID'];
                ?>
                <tr>
                    <td align="center"><?php echo $number++; ?></td>
                    <td><?php echo htmlspecialchars($row['CustomerName']); ?></td>
                    <td align="center"><?php echo htmlspecialchars($row['ContactNumber']); ?></td>
                    <td align="center"><?php echo htmlspecialchars($row['RoomNumber']); ?></td>
                    <td><?php echo htmlspecialchars($row['RoomType']); ?></td>
                    <td>RM<?php echo htmlspecialchars($row['Price']); ?></td>
                    <td align="center"><?php echo htmlspecialchars($row['CheckIN']); ?></td>
                    <td align="center"><?php echo htmlspecialchars($row['CheckOUT']); ?></td>
                    <td align="center">
                        <form method="POST" action="mybooking.php" onsubmit="return confirm('Are you sure you want to cancel this booking?');">
                            <input type="hidden" name="booking_id" value="<?php echo $booking_id; ?>">
                            <input type="hidden" name="RoomID" value="<?php echo $row['RoomID']; ?>">
                            <input type="submit" name="cancel_booking" value="Cancel">
                        </form>
                    </td>
                </tr>
            <?php
            }
        } else {
            ?>
            <tr>
                <td colspan="9" align="center">No bookings found.</td>
            </tr>
            <?php
        }

        mysqli_close($conn);
        ?>
        </tbody>
    </table>

    <br>
    <a href="booking.php"><input type="button" value="Back to Available Rooms"></a>
    <a href="logout.php"><input type="button" value="Logout"></a>

</body>
</html>