<?php
if (session_status() === PHP_SESSION_NONE) {
session_start();
}
header('Content-Type: application/json');

include '../../dirCommon/dbconnect.php';
include '../model/agentModel.php';

if (($_SESSION['user_role'] ?? '') != 'agent' || !isset($_SESSION['agent_id'], $_SESSION['user_id'])) {
echo json_encode(['success'=>false,'message'=>'Please login first.']);
exit;
}

$stats=
getAgentPerformanceStats(
$conn,
$_SESSION['agent_id'],
$_SESSION['user_id']
);

echo json_encode([
'success'=>true,
'stats'=>$stats
]);
