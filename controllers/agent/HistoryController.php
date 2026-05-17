<?php
// HistoryController.php
include "../../config.php";
include "../../models/DeliveryAssignmentModel.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "agent") {
    header("location:../../views/agent/login.php");
    exit();
}

$model   = new DeliveryAssignmentModel($conn);
$history = $model->getHistoryForAgent($_SESSION["user_id"]);
?>
