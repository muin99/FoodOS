<?php
/**
 * ROLE 3 — DELIVERY AGENT: DashboardController
 * Shows quick summary: online status, active delivery, earnings today.
 */

require_once dirname(__DIR__, 3) . '/includes/bootstrap.php';
requireRole(['agent']);

require_once BASE_PATH . '/models/DeliveryAgentModel.php';
require_once BASE_PATH . '/models/DeliveryAssignmentModel.php';

$agentModel      = new DeliveryAgentModel();
$assignmentModel = new DeliveryAssignmentModel();

$agent           = $agentModel->getByUserId($_SESSION['user_id']);
$activeDelivery  = $assignmentModel->getActiveForAgent($_SESSION['user_id']);
$earnings        = $agentModel->getEarningsSummary($_SESSION['user_id']);
$availableCount  = count($assignmentModel->getAvailable());

require_once BASE_PATH . '/views/agent/dashboard.php';
