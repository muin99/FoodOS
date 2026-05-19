<?php
if (session_status() === PHP_SESSION_NONE) {
session_start();
}
header('Content-Type: application/json');

include '../../dirCommon/dbconnect.php';
include '../model/agentModel.php';

if (($_SESSION['user_role'] ?? '') != 'agent' || !isset($_SESSION['user_id'])) {
echo json_encode(['success'=>false,'message'=>'Please login first.']);
exit;
}

$name=$_POST['name']??'';
$phone=$_POST['phone']??'';
$vehicleType=$_POST['vehicle_type']??'';

$result=
updateAgentProfile(
$conn,
$_SESSION['user_id'],
$name,
$phone,
$vehicleType
);

echo json_encode([
'success'=>$result
]);
