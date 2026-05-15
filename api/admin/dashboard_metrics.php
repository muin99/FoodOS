<?php
/**
 * ROLE 4 — PLATFORM ADMIN: AJAX API (required per submission checklist)
 * PDF checklist: at least one AJAX-based feature per role.
 * Suggested: poll dashboard metrics (restaurants, orders today, users, active agents) as JSON
 * for live admin dashboard without full page reload.
 */

header('Content-Type: application/json');
require_once dirname(__DIR__, 2) . '/includes/bootstrap.php';
requireRole(['admin']);

// TODO: return { restaurants, orders_today, users, active_agents }

echo json_encode(['metrics' => []]);
