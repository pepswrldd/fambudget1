<?php
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access.']);
    exit;
}

include('config.php');
$user_id = $_SESSION['user_id'];
$name = trim($_POST['family-member-name']);
$email = trim($_POST['family-member-email']);

if (empty($name) || empty($email)) {
    echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid email address.']);
    exit;
}

$stmt = $db->prepare("INSERT INTO family_members (user_id, name, email) VALUES (?, ?, ?)");
$stmt->bind_param("iss", $user_id, $name, $email);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Family member added successfully.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to add family member.']);
}
$stmt->close();
?>
