<?php
session_start();

include '../../dirCommon/dbconnect.php';
include '../model/agentModel.php';

$orderId=
(int)(
$_POST['order_id']
??
0
);

$result=
acceptAssignment(
$conn,
$orderId,
$_SESSION['agent_id'],
$_SESSION['user_id']
);

echo json_encode([
'success'=>$result
]);