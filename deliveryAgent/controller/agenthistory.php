<?php
session_start();
header('Content-Type: application/json');

include '../../dirCommon/dbconnect.php';
include '../model/agentModel.php';

if (($_SESSION['user_role'] ?? '') !== 'agent') {
echo json_encode(['success'=>false,'message'=>'Please login as an agent.']);
exit;
}

$history=
getAgentDeliveryHistory(
$conn,
$_SESSION['agent_id']
);

echo json_encode([
'success'=>true,
'history'=>$history
]);
