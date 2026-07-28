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

$room_id = isset($_GET['room_id']) ? intval($_GET['room_id']) : 0;
$message = "";
$message_type = "";


$room_query = "SELECT * FROM rooms WHERE ID = '$room_id'";
$room_result = mysqli_query($conn, $room_query);
$room = mysqli_fetch_assoc($room_result);


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['confirm_submit'])) {
    $room_id        = intval($_POST['RoomID']);
    $customer_name  = mysqli_real_escape_string($conn, trim($_POST['customer_name']));
    $contact_number = mysqli_real_escape_string($conn, trim($_POST['contact_number']));
    $check_in       = $_POST['check_in'];
    $check_out      = $_POST['check_out'];
    if ($check_in >= $check_out) {
        $message = "Check-out date must be later than Check-in date!";
        $message_type = "error";
    } elseif (empty($customer_name) || empty($contact_number)) {
        $message = "Please complete all fields!";
        $message_type = "error";
    } else {
        $insert_sql = "INSERT INTO bookings (RoomID, CustomerName, ContactNumber, CheckIN, CheckOUT) 
                       VALUES ('$room_id', '$customer_name', '$contact_number', '$check_in', '$check_out')";
        
        if (mysqli_query($conn, $insert_sql)) {
            $update_room = "UPDATE rooms SET Status = 'Booked' WHERE ID = '$room_id'";
            mysqli_query($conn, $update_room);

            $message = "Booking confirmed successfully!";
            $message_type = "success";
        } else {
            $message = "Database error: " . mysqli_error($conn);
            $message_type = "error";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Booking</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .booking-card { border: 1px solid black; padding: 20px; width: 450px; background: #fdfdfd; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-weight: bold; margin-bottom: 5px; }
        .form-group input { width: 100%; padding: 8px; box-sizing: border-box; }
        .alert { padding: 10px; margin-bottom: 15px; width: 450px; }
        .alert.error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .alert.success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .room-info { background: #eee; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; }
    </style>
</head>
<body>

    <h2>Confirm Your Room Reservation</h2>

    <?php if (!empty($message)): ?>
        <div class="alert <?php echo $message_type; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <?php if ($room): ?>
        <div class="booking-card">
            <div class="room-info">
                <strong>Room No:</strong> <?php echo htmlspecialchars($room['RoomNumber']); ?><br>
                <strong>Room Type:</strong> <?php echo htmlspecialchars($room['RoomType']); ?><br>
                <strong>Price:</strong> RM<?php echo htmlspecialchars($room['Price']); ?><br>
                <strong>Status:</strong> <?php echo htmlspecialchars($room['Status']); ?>
            </div>

            <?php if ($message_type == "success"): ?>
                <a href="booking.php"><input type="button" value="Back to Available Rooms"></a>
            <?php else: ?>

                <form method="POST" action="confirmbooking.php?room_id=<?php echo $room_id; ?>">
                    <input type="hidden" name="RoomID" value="<?php echo $room['ID']; ?>">

                    <div class="form-group">
                        <label for="customer_name">Full Name:</label>
                        <input type="text" name="customer_name" id="customer_name" required>
                    </div>

                    <div class="form-group">
                        <label for="contact_number">Contact Number:</label>
                        <input type="text" name="contact_number" id="contact_number" required>
                    </div>

                    <div class="form-group">
                        <label for="check_in">Check-in Date:</label>
                        <input type="date" name="check_in" id="check_in" min="<?php echo date('Y-m-d'); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="check_out">Check-out Date:</label>
                        <input type="date" name="check_out" id="check_out" min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" required>
                    </div>

                    <input type="submit" name="confirm_submit" value="Confirm Reservation">
                    <a href="booking.php"><input type="button" value="Cancel"></a>
                </form>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <p>No room selected or room not found.</p>
        <a href="booking.php"><input type="button" value="Back to Rooms"></a>
    <?php endif; ?>

    <?php mysqli_close($conn); ?>

</body>
</html>