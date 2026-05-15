<?php
/**
 * ROLE 1 — CUSTOMER: AJAX API (required)
 * PDF: Track active order status in real time — status badge updates via AJAX polling
 * without full page reload. Return JSON: { "status": "...", "estimated_minutes": N }
 * XMLHttpRequest → this endpoint → JSON response.
 */

header('Content-Type: application/json');
require_once dirname(__DIR__, 2) . '/includes/bootstrap.php';
requireRole(['customer']);

// TODO: GET order_id — validate ownership — return current order status from OrderModel

echo json_encode(['error' => 'Not implemented']);
