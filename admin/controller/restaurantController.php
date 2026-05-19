<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../../dirCommon/dbconnect.php';
include '../model/restaurantModel.php';

$action = $_POST['action'] ?? null;
$id     = $_POST['id'] ?? null;

if ($action && $id) {
    switch ($action) {
        case 'approve': approveRestaurant($conn, $id); break;
        case 'block':   blockRestaurant($conn, $id);   break;
        case 'pending': pendingRestaurant($conn, $id); break;
    }
    header("Location: ../view/restaurants.php");
    exit;
}

$restaurants = getAllRestaurants($conn);