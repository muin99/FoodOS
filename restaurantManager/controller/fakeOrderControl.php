<?php
include '../../dirCommon/dbconnect.php';

$order = json_decode($_POST['order'], true);

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
?>