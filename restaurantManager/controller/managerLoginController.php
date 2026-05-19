<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
header('Content-Type: application/json');

include __DIR__ . '/../../dirCommon/dbconnect.php';
include __DIR__ . '/../model/managerModel.php';

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if ($email == '' || $password == '') {
    echo json_encode(['success' => false, 'message' => 'Email and password are required.']);
    exit;
}

$manager = managerLogin($conn, $email, $password);

if ($manager == false) {
    echo json_encode(['success' => false, 'message' => 'Invalid manager email or password.']);
    exit;
}

if (isset($manager['is_approved']) && $manager['is_approved'] !== null && $manager['is_approved'] != 1) {
    echo json_encode(['success' => false, 'message' => 'Your restaurant account is pending admin approval.']);
    exit;
}

$_SESSION['user_id'] = $manager['id'];
$_SESSION['user_name'] = $manager['name'];
$_SESSION['user_email'] = $manager['email'];
$_SESSION['user_role'] = $manager['role'];

if (isset($manager['restaurant_id'])) {
    $_SESSION['restaurant_id'] = $manager['restaurant_id'];
}

echo json_encode([
    'success' => true,
    'redirect' => '../restaurantManager/view/dashboard.php'
]);
