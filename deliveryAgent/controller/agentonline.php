<?php
session_start();

include '../../dirCommon/dbconnect.php';
include '../model/agentModel.php';

if(($_SESSION['user_role'] ?? '')!='agent' || !isset($_SESSION['user_id'])){
echo json_encode([
'success'=>false
]);
exit;
}

$isOnline=
isset($_POST['is_online'])
?
(int)$_POST['is_online']
:
0;

$result=
toggleAgentOnlineStatus(
$conn,
$_SESSION['user_id'],
$isOnline
);

echo json_encode([
'success'=>$result
]);
