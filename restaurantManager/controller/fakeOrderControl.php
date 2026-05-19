<?php
include __DIR__ . '/managerSession.php';
managerRequireJson();
include __DIR__ . '/../../dirCommon/dbconnect.php';

$order = json_decode($_POST['order'], true);
if (!is_array($order)) {
    echo json_encode(["success" => false, "message" => "Invalid order data"]);
    exit;
}

$customerId = $order['customer_id'];
$restaurantId = $order['restaurant_id'];
$agentId = $order['agent_id'];
$deliveryAddress = $order['delivery_address'];
$paymentMethod = $order['payment_method'];
$subtotal = $order['subtotal'];
$deliveryFee = $order['delivery_fee'];
$totalAmount = $order['total_amount'];
$status = 'delivered';
$estimatedMinutes = $order['estimated_delivery_minutes'];
$managerId = (int)$_SESSION['user_id'];

$checkStmt = mysqli_prepare($conn, "SELECT id FROM restaurants WHERE id = ? AND manager_id = ? LIMIT 1");
mysqli_stmt_bind_param($checkStmt, "ii", $restaurantId, $managerId);
mysqli_stmt_execute($checkStmt);
$checkResult = mysqli_stmt_get_result($checkStmt);
if (!mysqli_fetch_assoc($checkResult)) {
    echo json_encode(["success" => false, "message" => "Restaurant does not belong to this manager"]);
    exit;
}

$sql = "INSERT INTO orders 
(customer_id, restaurant_id, agent_id, delivery_address, payment_method, subtotal, delivery_fee, total_amount, status, estimated_delivery_minutes)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param(
    $stmt,
    "iiissdddsi",
    $customerId,
    $restaurantId,
    $agentId,
    $deliveryAddress,
    $paymentMethod,
    $subtotal,
    $deliveryFee,
    $totalAmount,
    $status,
    $estimatedMinutes
);

if(mysqli_stmt_execute($stmt)){
    echo json_encode(["success" => true, "message" => "Order completed and inserted"]);
}else{
    echo json_encode(["success" => false, "message" => "Insert failed"]);
}
