<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
header('Content-Type: application/json');

include __DIR__ . '/../../dirCommon/dbconnect.php';
include __DIR__ . '/../model/loginRegistration.php';

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

if (isset($user['is_approved']) && $user['is_approved'] !== null && (int)$user['is_approved'] !== 1) {
    echo json_encode([
        'success' => false,
        'message' => 'Your restaurant account is pending admin approval.'
    ]);
    exit;
}

$_SESSION['user_id'] = $user['id'];
$_SESSION['user_name'] = $user['name'];
$_SESSION['user_email'] = $user['email'];
$_SESSION['user_role'] = $user['role'];
if (!empty($user['restaurant_id'])) {
    $_SESSION['restaurant_id'] = $user['restaurant_id'];
}

echo json_encode([
    'success' => true,
    'redirect' => '../restaurantManager/view/dashboard.php'
]);
