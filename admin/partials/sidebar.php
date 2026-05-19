<?php
/*
 * Admin Sidebar Partial
 * File: admin/partials/sidebar.php
 * Included by every admin view.
 * Expects $activePage to be set by the including view.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$adminName  = $_SESSION['admin_name'] ?? 'Admin';
$adminRole  = $_SESSION['admin_role'] ?? 'admin';
?>

<div class="sidebar">

  <div class="logo">
    <h1>BiteBuddy</h1>
    <p>Admin Console</p>
  </div>

  <!-- ===== ADMIN PROFILE ===== -->
  <div class="profile">
    <img src="https://i.pravatar.cc/100" alt="Admin Avatar">

    <div>
      <h4><?= htmlspecialchars($adminName) ?></h4>
      <p><?= ucfirst(htmlspecialchars($adminRole)) ?></p>
    </div>
  </div>

  <!-- ===== MENU ===== -->
  <nav class="menu">
    <a href="adminDashboard.php"
       class="<?= ($activePage === 'dashboard') ? 'active' : '' ?>">
       Dashboard
    </a>

    <a href="users.php"
       class="<?= ($activePage === 'users') ? 'active' : '' ?>">
       Users
    </a>

    <a href="restaurants.php"
       class="<?= ($activePage === 'restaurants') ? 'active' : '' ?>">
       Restaurants
    </a>

    <a href="agents.php"
       class="<?= ($activePage === 'agents') ? 'active' : '' ?>">
       Agents
    </a>

    <a href="complaints.php"
       class="<?= ($activePage === 'complaints') ? 'active' : '' ?>">
       Complaints
    </a>

    <a href="settings.php"
       class="<?= ($activePage === 'settings') ? 'active' : '' ?>">
       Settings
    </a>
  </nav>

  <!-- ===== BOTTOM ===== -->
  <div class="bottom-links">
    <a href="#">Help Center</a>

    <button class="logout-btn" onclick="window.location.href='../controller/logout.php'">
      Sign Out
    </button>
  </div>

</div>