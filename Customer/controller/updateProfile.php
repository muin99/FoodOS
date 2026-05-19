<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../../dirCommon/dbconnect.php';
include '../model/customerModel.php';

if (($_SESSION['user_role'] ?? '') !== 'customer' || !isset($_SESSION['user_id'])) {
    header('Location: ../../dirCommon/login.html');
    exit;
}

$name = trim($_POST['name'] ?? '');
$phone = trim($_POST['phone'] ?? '');

if ($name !== '' && updateCustomerProfile($conn, (int)$_SESSION['user_id'], $name, $phone)) {
    $_SESSION['user_name'] = $name;
}

header('Location: ../view/profile.php');
exit;
