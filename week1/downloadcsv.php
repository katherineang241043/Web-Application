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


$filename = "employee_list_" . strtolower($selected_dept) . "_" . date('Ymd') . ".csv";
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=' . $filename);


$output = fopen('php://output', 'w');


fputs($output, $b = chr(0xEF) . chr(0xBB) . chr(0xBF));


fputcsv($output, array('No.', 'ID', 'Name', 'Department'));


if ($selected_dept != 'All') {
    $safe_dept = mysqli_real_escape_string($conn, $selected_dept);
    $query = "SELECT * FROM employee WHERE department = '$safe_dept'";
} else {
    $query = "SELECT * FROM employee";
}

$result = mysqli_query($conn, $query);
$number = 1;


while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, array(
        $number++,
        $row['ID'],
        $row['name'],
        $row['department']
    ));
}

fclose($output);
mysqli_close($conn);
exit();
?>