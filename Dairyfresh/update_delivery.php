<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'supplier') {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "dairy_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = (int) $_POST['order_id'];
    $status = $_POST['status'];

    // Validate status
    $allowed_statuses = ['Pending', 'Delivered'];
    if (!in_array($status, $allowed_statuses)) {
        die("Invalid status value.");
    }

    // Update with prepared statement
    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ? AND supplier_id = ?");
    $stmt->bind_param("sii", $status, $order_id, $_SESSION['user_id']);

    if ($stmt->execute()) {
        header("Location: supplier.php");
        exit();
    } else {
        echo "❌ Error updating order. Please try again.";
    }
}
?>
