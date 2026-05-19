<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
header('Content-Type: application/json');

include __DIR__ . '/../../dirCommon/dbconnect.php';
include __DIR__ . '/../model/agentModel.php';

if (($_SESSION['user_role'] ?? '') !== 'agent' || empty($_SESSION['user_id']) || empty($_SESSION['agent_id'])) {
    http_response_code(401);
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
