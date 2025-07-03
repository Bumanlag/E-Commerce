<?php
session_start();
require_once 'database.php';

if (!isset($_SESSION['user_data'])) {
    header("Location: login.php");
    exit;
}

if (isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);
    $user_id = $_SESSION['user_data']['id'];

    $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$user_id, $product_id]);
}

header("Location: cart.php");
exit;
