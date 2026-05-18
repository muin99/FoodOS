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
declineAssignment(
$conn,
$orderId,
$_SESSION['agent_id']
);

echo json_encode([
'success'=>$result
]);