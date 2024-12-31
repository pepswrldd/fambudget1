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

// Fetch total spendings per month
$spendings_result = $db->query("SELECT MONTH(date) AS month, SUM(amount) AS total_spendings FROM spendings WHERE user_id = $user_id GROUP BY MONTH(date)");

// Fetch savings per month (if applicable)
$savings_result = $db->query("SELECT MONTH(date) AS month, SUM(amount) AS total_savings FROM savings WHERE user_id = $user_id GROUP BY MONTH(date)");

// Fetch category-wise spendings
$categories_result = $db->query("SELECT category, SUM(amount) AS total_spent FROM spendings WHERE user_id = $user_id GROUP BY category");

// Fetch any other analytics you want, like last month's data or debt.

$spendings = [];
$savings = [];
$categories = [];

while ($row = $spendings_result->fetch_assoc()) {
    $spendings[$row['month']] = $row['total_spendings'];
}

while ($row = $savings_result->fetch_assoc()) {
    $savings[$row['month']] = $row['total_savings'];
}

while ($row = $categories_result->fetch_assoc()) {
    $categories[$row['category']] = $row['total_spent'];
}

// Return all the data as a JSON response
echo json_encode([
    'spendings' => $spendings,
    'savings' => $savings,
    'categories' => $categories
]);
?>
