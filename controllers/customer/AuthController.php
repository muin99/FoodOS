<?php
/**
 * ROLE 1 — CUSTOMER: AuthController
 * PDF features:
 * - Register with name, email, phone, and password
 * - Log in and log out securely
 * MVC: validate input here; call UserModel; render views/customer/auth/*.php
 * Server-side validation with descriptive error messages required.
 */

require_once dirname(__DIR__, 3) . '/includes/bootstrap.php';
// requireRole(['customer']); — call only on protected actions, NOT on register/login

// TODO: register(), login(), logout()
