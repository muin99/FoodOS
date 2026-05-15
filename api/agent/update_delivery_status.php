<?php
/**
 * ROLE 3 — DELIVERY AGENT: AJAX API (required)
 * PDF: Update delivery status (Picked Up → On the Way → Delivered); each update reflected
 * immediately via AJAX on the customer's tracking page.
 * Return JSON: { "success": true, "status": "..." }
 */

header('Content-Type: application/json');
require_once dirname(__DIR__, 2) . '/includes/bootstrap.php';
requireRole(['agent']);

// TODO: POST assignment_id, new_status — update delivery_assignments + orders.status

echo json_encode(['success' => false]);
