<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
header('Content-Type: application/json');

include __DIR__ . '/../../dirCommon/dbconnect.php';
include __DIR__ . '/../model/agentModel.php';

if (($_SESSION['user_role'] ?? '') != 'agent' || !isset($_SESSION['user_id'], $_SESSION['agent_id'])) {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'message' => 'Please log in as a delivery agent.'
    ]);
    exit;
}

$isOnline = isset($_POST['is_online']) && (int)$_POST['is_online'] === 1 ? 1 : 0;

$result = toggleAgentOnlineStatus($conn, (int)$_SESSION['user_id'], (int)$_SESSION['agent_id'], $isOnline);

echo json_encode([
    'success' => $result,
    'is_online' => $isOnline,
    'message' => $result ? 'Status updated.' : 'Could not update online status.'
]);
