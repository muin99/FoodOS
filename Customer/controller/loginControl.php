<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../../dirCommon/dbconnect.php';
include '../model/loginRegistration.php';

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if ($email == '' || $password == '') {
    echo json_encode(['success' => false, 'message' => 'Email and password are required.']);
    exit;
}

$user = customerLogin($conn, $email, $password);

if ($user == false) {
    echo json_encode(['success' => false, 'message' => 'Invalid customer email or password.']);
    exit;
}

$_SESSION['user_id'] = $user['id'];
$_SESSION['user_name'] = $user['name'];
$_SESSION['user_email'] = $user['email'];
$_SESSION['user_role'] = $user['role'];

echo json_encode([
    'success' => true,
    'redirect' => '../Customer/view/browseResturants.php'
]);
