<?php
include '../../dirCommon/dbconnect.php';

$id = $_POST['id'] ?? null;
$action = $_POST['action'] ?? '';

if(!$id) {
    echo json_encode(['success'=>false,'message'=>'Invalid item ID']);
    exit;
}

if($action == 'update'){
    $price = $_POST['price'] ?? 0;
    $isAvailable = $_POST['is_available'] ?? 0;

    $stmt = mysqli_prepare($conn, "UPDATE menu_items SET price=?, is_available=? WHERE id=?");
    mysqli_stmt_bind_param($stmt, "dii", $price, $isAvailable, $id);
    if(mysqli_stmt_execute($stmt)){
        echo json_encode(['success'=>true,'message'=>'Item updated successfully']);
    } else {
        echo json_encode(['success'=>false,'message'=>'Update failed']);
    }
}

if($action == 'delete'){
    $stmt = mysqli_prepare($conn, "DELETE FROM menu_items WHERE id=?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    if(mysqli_stmt_execute($stmt)){
        echo json_encode(['success'=>true,'message'=>'Item deleted successfully']);
    } else {
        echo json_encode(['success'=>false,'message'=>'Delete failed']);
    }
}
?>