<?php
include('config.php');
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {
    // Get form data from the request
    $amount = trim($_POST['amount']);
    $user_id = $_SESSION['user_id'];

    // Validate input
    if (empty($amount) || !is_numeric($amount) || $amount <= 0) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid amount.']);
        exit;
    }

    // Insert data into earnings table using prepared statements
    $stmt = $db->prepare("INSERT INTO earnings (amount, user_id) VALUES (?, ?)");
    if ($stmt) {
        $stmt->bind_param("di", $amount, $user_id);
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Earnings added successfully!']);
        } else {
            error_log("Database error: " . $stmt->error);
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'An error occurred. Please try again later.']);
        }
        $stmt->close();
    } else {
        error_log("Database prepare error: " . $db->error);
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'An error occurred. Please try again later.']);
    }
} else {
    http_response_code(401); // Unauthorized
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
}
?>
