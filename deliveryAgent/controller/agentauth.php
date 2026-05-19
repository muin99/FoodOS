<?php
session_start();
header('Content-Type: application/json');

include '../../dirCommon/dbconnect.php';
include '../model/agentModel.php';

if (($_SESSION['user_role'] ?? '') !== 'agent' || empty($_SESSION['user_id']) || empty($_SESSION['agent_id'])) {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'message' => 'Please login as an agent.',
        'redirect' => '/FoodOS/dirCommon/login.html?tab=agent-login'
    ]);
    exit;
}

$profile = getAgentProfile($conn, (int)$_SESSION['user_id']);

if ($profile === null) {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'message' => 'Agent profile was not found. Please login again.',
        'redirect' => '/FoodOS/dirCommon/login.html?tab=agent-login'
    ]);
    exit;
}

echo json_encode([
    'success' => true,
    'agent_id' => (int)$_SESSION['agent_id'],
    'profile' => $profile
]);
