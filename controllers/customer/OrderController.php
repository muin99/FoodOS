<?php
/**
 * ROLE 1 — CUSTOMER: OrderController
 * PDF features:
 * - Track active order (status badge via AJAX polling — see api/customer/order_status.php)
 * - View complete order history
 * - Re-order: add all previous order items to cart with one click
 * - Cancel pending order before restaurant has accepted it
 */

require_once dirname(__DIR__, 3) . '/includes/bootstrap.php';
requireRole(['customer']);

// TODO: history(), track($orderId), reorder($orderId), cancel($orderId)
