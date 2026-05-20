<?php
include __DIR__ . '/managerSession.php';
managerRequireJson();
include __DIR__ . '/../../dirCommon/dbconnect.php';

$id = (int)($_POST['id'] ?? 0);
$status = $_POST['status'] ?? 'ready';
$managerId = (int)$_SESSION['user_id'];
$allowedStatuses = ['accepted', 'preparing', 'ready', 'cancelled'];

if ($id <= 0 || !in_array($status, $allowedStatuses)) {
    echo json_encode(["success" => false, "message" => "Invalid order update."]);
    exit;
}

$sql = "
    UPDATE orders o
    INNER JOIN restaurants r ON r.id = o.restaurant_id
    SET o.status = ?
    WHERE o.id = ? AND r.manager_id = ? AND o.status IN ('pending', 'accepted', 'preparing', 'ready')
";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "sii", $status, $id, $managerId);

if(mysqli_stmt_execute($stmt) && mysqli_stmt_affected_rows($stmt) > 0){
    echo json_encode(["success" => true, "message" => "Order updated to " . str_replace('_', ' ', $status) . "."]);
}else{
    echo json_encode(["success" => false, "message" => "Order not found for this restaurant."]);
}
