<?php
session_start();
header('Content-Type: application/json');
include '../../dirCommon/dbconnect.php';
include '../model/deliveryagentmodel.php';

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

session_regenerate_id(true);

$_SESSION['user_id']    = $user['id'];
$_SESSION['user_name']  = $user['name'];
$_SESSION['user_email'] = $user['email'];
$_SESSION['user_role']  = $user['role'];
$_SESSION['agent_id']   = $user['agent_id'];

echo json_encode([
    'success'  => true,
    'redirect' => '/FoodOS/deliveryAgent/view/dashboard.html?v=agent-dashboard-v2'
]);
