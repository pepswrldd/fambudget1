<?php
include 'config.php'; // Connect to the database
$result = $conn->query("SELECT * FROM expenses WHERE user_id = {$_SESSION['user_id']} ORDER BY date DESC LIMIT 5");
while ($row = $result->fetch_assoc()) {
    echo "<tr>
            <td>{$row['name']}</td>
            <td>\${$row['price']}</td>
            <td>{$row['category']}</td>
            <td>{$row['spender']}</td>
          </tr>";
}
?>
