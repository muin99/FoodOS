<?php
/**
 * ROLE 2 — RESTAURANT MANAGER: AJAX API (required)
 * PDF: View incoming orders in real time via AJAX — new orders appear without page refresh.
 * Return JSON list of new/pending orders for logged-in manager's restaurant.
 */

header('Content-Type: application/json');
require_once dirname(__DIR__, 2) . '/includes/bootstrap.php';
requireRole(['manager']);

// TODO: return orders where status = pending for manager's restaurant_id

echo json_encode(['orders' => []]);
