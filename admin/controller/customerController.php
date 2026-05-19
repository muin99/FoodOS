<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../../dirCommon/dbconnect.php';
include '../model/customerModel.php';

$action = $_POST['action'] ?? null;
$id     = $_POST['id'] ?? null;

if ($action && $id) {

    switch ($action) {

        case 'activate':
            activateCustomer($conn, $id);
            break;

        case 'deactivate':
            deactivateCustomer($conn, $id);
            break;
    }

    header("Location: ../view/customers.php");
    exit;
}

$customers = getAllCustomers($conn);