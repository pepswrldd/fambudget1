<?php
// notifications.php
require_once 'config.php'; // Your DB connection

// Get the current date
$current_date = date('Y-m-d');

// Query to fetch upcoming bills (due within the next 7 days, for example)
$query = "SELECT bill_name, due_date FROM bills WHERE due_date >= '$current_date' AND due_date <= DATE_ADD('$current_date', INTERVAL 7 DAY)";
$result = mysqli_query($conn, $query);

$notifications = [];

while ($row = mysqli_fetch_assoc($result)) {
    $notifications[] = $row;
}

// Return as JSON
echo json_encode($notifications);
?>
