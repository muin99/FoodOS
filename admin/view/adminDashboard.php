<?php

$pageTitle  = 'BiteBuddy - Admin Dashboard';
$activePage = 'dashboard';
$basePath   = '../../';
$extraCss   = ['../assets/css/adminDashboard.css'];
include '../controller/dashboardController.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $pageTitle ?></title>

  <?php
  if (!empty($extraCss)) {
      foreach ($extraCss as $css) {
          echo '<link rel="stylesheet" href="' . $css . '">';
      }
  }
  ?>
</head>
<body>

<div class="admin-wrap">

  <?php include __DIR__ . '/../partials/sidebar.php'; ?>

  <div class="main">

    <div class="topbar">
      <div>
        <h2>Platform Overview</h2>
      </div>

    </div>

    <div class="cards">

      <div class="card">
        <p>Total Users</p>
        <h3><?= $totalUsers ?></h3>
      </div>

      <div class="card">
        <p>Active Restaurants</p>
         <h3><?= $totalRestaurants ?></h3>
      </div>

      <div class="card">
        <p>Active Delivery Agents</p>
        <h3><?= $totalActiveAgents ?></h3>
      </div>

      <div class="card">
        <p>Total Orders Today</p>
        <h3><?= $totalOrdersToday ?></h3>
      </div>

    </div>

<div class="content">

  <div class="table-section">

    <div class="table-header">
      <h3>User List</h3>
      <!-- <button class="export-btn">Export CSV</button> -->
    </div>

    <table>
      <thead>
        <tr>
          <th>User</th>
          <th>Role</th>
          <th>Orders</th>
          <th>Status</th>
        </tr>
      </thead>

      <tbody>

      <?php foreach ($users as $user): ?>

      <tr>
        <td>
          <div class="user-info">
            <img src="https://i.pravatar.cc/10<?= $user['id'] ?>" alt="user">
            <span><?= htmlspecialchars($user['name']) ?></span>
          </div>
        </td>

        <td><?= ucfirst($user['role']) ?></td>

        <td><?= $user['total_orders'] ?? 0 ?></td>

        <td>
          <?php if ($user['is_active'] == 1): ?>
            <span class="status active-status">Active</span>
          <?php else: ?>
            <span class="status suspended">Inactive</span>
          <?php endif; ?>
        </td>
      </tr>

      <?php endforeach; ?>

      </tbody>
    </table>

  </div>

</div>

  </div>

</div>

</body>
</html>