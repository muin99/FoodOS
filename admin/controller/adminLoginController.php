<?php

session_start();

header('Content-Type: application/json');

include '../../dirCommon/dbconnect.php';
include '../model/adminModel.php';

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if ($email == '' || $password == '') {
    echo json_encode([
        'success' => false,
        'message' => 'Email and password are required.'
    ]);
    exit;
}

$admin = adminLogin($conn, $email);

if ($admin == false) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid admin email or password.'
    ]);
    exit;
}

if (!password_verify($password, $admin['password'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Wrong password.'
    ]);
    exit;
}

if ($admin['role'] !== 'admin') {
    echo json_encode([
        'success' => false,
        'message' => 'Unauthorized access.'
    ]);
    exit;
}

$_SESSION['admin_id']    = $admin['id'];
$_SESSION['admin_name']  = $admin['name'];
$_SESSION['admin_email'] = $admin['email'];
$_SESSION['admin_role']  = $admin['role'];

echo json_encode([
    'success' => true,
    'redirect' => '../views/adminDashboard.php'
]);