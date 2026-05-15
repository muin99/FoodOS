<?php
/**
 * ROLE 3 — DELIVERY AGENT: AuthController
 * PDF features:
 * - Register as delivery agent (name, phone, vehicle type); submit for admin approval
 * - Log in after approval
 */

require_once dirname(__DIR__, 3) . '/includes/bootstrap.php';
// requireRole(['agent']); — call only after login; register allowed before approval

// TODO: register(), login(), logout() — block until delivery_agents.is_approved
