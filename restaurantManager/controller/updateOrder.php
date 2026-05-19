<?php
include __DIR__ . '/managerSession.php';
managerRequireJson();
include __DIR__ . '/../../dirCommon/dbconnect.php';

$input = json_decode(file_get_contents('php://input'), true);
if (!is_array($input)) {
    $input = $_POST;
}

$orderId = (int)($input['order_id'] ?? $input['id'] ?? 0);
$status = $input['status'] ?? '';
$allowedStatuses = ['pending', 'accepted', 'preparing', 'ready', 'picked_up', 'delivered', 'cancelled'];

if ($orderId <= 0 || !in_array($status, $allowedStatuses)) {
    echo json_encode(['success' => false, 'message' => 'Invalid order update.']);
    exit;
}

$managerId = $_SESSION['user_id'];

$sql = "
    UPDATE orders o
    INNER JOIN restaurants r ON r.id = o.restaurant_id
    SET o.status = ?
    WHERE o.id = ? AND r.manager_id = ?
";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'sii', $status, $orderId, $managerId);
$success = mysqli_stmt_execute($stmt) && mysqli_stmt_affected_rows($stmt) > 0;

echo json_encode([
    'success' => $success,
    'message' => $success ? 'Order updated successfully.' : 'Order not found for this restaurant.'
]);
