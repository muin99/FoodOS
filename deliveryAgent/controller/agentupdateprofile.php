<?php
session_start();

include '../../dirCommon/dbconnect.php';
include '../model/agentModel.php';

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