<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'User not logged in.']);
    exit;
}

include('config.php');
$user_id = $_SESSION['user_id'];

$stmt = $db->prepare("SELECT name, email, phone FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    echo json_encode(['status' => 'success', 'name' => $user['name'], 'email' => $user['email'], 'phone' => $user['phone']]);
} else {
    http_response_code(404);
    echo json_encode(['status' => 'error', 'message' => 'User not found.']);
}
$stmt->close();
?>
