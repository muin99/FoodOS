<?php
include __DIR__ . '/managerSession.php';
managerRequireJson();
include __DIR__ . '/../../dirCommon/dbconnect.php';

$id = (int)($_POST['id'] ?? 0);
$managerId = (int)$_SESSION['user_id'];

$sql = "
    UPDATE orders o
    INNER JOIN restaurants r ON r.id = o.restaurant_id
    SET o.status = 'ready'
    WHERE o.id = ? AND r.manager_id = ? AND o.status IN ('pending', 'accepted', 'preparing')
";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ii", $id, $managerId);

if(mysqli_stmt_execute($stmt) && mysqli_stmt_affected_rows($stmt) > 0){
    echo json_encode(["success" => true, "message" => "Order marked ready for pickup"]);
}else{
    echo json_encode(["success" => false, "message" => "Order not found for this restaurant."]);
}
