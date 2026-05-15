<?php
/**
 * ROLE 4 — PLATFORM ADMIN: RestaurantController
 * PDF features:
 * - View all restaurants (pending approval and approved)
 * - Approve or reject new registrations; suspend or reactivate
 * - Manage featured restaurants on customer homepage (add/remove)
 */

require_once dirname(__DIR__, 3) . '/includes/bootstrap.php';
requireRole(['admin']);

// TODO: index(), approve(), reject(), suspend(), feature(), unfeature()
