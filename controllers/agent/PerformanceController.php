<?php
// PerformanceController.php
include "../../config.php";
include "../../models/DeliveryAgentModel.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "agent") {
    header("location:../../views/agent/login.php");
    exit();
}

$model = new DeliveryAgentModel($conn);
$stats = $model->getPerformanceStats($_SESSION["user_id"]);
?>
