<?php
/**
 * HELPERS
 * -------
 * Shared utility functions (escaping output, redirects, flash messages).
 * No business logic for specific roles — keep role logic in controllers.
 */

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function redirect(string $path): void
{
    header('Location: ' . BASE_URL . $path);
    exit;
}

function setFlash(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}
