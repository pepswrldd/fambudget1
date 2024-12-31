<?php
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    include('config.php');

    $data = json_decode(file_get_contents('php://input'), true);
    $name = trim($data['name']);
    $user_id = $_SESSION['user_id'];

    if (empty($name)) {
        echo json_encode(['success' => false, 'message' => 'Name is required.']);
        exit;
    }

    $stmt = $db->prepare("INSERT INTO family_members (user_id, name) VALUES (?, ?)");
    $stmt->bind_param("is", $user_id, $name);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Family member added successfully!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error adding family member.']);
    }

    $stmt->close();
}
?>
