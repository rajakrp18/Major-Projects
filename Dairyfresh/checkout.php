<?php
session_start();
$conn = new mysqli("localhost", "root", "", "dairy_db");

if ($conn->connect_error) {
    die(json_encode(['status' => 'error', 'message' => 'DB Connection Failed']));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cart = json_decode(file_get_contents('php://input'), true);
    $success = true;
    $user_id = $_SESSION['user_id'] ?? null;
    $customer_name = $_SESSION['name'] ?? 'Guest';

    if (!$user_id || empty($cart)) {
        echo json_encode(['status' => 'error', 'message' => 'User not logged in or cart empty']);
        exit;
    }

    foreach ($cart as $item) {
        $id = (int)$item['id'];
        $qty = (int)$item['quantity'];

        // Fetch item info
        $itemRes = $conn->query("SELECT name, item_quan FROM items WHERE id = $id");
        $itemData = $itemRes->fetch_assoc();

        if (!$itemData || $itemData['item_quan'] < $qty) {
            $success = false;
            continue;
        }

        // Update stock
        $conn->query("UPDATE items SET item_quan = item_quan - $qty WHERE id = $id");

        // Insert into orders table
        $stmt = $conn->prepare("INSERT INTO orders (customer_name, item_name, quantity, status, supplier_id) VALUES (?, ?, ?, ?, ?)");
        $item_name = $itemData['name'];
        $status = "Pending";
        $supplier_id = 1; // ⚠️ Replace with dynamic mapping if needed
        $stmt->bind_param("ssisi", $customer_name, $item_name, $qty, $status, $supplier_id);
        $stmt->execute();
    }

    echo json_encode(['status' => $success ? 'success' : 'partial']);
}
?>
