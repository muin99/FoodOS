<?php
session_start();
include '../../dirCommon/dbconnect.php';
include '../model/loginRegistration.php';

$email    = $_POST['email']    ?? '';
$password = $_POST['password'] ?? '';

if ($email == '' || $password == '') {
    echo json_encode(['success' => false, 'message' => 'Email and password are required.']);
    exit;
}

$user = agentLogin($conn, $email, $password);

if ($user == false) {
    echo json_encode(['success' => false, 'message' => 'Invalid agent email or password.']);
    exit;
}


if (!$user['is_approved']) {
    echo json_encode(['success' => false, 'message' => 'Your agent account is pending admin approval.']);
    exit;
}

$_SESSION['user_id']    = $user['id'];
$_SESSION['user_name']  = $user['name'];
$_SESSION['user_email'] = $user['email'];
$_SESSION['user_role']  = $user['role'];
$_SESSION['agent_id']   = $user['agent_id'];   

echo json_encode([
    'success'  => true,
    'redirect' => '../Agent/view/agentDashboard.php'
]);