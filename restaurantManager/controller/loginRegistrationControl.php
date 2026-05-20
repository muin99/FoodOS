<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
header('Content-Type: application/json');

include __DIR__ . '/../../dirCommon/dbconnect.php';
include __DIR__ . '/../model/loginRegistration.php';

$firstName = $_POST['firstname'] ?? '';
$lastName = $_POST['lastname'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$password = $_POST['password'] ?? '';
$confirmPassword = $_POST['confirm_password'] ?? '';
$accountType = $_POST['account_type'] ?? '';
$restaurantName = trim($_POST['restaurant_name'] ?? '');
$cuisineType = trim($_POST['cuisine_type'] ?? '');
$address = trim($_POST['address'] ?? '');

if ($accountType != 'manager') {
    echo json_encode(['success' => false, 'message' => 'Please select Restaurant Manager registration.']);
    exit;
}

if ($firstName == '' || $lastName == '' || $email == '' || $phone == '' || $password == '' || $restaurantName == '' || $cuisineType == '' || $address == '') {
    echo json_encode(['success' => false, 'message' => 'Please fill in all required fields.']);
    exit;
}

if ($password != $confirmPassword) {
    echo json_encode(['success' => false, 'message' => 'Passwords do not match.']);
    exit;
}

if (!isset($_POST['terms'])) {
    echo json_encode(['success' => false, 'message' => 'Please accept the terms and conditions.']);
    exit;
}

if (managerEmailExists($conn, $email)) {
    echo json_encode(['success' => false, 'message' => 'This email is already registered.']);
    exit;
}

$name = trim($firstName . ' ' . $lastName);
$newUserId = managerRegister($conn, $name, $email, $phone, $password, $restaurantName, $cuisineType, $address);

if ($newUserId == false) {
    echo json_encode(['success' => false, 'message' => 'Registration failed.']);
    exit;
}

echo json_encode([
    'success' => true,
    'message' => 'Registration successful. Your restaurant is pending admin approval.',
    'redirect' => 'login.html?tab=manager-login'
]);
