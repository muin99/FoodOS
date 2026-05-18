<?php
session_start();

include '../../dirCommon/dbconnect.php';
include '../model/agentModel.php';

$earnings=
getAgentEarningsSummary(
$conn,
$_SESSION['agent_id']
);

echo json_encode([
'success'=>true,
'earnings'=>$earnings
]);