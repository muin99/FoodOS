<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
header('Content-Type: application/json');

include __DIR__ . '/../../dirCommon/dbconnect.php';
include __DIR__ . '/../model/customerModel.php';

if (($_SESSION['user_role'] ?? '') !== 'customer' || empty($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'message' => 'Please login as a customer.'
    ]);
    exit;
}

$orderId = (int)($_POST['order_id'] ?? 0);
$rating = (int)($_POST['rating'] ?? 0);
$comment = $_POST['comment'] ?? '';

$result = saveCustomerReview($conn, (int)$_SESSION['user_id'], $orderId, $rating, $comment);
echo json_encode($result);
