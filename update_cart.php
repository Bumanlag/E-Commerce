<?php
session_start();
require_once 'database.php';

if (!isset($_SESSION['user_data'])) {
    header("Location: login.php");
    exit;
}

if (isset($_POST['product_id'], $_POST['action'])) {
    $product_id = intval($_POST['product_id']);
    $user_id = $_SESSION['user_data']['id'];

    if ($_POST['action'] === 'increase') {
        $stmt = $pdo->prepare("UPDATE cart SET quantity = quantity + 1 WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$user_id, $product_id]);
    } elseif ($_POST['action'] === 'decrease') {
        // First, get current quantity
        $stmt = $pdo->prepare("SELECT quantity FROM cart WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$user_id, $product_id]);
        $item = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($item && $item['quantity'] > 1) {
            $stmt = $pdo->prepare("UPDATE cart SET quantity = quantity - 1 WHERE user_id = ? AND product_id = ?");
            $stmt->execute([$user_id, $product_id]);
        }
    }
}

header("Location: cart.php");
exit;
