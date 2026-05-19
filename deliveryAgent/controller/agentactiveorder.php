<?php
session_start();
header('Content-Type: application/json');

include '../../dirCommon/dbconnect.php';
include '../model/agentModel.php';

if (($_SESSION['user_role'] ?? '') !== 'agent') {
    echo json_encode(['success' => false, 'message' => 'Please login as an agent.']);
    exit;
}

$activeDelivery = getAgentActiveDelivery($conn, (int)$_SESSION['agent_id']);
$profile = getAgentProfile($conn, (int)$_SESSION['user_id']);

echo json_encode([
    'success' => true,
    'active_delivery' => $activeDelivery,
    'profile' => $profile
]);
