<?php
session_start();

include '../../dirCommon/dbconnect.php';
include '../model/agentModel.php';

$history=
getAgentDeliveryHistory(
$conn,
$_SESSION['agent_id']
);

echo json_encode([
'success'=>true,
'history'=>$history
]);