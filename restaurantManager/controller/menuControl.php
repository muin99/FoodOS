<?php
include __DIR__ . '/managerSession.php';
managerRequireJson();
include __DIR__ . '/../../dirCommon/dbconnect.php';

$action = $_POST['action'] ?? '';
$id = (int)($_POST['id'] ?? 0);
$managerId = (int)$_SESSION['user_id'];

if ($action == 'add') {
    $categoryId = (int)($_POST['category_id'] ?? 0);
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = (float)($_POST['price'] ?? 0);
    $imagePath = trim($_POST['image_path'] ?? 'restaurantManager/assets/images/burger.png');
    $isAvailable = isset($_POST['is_available']) && (int)$_POST['is_available'] === 0 ? 0 : 1;

    if ($categoryId <= 0 || $name == '' || $price <= 0) {
        echo json_encode(['success' => false, 'message' => 'Please fill item name, category, and price.']);
        exit;
    }

    $restaurantSql = "
        SELECT r.id
        FROM restaurants r
        INNER JOIN menu_categories c ON c.restaurant_id = r.id
        WHERE r.manager_id = ? AND c.id = ?
        LIMIT 1
    ";
    $restaurantStmt = mysqli_prepare($conn, $restaurantSql);
    mysqli_stmt_bind_param($restaurantStmt, "ii", $managerId, $categoryId);
    mysqli_stmt_execute($restaurantStmt);
    $restaurantResult = mysqli_stmt_get_result($restaurantStmt);
    $restaurant = mysqli_fetch_assoc($restaurantResult);

    if (!$restaurant) {
        echo json_encode(['success' => false, 'message' => 'Category not found for this restaurant.']);
        exit;
    }

    $restaurantId = (int)$restaurant['id'];
    $stmt = mysqli_prepare($conn, "
        INSERT INTO menu_items (restaurant_id, category_id, name, description, price, image_path, is_available)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    mysqli_stmt_bind_param($stmt, "iissdsi", $restaurantId, $categoryId, $name, $description, $price, $imagePath, $isAvailable);

    echo json_encode([
        'success' => mysqli_stmt_execute($stmt),
        'message' => mysqli_stmt_error($stmt) ? 'Item could not be added.' : 'Item added successfully.'
    ]);
    exit;
}

if(!$id) {
    echo json_encode(['success'=>false,'message'=>'Invalid item ID']);
    exit;
}

if($action == 'update'){
    $price = $_POST['price'] ?? 0;
    $isAvailable = $_POST['is_available'] ?? 0;

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
