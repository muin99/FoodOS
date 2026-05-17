<?php
// AssignmentController.php
include "../../config.php";
include "../../models/DeliveryAssignmentModel.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "agent") {
    header("location:../../views/agent/login.php");
    exit();
}

$model   = new DeliveryAssignmentModel($conn);
$success = "";
$error   = "";

// -------------------------------------------------------
// ACCEPT ASSIGNMENT
// -------------------------------------------------------
if (isset($_POST["accept"])) {
    $order_id = (int)$_POST["order_id"];
    $result   = $model->accept($order_id, $_SESSION["user_id"]);

    if ($result) {
        $success = "Assignment accepted! Head to the restaurant for pickup.";
    } else {
        $error = "This order is no longer available.";
    }
}

// -------------------------------------------------------
// DECLINE ASSIGNMENT
// -------------------------------------------------------
if (isset($_POST["decline"])) {
    $assignment_id = (int)$_POST["assignment_id"];
    $model->decline($assignment_id, $_SESSION["user_id"]);
    $success = "Assignment declined.";
}
?>
