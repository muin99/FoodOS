<?php
session_start();

include '../../dirCommon/dbconnect.php';
include '../model/agentModel.php';

$orders=
getAvailableAssignments($conn);

echo json_encode([
'success'=>true,
'orders'=>$orders
]);