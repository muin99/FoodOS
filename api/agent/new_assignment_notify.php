<?php
/**
 * ROLE 3 — DELIVERY AGENT: AJAX API (required)
 * PDF: Visual notification (AJAX-driven) when new assignment available while agent is online.
 * Poll while is_online=1; return JSON: { "has_new": true, "assignment": {...} }
 */

header('Content-Type: application/json');
require_once dirname(__DIR__, 2) . '/includes/bootstrap.php';
requireRole(['agent']);

// TODO: check for ready unassigned orders near agent (or all available per PDF)

echo json_encode(['has_new' => false]);
