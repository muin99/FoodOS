<?php
// AJAX API: Update delivery status
// POST: assignment_id, new_status
// Returns JSON: { "success": true, "status": "picked_up" }

include "../../config.php";
include "../../models/DeliveryAssignmentModel.php";

header("Content-Type: application/json");

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "agent") {
    echo json_encode(["success" => false, "message" => "Not logged in."]);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    echo json_encode(["success" => false, "message" => "POST required."]);
    exit();
}

$assignment_id = (int)($_POST["assignment_id"] ?? 0);
$new_status    = trim($_POST["new_status"]     ?? "");

$allowed = ["picked_up", "on_the_way", "delivered"];
if ($assignment_id <= 0 || !in_array($new_status, $allowed)) {
    echo json_encode(["success" => false, "message" => "Invalid parameters."]);
    exit();
}

$model  = new DeliveryAssignmentModel($conn);
$result = $model->updateStatus($assignment_id, $_SESSION["user_id"], $new_status);

echo json_encode([
    "success" => $result,
    "status"  => $new_status,
    "message" => $result ? "Status updated." : "Could not update status."
]);
?>
