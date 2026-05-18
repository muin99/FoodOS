<?php
session_start();

include '../../dirCommon/dbconnect.php';
include '../model/loginRegistration.php';

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if ($email == '' || $password == '') {
    echo json_encode([
        'success' => false,
        'message' => 'Email and password are required.'
    ]);
    exit;
}

$user = managerLogin($conn, $email, $password);



if ($user == false) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid manager email or password.'
    ]);
    exit;
}

$_SESSION['user_id'] = $user['id'];
$_SESSION['user_name'] = $user['name'];
$_SESSION['user_email'] = $user['email'];
$_SESSION['user_role'] = $user['role'];

echo json_encode([
    'success' => true,
    'redirect' => '../restaurantManager/view/dashboard.php'
]);