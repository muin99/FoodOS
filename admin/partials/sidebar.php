<?php
/*
 * Admin Sidebar Partial
 * File: admin/partials/sidebar.php
 * Included by every admin view.
 * Expects $activePage to be set by the including view.
 */
?>

<div class="sidebar">

  <div class="logo">
    <h1>BiteBuddy</h1>
    <p>Admin Console</p>
  </div>

  <div class="profile">
    <img src="https://i.pravatar.cc/100" alt="Admin Avatar">
    <div>
      <h4>Alex Chen</h4>
      <p>Super Admin</p>
    </div>
  </div>

  <nav class="menu">
    <a href="adminDashboard.php"
       class="<?= ($activePage === 'dashboard')   ? 'active' : '' ?>">Dashboard</a>
    <a href="users.php"
       class="<?= ($activePage === 'users')        ? 'active' : '' ?>">Users</a>
    <a href="restaurants.php"
       class="<?= ($activePage === 'restaurants')  ? 'active' : '' ?>">Restaurants</a>
    <a href="agents.php"
       class="<?= ($activePage === 'agents')       ? 'active' : '' ?>">Agents</a>
    <a href="complaints.php"
       class="<?= ($activePage === 'complaints')   ? 'active' : '' ?>">Complaints</a>
    <a href="settings.php"
       class="<?= ($activePage === 'settings')     ? 'active' : '' ?>">Settings</a>
  </nav>

  <div class="bottom-links">
    <a href="#">Help Center</a>
    <button class="logout-btn">Sign Out</button>
  </div>

</div>