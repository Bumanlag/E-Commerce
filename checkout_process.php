<?php
session_start();
if (!isset($_SESSION['user_data'])) {
    header("Location: login.php");
    exit;
}

require_once 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo->beginTransaction();
        
        // Get data from form
        $userId = $_POST['user_id'];
        $totalAmount = $_POST['total_amount'];
        $cartData = json_decode($_POST['cart_data'], true);
        
        // 1. Create order record
        $stmt = $pdo->prepare("
            INSERT INTO orders (user_id, total, shipping_address, created_at)
            VALUES (?, ?, 'Default Shipping Address', NOW())
        ");
        $stmt->execute([$userId, $totalAmount]);
        $orderId = $pdo->lastInsertId();
        
        // 2. Create order items
        foreach ($cartData as $item) {
            $stmt = $pdo->prepare("
                INSERT INTO order_items (order_id, product_id, quantity, price)
                VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([
                $orderId,
                $item['product_id'],
                $item['quantity'],
                $item['price']
            ]);
        }
        
        // 3. Clear cart
        $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ?");
        $stmt->execute([$userId]);
        
        $pdo->commit();
        
        // Redirect to order confirmation
        header("Location: order_confirmation.php?order_id=$orderId");
        exit;
        
    } catch (Exception $e) {
        $pdo->rollBack();
        // Handle error
        die("Error processing your order: " . $e->getMessage());
    }
} else {
    header("Location: cart.php");
    exit;
}
?>