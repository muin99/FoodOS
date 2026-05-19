<?php
session_start();

include '../../dirCommon/dbconnect.php';
include '../model/agentModel.php';

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