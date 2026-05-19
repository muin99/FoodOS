<?php
include __DIR__ . '/managerSession.php';
managerRequireJson();
include __DIR__ . '/../../dirCommon/dbconnect.php';

$id = (int)($_POST['id'] ?? 0);
$action = $_POST['action'] ?? '';

if(!$id) {
    echo json_encode(['success'=>false,'message'=>'Invalid item ID']);
    exit;
}

if($action == 'update'){
    $price = $_POST['price'] ?? 0;
    $isAvailable = $_POST['is_available'] ?? 0;
    $managerId = (int)$_SESSION['user_id'];

    $stmt = mysqli_prepare($conn, "
        UPDATE menu_items mi
        INNER JOIN restaurants r ON r.id = mi.restaurant_id
        SET mi.price = ?, mi.is_available = ?
        WHERE mi.id = ? AND r.manager_id = ?
    ");
    mysqli_stmt_bind_param($stmt, "diii", $price, $isAvailable, $id, $managerId);
    if(mysqli_stmt_execute($stmt) && mysqli_stmt_affected_rows($stmt) > 0){
        echo json_encode(['success'=>true,'message'=>'Item updated successfully']);
    } else {
        echo json_encode(['success'=>false,'message'=>'Item not found for this restaurant.']);
    }
}

if($action == 'delete'){
    $managerId = (int)$_SESSION['user_id'];
    $stmt = mysqli_prepare($conn, "
        DELETE mi FROM menu_items mi
        INNER JOIN restaurants r ON r.id = mi.restaurant_id
        WHERE mi.id = ? AND r.manager_id = ?
    ");
    mysqli_stmt_bind_param($stmt, "ii", $id, $managerId);
    if(mysqli_stmt_execute($stmt) && mysqli_stmt_affected_rows($stmt) > 0){
        echo json_encode(['success'=>true,'message'=>'Item deleted successfully']);
    } else {
        echo json_encode(['success'=>false,'message'=>'Item not found for this restaurant.']);
    }
}
