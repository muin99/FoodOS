<?php
include '../../dirCommon/dbconnect.php';

$id = $_POST['id'] ?? 0;

$sql = "UPDATE orders SET status = 'delivered' WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);

if(mysqli_stmt_execute($stmt)){
    echo json_encode(["success" => true, "message" => "Order completed"]);
}else{
    echo json_encode(["success" => false, "message" => "Update failed"]);
}
?>