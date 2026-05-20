<?php

include __DIR__ . '/managerSession.php';
managerRequireJson();
include __DIR__ . '/../../dirCommon/dbconnect.php';
include __DIR__ . '/../model/profileModel.php';

$managerId = $_SESSION['user_id'];

$name = $_POST['name'] ?? '';
$description = $_POST['description'] ?? '';
$cuisineType = $_POST['cuisine_type'] ?? '';
$address = $_POST['address'] ?? '';
$city = $_POST['city'] ?? '';
$deliveryRadius = $_POST['delivery_radius_km'] ?? 0;
$isOpen = $_POST['is_open'] ?? 1;

$updated = updateRestaurantProfile(
    $conn,
    $managerId,
    $name,
    $description,
    $cuisineType,
    $address,
    $city,
    $deliveryRadius,
    $isOpen
);

if ($updated) {
    echo json_encode([
        'success' => true,
        'message' => 'Profile updated successfully.'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Update failed. Restaurant row may not exist for this manager.'
    ]);
}
