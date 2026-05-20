<?php

if (!function_exists('managerRequirePage')) {
function managerRequirePage()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (($_SESSION['user_role'] ?? '') !== 'manager' || empty($_SESSION['user_id'])) {
        header('Location: ../../dirCommon/login.html?tab=manager-login');
        exit;
    }
}
}

if (!function_exists('managerRequireJson')) {
function managerRequireJson()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    header('Content-Type: application/json');

    if (($_SESSION['user_role'] ?? '') !== 'manager' || empty($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode([
            'success' => false,
            'message' => 'Please login as a restaurant manager.'
        ]);
        exit;
    }
}
}
