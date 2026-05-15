<?php
/**
 * ROLE 4 — PLATFORM ADMIN: DashboardController
 * PDF features:
 * - Admin dashboard: total active restaurants, orders today, registered users, active delivery agents
 * - Platform-wide analytics: revenue, orders per status, top restaurants, busiest agents, peak hours
 * - Delivery performance: avg delivery time, on-time rate, failed deliveries
 * - Generate/view monthly summary reports
 */

require_once dirname(__DIR__, 3) . '/includes/bootstrap.php';
requireRole(['admin']);

// TODO: index(), analytics(), deliveryReport(), monthlyReport()
