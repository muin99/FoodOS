<?php

$pageTitle  = 'Restaurants - Admin';
$activePage = 'restaurants';
$basePath   = '../../';

include '../controller/restaurantController.php';

if (!isset($restaurants)) {
    $restaurants = [];
}

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

  <?php include __DIR__ . '/../partials/sidebar.php'; ?>

  <div class="main">

    <div class="topbar">
      <div>
        <h2>Restaurant Management</h2>
      </div>

      <div class="search-box">
        <input type="text" placeholder="Search restaurants...">
      </div>
    </div>

    <!-- CARDS -->
    <div class="cards">

      <div class="card">
        <p>Total Restaurants</p>
        <h3><?= count($restaurants) ?></h3>
      </div>

      <div class="card">
        <p>Pending</p>
        <h3><?= count(array_filter($restaurants, fn($r) => $r['is_approved'] == 1)) ?></h3>
      </div>

      <div class="card">
        <p>Approved</p>
        <h3><?= count(array_filter($restaurants, fn($r) => $r['is_approved'] == 2)) ?></h3>
      </div>

      <div class="card">
        <p>Blocked</p>
        <h3><?= count(array_filter($restaurants, fn($r) => $r['is_approved'] == 3)) ?></h3>
      </div>

    </div>

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
              <th>Actions</th>
            </tr>
          </thead>

          <tbody>

          <?php foreach ($restaurants as $r): ?>
          <?php if ($r['is_approved'] != 1): ?>

            <tr>

              <!-- RESTAURANT INFO -->
              <td>
                <div class="user-info">
                  <img src="../assets/default-restaurant.png" alt="restaurant">
                  <span><?= htmlspecialchars($r['name']) ?></span>
                </div>
              </td>

              <!-- MANAGER -->
              <td>
                <?= htmlspecialchars($r['manager_name'] ?? 'N/A') ?>
              </td>

              <!-- CITY -->
              <td>
                <?= htmlspecialchars($r['city']) ?>
              </td>

              <!-- STATUS -->
              <td>
                <?php if ($r['is_approved'] == 2): ?>
                  <span class="status active-status">Approved</span>
                <?php elseif ($r['is_approved'] == 3): ?>
                  <span class="status suspended">Blocked</span>
                <?php endif; ?>
              </td>

              <!-- ACTIONS -->
              <td>
                <form method="POST" action="../controller/restaurantController.php" style="display:flex; gap:5px;">
                  <input type="hidden" name="id" value="<?= $r['id'] ?>">

                  <?php if ($r['is_approved'] == 2): ?>
                    <button type="submit" name="action" value="block" class="reject-btn">
                      Block
                    </button>

                  <?php elseif ($r['is_approved'] == 3): ?>
                    <button type="submit" name="action" value="approve" class="approve-btn">
                      Re-Activate
                    </button>

                  <?php endif; ?>

                </form>
              </td>

            </tr>

          <?php endif; ?>
          <?php endforeach; ?>

          </tbody>

        </table>

      </div>

      <!-- RIGHT PANEL — Pending Approvals -->
      <div class="right-panel">

        <div class="box">
          <h3>Pending Approvals</h3>
          <p style="font-size:13px;color:#666;">
            <!-- New restaurants waiting for approval. -->
             <?php $pendingList = array_filter($restaurants, fn($r) => $r['is_approved'] == 1); ?>

        <?php if (count($pendingList) === 0): ?>

          <div class="box">
            <p style="font-size:13px;color:#999;">No pending restaurants.</p>
          </div>

        <?php else: ?>

          <?php foreach ($pendingList as $p): ?>

            <div class="box" style="margin-bottom:10px;">

              <p style="font-weight:600;margin:0;"><?= htmlspecialchars($p['name']) ?></p>
              <p style="font-size:12px;color:#888;margin:4px 0;"><?= htmlspecialchars($p['city']) ?></p>
              <p style="font-size:12px;color:#888;margin:4px 0;">
                Manager: <?= htmlspecialchars($p['manager_name'] ?? 'N/A') ?>
              </p>

              <form method="POST" action="../controller/restaurantController.php" style="margin-top:8px;">
                <input type="hidden" name="id" value="<?= $p['id'] ?>">
                <button type="submit" name="action" value="approve" class="approve-btn" style="width:100%;">
                  Approve
                </button>
              </form>

            </div>

          <?php endforeach; ?>

        <?php endif; ?>
          </p>
        </div>

      </div>

    </div>

  </div>
</div>

</body>
</html>