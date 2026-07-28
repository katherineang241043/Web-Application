<?php
session_start();

$servername = "localhost";
$username = "kshop";
$password = "kshop123";
$dbname = "kshop";


$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_GET["ID"])) {
    $booking_id = intval($_GET["ID"]);


    $find_room_sql = "SELECT RoomID FROM bookings WHERE ID = '$booking_id'";
    $result = mysqli_query($conn, $find_room_sql);

    if ($result && $row = mysqli_fetch_assoc($result)) {
        $room_id = $row['room_id'];

        $delete_sql = "DELETE FROM bookings WHERE ID = '$booking_id'";
        if (mysqli_query($conn, $delete_sql)) {

            $update_room_sql = "UPDATE rooms SET Status = 'Available' WHERE ID = '$room_id'";
            mysqli_query($conn, $update_room_sql);
        }
    }
}

$conn->close();

header("Location: profile.php");
exit();
?>