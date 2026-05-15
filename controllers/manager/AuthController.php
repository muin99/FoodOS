<?php
/**
 * ROLE 2 — RESTAURANT MANAGER: AuthController
 * PDF features:
 * - Register restaurant account; submit details for admin approval
 * - Log in after approval
 */

require_once dirname(__DIR__, 3) . '/includes/bootstrap.php';
// requireRole(['manager']); — call only after login; register allowed before approval

// TODO: registerRestaurant(), login(), logout() — block login until is_approved
