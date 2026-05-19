<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
header('Content-Type: application/json');
include '../../dirCommon/dbconnect.php';
include '../model/deliveryagentmodel.php';

$firstName    = $_POST['firstname']       ?? '';
$lastName     = $_POST['lastname']        ?? '';
$email        = $_POST['email']           ?? '';
$phone        = $_POST['phone']           ?? '';
$password     = $_POST['password']        ?? '';
$confirmPassword = $_POST['confirm_password'] ?? '';
$vehicleType  = $_POST['vehicle_type']    ?? '';
$accountType  = $_POST['account_type']    ?? '';


if ($accountType != 'agent') {
    echo json_encode(['success' => false, 'message' => 'Please select Agent registration.']);
    exit;
}


if ($firstName == '' || $lastName == '' || $email == '' || $phone == '' || $password == '' || $vehicleType == '') {
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

if (agentEmailExists($conn, $email)) {
    echo json_encode(['success' => false, 'message' => 'This email is already registered.']);
    exit;
}

$name = trim($firstName . ' ' . $lastName);


$result = agentRegister($conn, $name, $email, $phone, $password, $vehicleType);

if ($result == false) {
    echo json_encode(['success' => false, 'message' => 'Registration failed. Please try again.']);
    exit;
}


echo json_encode([
    'success' => true,
    'message' => 'Registration successful! Your account is pending admin approval. You will be notified once approved.',
    'redirect' => 'login.html'
]);
