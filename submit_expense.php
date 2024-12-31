<?php
include('config.php');
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {
    // Get form data
    $name = trim($_POST['name']);
    $price = trim($_POST['price']);
    $theme = trim($_POST['theme']);
    $details = trim($_POST['details']);
    $user_id = $_SESSION['user_id'];

    // Validate inputs
    if (empty($name) || empty($price) || empty($theme) || empty($details)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'All fields are required.']);
        exit;
    }

    if (!is_numeric($price) || $price <= 0) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid price.']);
        exit;
    }

    $validThemes = ['Rent', 'Grocery', 'Entertainment', 'Education', 'Bills', 'Other'];
    if (!in_array($theme, $validThemes)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid theme selected.']);
        exit;
    }

    // Insert data into the expenses table using prepared statements
    $stmt = $db->prepare("INSERT INTO expenses (name, price, theme, details, user_id) VALUES (?, ?, ?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("sdssi", $name, $price, $theme, $details, $user_id);
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Expense added successfully!']);
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
