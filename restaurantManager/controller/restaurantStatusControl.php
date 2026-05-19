<?php

include __DIR__ . '/managerSession.php';
managerRequireJson();
include __DIR__ . '/../../dirCommon/dbconnect.php';

$managerId = (int)$_SESSION['user_id'];
$isOpen = isset($_POST['is_open']) && (int)$_POST['is_open'] === 1 ? 1 : 0;

$restaurantStmt = mysqli_prepare($conn, "SELECT id FROM restaurants WHERE manager_id = ? ORDER BY id ASC LIMIT 1");
mysqli_stmt_bind_param($restaurantStmt, "i", $managerId);
mysqli_stmt_execute($restaurantStmt);
$restaurantResult = mysqli_stmt_get_result($restaurantStmt);
$restaurant = mysqli_fetch_assoc($restaurantResult);

if (!$restaurant) {
    echo json_encode([
        'success' => false,
        'message' => 'Restaurant not found for this manager.'
    ]);
    exit;
}

$restaurantId = (int)$restaurant['id'];
$stmt = mysqli_prepare($conn, "UPDATE restaurants SET is_open = ? WHERE id = ? AND manager_id = ?");
mysqli_stmt_bind_param($stmt, "iii", $isOpen, $restaurantId, $managerId);
$success = mysqli_stmt_execute($stmt);

echo json_encode([
    'success' => $success,
    'is_open' => $isOpen,
    'message' => $success ? 'Restaurant status updated.' : 'Could not update restaurant status.'
]);
