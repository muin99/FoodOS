<?php
session_start();
header('Content-Type: application/json');

include '../../dirCommon/dbconnect.php';
include '../model/agentModel.php';

if (($_SESSION['user_role'] ?? '') !== 'agent') {
echo json_encode(['success'=>false,'message'=>'Please login as an agent.']);
exit;
}

$orderId=
(int)(
$_POST['order_id']
??
0
);

$newStatus=
$_POST['new_status']
??
'';

$result=
updateDeliveryStatus(
$conn,
$orderId,
$_SESSION['agent_id'],
$newStatus
);

echo json_encode([
'success'=>$result
]);
