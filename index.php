<?php
/**
 * FRONT CONTROLLER / ENTRY POINT
 * ------------------------------
 * Route requests to the correct role controller.
 * Ensure each role has its own login and dashboard (submission checklist).
 *
 * Suggested routing (implement as needed):
 * - ?role=customer&action=login
 * - Or separate entry files per role if preferred for group separation
 */

require_once __DIR__ . '/includes/bootstrap.php';

// TODO: Simple router — dispatch to controllers/customer|manager|agent|admin

echo '<h1>FoodOS — Online Food Ordering System</h1>';
echo '<p>Project scaffold. Implement routing and role dashboards per PDF.</p>';
echo '<ul>';
echo '<li><a href="' . BASE_URL . '/controllers/customer/">Customer</a></li>';
echo '<li><a href="' . BASE_URL . '/controllers/manager/">Restaurant Manager</a></li>';
echo '<li><a href="' . BASE_URL . '/controllers/agent/">Delivery Agent</a></li>';
echo '<li><a href="' . BASE_URL . '/controllers/admin/">Platform Admin</a></li>';
echo '</ul>';
