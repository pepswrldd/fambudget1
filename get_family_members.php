<?php
session_start();
header('Content-Type: application/json');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'User not logged in.']);
    exit;
}

include('config.php');
$user_id = $_SESSION['user_id'];

// Query to fetch family members with last_seen
$result = $db->query("SELECT id, name, total_spendings, last_seen FROM family_members WHERE user_id = $user_id");

$family_members = [];

// Fetch data from the result
while ($row = $result->fetch_assoc()) {
    $family_members[] = $row;
}

// Return family members as JSON
echo json_encode($family_members);
?>
