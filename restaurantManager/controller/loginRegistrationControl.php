<?php
session_start();

include '../../dirCommon/dbconnect.php';
include '../model/loginRegistration.php';

$firstName = $_POST['firstname'] ?? '';
$lastName = $_POST['lastname'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$password = $_POST['password'] ?? '';
$confirmPassword = $_POST['confirm_password'] ?? '';
$accountType = $_POST['account_type'] ?? '';

if ($accountType != 'restaurant_manager') {
    echo json_encode(['success' => false, 'message' => 'Please select Restaurant Manager registration.']);
    exit;
}

if ($firstName == '' || $lastName == '' || $email == '' || $phone == '' || $password == '') {
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
$newUserId = managerRegister($conn, $name, $email, $phone, $password);

if ($newUserId == false) {
    echo json_encode(['success' => false, 'message' => 'Registration failed.']);
    exit;
}

$_SESSION['user_id'] = $newUserId;
$_SESSION['user_name'] = $name;
$_SESSION['user_email'] = $email;
$_SESSION['user_role'] = 'manager';

echo json_encode([
    'success' => true,
    'redirect' => '../restaurantManager/view/dashboard.php'
]);