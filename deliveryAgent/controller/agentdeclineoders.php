<?php
session_start();

include '../../dirCommon/dbconnect.php';
include '../model/agentModel.php';

if (($_SESSION['user_role'] ?? '') != 'agent' || !isset($_SESSION['agent_id'])) {
echo json_encode(['success'=>false,'message'=>'Please login first.']);
exit;
}

$orderId=
(int)(
$_POST['order_id']
??
0
);

$result=
declineAssignment(
$conn,
$orderId,
$_SESSION['agent_id']
);

echo json_encode([
'success'=>$result
]);
