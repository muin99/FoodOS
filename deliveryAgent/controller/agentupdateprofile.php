<?php
session_start();
header('Content-Type: application/json');

include '../../dirCommon/dbconnect.php';
include '../model/agentModel.php';

if (($_SESSION['user_role'] ?? '') != 'agent' || !isset($_SESSION['user_id'])) {
echo json_encode(['success'=>false,'message'=>'Please login first.']);
exit;
}

$name=$_POST['name']??'';
$phone=$_POST['phone']??'';
$vehicleType=$_POST['vehicle_type']??'';
$profilePic=null;

if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
    $allowedTypes = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
    $mimeType = mime_content_type($_FILES['profile_pic']['tmp_name']);

    if (!isset($allowedTypes[$mimeType])) {
        echo json_encode(['success' => false, 'message' => 'Only JPG, PNG, or WEBP profile pictures are allowed.']);
        exit;
    }

    $uploadDir = dirname(__DIR__) . '/uploads/profile';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0775, true);
    }

    $fileName = 'agent_' . (int)$_SESSION['user_id'] . '_' . time() . '.' . $allowedTypes[$mimeType];
    $targetPath = $uploadDir . '/' . $fileName;

    if (!move_uploaded_file($_FILES['profile_pic']['tmp_name'], $targetPath)) {
        echo json_encode(['success' => false, 'message' => 'Profile picture upload failed.']);
        exit;
    }

    $profilePic = '../uploads/profile/' . $fileName;
}

$result=
updateAgentProfile(
$conn,
$_SESSION['user_id'],
$name,
$phone,
$vehicleType,
$profilePic
);

echo json_encode([
'success'=>$result
]);
