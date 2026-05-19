<?php

$pageTitle  = 'Restaurants - Admin';
$activePage = 'restaurants';
$basePath   = '../../';

include '../controller/restaurantController.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $pageTitle ?></title>

<link rel="stylesheet" href="../assets/css/adminDashboard.css">

</head>

<body>

<div class="admin-wrap">

  <!-- SIDEBAR -->
  <?php include __DIR__ . '/../partials/sidebar.php'; ?>

  <!-- MAIN -->
  <div class="main">

    <!-- TOPBAR -->
    <div class="topbar">
      <div>
        <h2>Restaurant Management</h2>
      </div>

      <div class="search-box">
        <input type="text" placeholder="Search restaurants...">
      </div>
    </div>

    <!-- CARDS (same dashboard style) -->
    <div class="cards">

      <div class="card">
        <p>Total Restaurants</p>
        <h3><?= count($restaurants) ?></h3>
      </div>

      <div class="card">
        <p>Pending</p>
        <h3>
          <?= count(array_filter($restaurants, fn($r) => $r['is_approved'] == 0)) ?>
        </h3>
      </div>

      <div class="card">
        <p>Approved</p>
        <h3>
          <?= count(array_filter($restaurants, fn($r) => $r['is_approved'] == 1)) ?>
        </h3>
      </div>

      <div class="card">
        <p>Closed</p>
        <h3>
          <?= count(array_filter($restaurants, fn($r) => $r['is_open'] == 0)) ?>
        </h3>
      </div>

    </div>

    <!-- TABLE SECTION (dashboard style) -->
    <div class="content">

      <div class="table-section">

        <div class="table-header">
          <h3>Restaurant List</h3>
          <button class="export-btn">Export CSV</button>
        </div>

        <table>

          <thead>
            <tr>
              <th>Restaurant</th>
              <th>Manager</th>
              <th>City</th>
              <th>Status</th>
              <th>Approval</th>
            </tr>
          </thead>

          <tbody>

          <?php foreach ($restaurants as $r): ?>

            <tr>

              <td>
                <div class="user-info">
                  <img src="../assets/default-restaurant.png">
                  <span><?= htmlspecialchars($r['name']) ?></span>
                </div>
              </td>

              <td>
                <?= $r['manager_name'] ?? 'N/A' ?>
              </td>

              <td><?= $r['city'] ?></td>

              <td>
                <?php if ($r['is_open'] == 1): ?>
                  <span class="status active-status">Open</span>
                <?php else: ?>
                  <span class="status suspended">Closed</span>
                <?php endif; ?>
              </td>

              <td>
                <?php if ($r['is_approved'] == 0): ?>

                  <div class="btn-group">
                    <button class="approve-btn">✔</button>
                    <button class="reject-btn">✖</button>
                  </div>

                <?php else: ?>

                  <span class="status active-status">Approved</span>

                <?php endif; ?>
              </td>

            </tr>

          <?php endforeach; ?>

          </tbody>

        </table>

      </div>

      <!-- RIGHT PANEL (same dashboard structure) -->
      <div class="right-panel">

        <div class="box">

          <h3>Restaurant Actions</h3>

          <p style="font-size:13px;color:#666;">
            Manage approval, suspension and onboarding here.
          </p>

        </div>

        <div class="box settings">

          <h3>Quick Info</h3>

          <p>Total: <?= count($restaurants) ?></p>

        </div>

      </div>

    </div>

  </div>
</div>

</body>
</html>