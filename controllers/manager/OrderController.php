<?php
/**
 * ROLE 2 — RESTAURANT MANAGER: OrderController
 * PDF features:
 * - View incoming orders in real time via AJAX (api/manager/incoming_orders.php)
 * - Accept or reject incoming orders
 * - Update status: Preparing → Ready for Pickup
 * - Active orders dashboard grouped by status
 * - Full order history: customer name, items, total, delivery status
 */

require_once dirname(__DIR__, 3) . '/includes/bootstrap.php';
requireRole(['manager']);

// TODO: dashboard(), accept(), reject(), updateStatus(), history()
