<?php
// AJAX API: Poll for new available assignments
// Returns JSON: { "has_new": true, "assignment": { order_id, restaurant_name, ... } }

include "../../config.php";
include "../../models/DeliveryAgentModel.php";
include "../../models/DeliveryAssignmentModel.php";

header("Content-Type: application/json");

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "agent") {
    echo json_encode(["has_new" => false]);
    exit();
}

$agentModel = new DeliveryAgentModel($conn);
$agent      = $agentModel->getByUserId($_SESSION["user_id"]);

// Only notify if agent is online
if (!$agent || !$agent["is_online"]) {
    echo json_encode(["has_new" => false]);
    exit();
}

$model      = new DeliveryAssignmentModel($conn);
$has_new    = $model->hasNewAvailable();
$assignment = $has_new ? $model->getLatestAvailable() : null;

echo json_encode([
    "has_new"    => $has_new,
    "assignment" => $assignment
]);
?>
