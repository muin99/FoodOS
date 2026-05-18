<?php
session_start();

include '../../dirCommon/dbconnect.php';
include '../model/agentModel.php';

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