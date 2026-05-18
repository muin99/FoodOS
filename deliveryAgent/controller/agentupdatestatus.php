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