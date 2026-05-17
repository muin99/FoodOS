<?php
// EarningsController.php
include "../../config.php";
include "../../models/DeliveryAgentModel.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "agent") {
    header("location:../../views/agent/login.php");
    exit();
}

$model    = new DeliveryAgentModel($conn);
$earnings = $model->getEarningsSummary($_SESSION["user_id"]);
?>
