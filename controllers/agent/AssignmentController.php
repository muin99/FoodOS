<?php
/**
 * ROLE 3 — DELIVERY AGENT: AssignmentController
 * PDF features:
 * - View available assignments: orders Ready for Pickup and unassigned
 * - Accept assignment: restaurant name/address, customer address, items, instructions
 * - Decline assignment (returns to available pool)
 * - Update status: Picked Up → On the Way → Delivered (reflected on customer tracking via AJAX)
 * - View current active delivery while in progress
 */

require_once dirname(__DIR__, 3) . '/includes/bootstrap.php';
requireRole(['agent']);

// TODO: available(), accept(), decline(), updateStatus(), active()
