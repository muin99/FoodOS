<?php
if (session_status() === PHP_SESSION_NONE) {
session_start();
}
header('Content-Type: application/json');

include '../../dirCommon/dbconnect.php';
include '../model/agentModel.php';

if (($_SESSION['user_role'] ?? '') != 'agent') {
echo json_encode(['success'=>false,'message'=>'Please login first.']);
exit;
}

$orders=
getAvailableAssignments($conn);

echo json_encode([
'success'=>true,
'orders'=>$orders
]);
