<?php
/**
 * AUTHENTICATION & ROLE-BASED ACCESS CONTROL
 * ------------------------------------------
 * PDF requirement: PHP sessions with RBAC on every protected page.
 *
 * Implement:
 * - isLoggedIn()
 * - currentUser() — from session
 * - requireRole(string ...$roles) — redirect/deny if role mismatch
 * - loginUser / logoutUser helpers used by role controllers
 *
 * Roles: customer | manager | agent | admin
 * Must prevent any user from accessing another role's pages.
 */

function isLoggedIn(): bool
{
    return isset($_SESSION['user_id']);
}

function currentUserRole(): ?string
{
    return $_SESSION['role'] ?? null;
}

function requireRole(array $allowedRoles): void
{
    if (!isLoggedIn() || !in_array(currentUserRole(), $allowedRoles, true)) {
        header('Location: ' . BASE_URL . '/index.php');
        exit;
    }
}

function logoutUser(): void
{
    session_destroy();
}
