<?php
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    include('config.php');

    $data = json_decode(file_get_contents('php://input'), true);
    $memberId = $data['memberId'];
    $amount = $data['amount'];

    if (empty($amount) || !is_numeric($amount) || $amount <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid amount.']);
        exit;
    }

    $stmt = $db->prepare("UPDATE family_members SET total_spendings = total_spendings + ? WHERE id = ?");
    $stmt->bind_param("di", $amount, $memberId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Spending added successfully!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error updating spendings.']);
    }

    $stmt->close();
}
?>
