<?php
session_start();
header('Content-Type: application/json');

include '../../dirCommon/dbconnect.php';
include '../model/agentModel.php';

if (($_SESSION['user_role'] ?? '') !== 'agent') {
echo json_encode(['success'=>false,'message'=>'Please login as an agent.']);
exit;
}

$lastChecked=
$_POST['last_checked']
??
date(
'Y-m-d H:i:s',
strtotime('-1 minute')
);

$count=
getNewAssignmentsSince(
$conn,
$lastChecked
);

echo json_encode([
'success'=>true,
'new_count'=>$count
]);
