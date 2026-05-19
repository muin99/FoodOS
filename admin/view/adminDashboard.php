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

  <!-- ===== SIDEBAR ===== -->
  <?php include __DIR__ . '/../partials/sidebar.php'; ?>

  <!-- ===== MAIN ===== -->
  <div class="main">

    <!-- TOPBAR -->
    <div class="topbar">
      <div>
        <h2>Platform Overview</h2>
      </div>

      <div class="search-box">
        <input type="text" placeholder="Search resources...">
      </div>
    </div>

    <!-- CARDS -->
    <div class="cards">

      <div class="card">
        <!-- <span class="green">+12%</span> -->
        <p>Total Users</p>
        <!-- <h3>1.2M</h3> -->
        <h3><?= $totalUsers ?></h3>
      </div>

      <div class="card">
        <!-- <span class="green">+5%</span> -->
        <p>Active Restaurants</p>
        <!-- <h3>14,208</h3> -->
         <h3><?= $totalRestaurants ?></h3>
      </div>

      <div class="card">
        <!-- <span class="red">-2%</span> -->
        <p>Active Delivery Agents</p>
        <h3><?= $totalActiveAgents ?></h3>
      </div>

      <div class="card">
        <!-- <span class="green">Stable</span> -->
        <p>Total Orders Today</p>
        <h3><?= $totalOrdersToday ?></h3>
      </div>

    </div>

    <!-- CONTENT -->
    <div class="content">

      <!-- TABLE SECTION -->
      <div class="table-section">

        <div class="table-header">
          <h3>User Management</h3>
          <button class="export-btn">Export CSV</button>
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

            <tr>
              <td>
                <div class="user-info">
                  <img src="https://i.pravatar.cc/101" alt="Sarah Jenkins">
                  <span>Sarah Jenkins</span>
                </div>
              </td>

              <td>Customer</td>
              <td>42</td>
              <td>
                <span class="status active-status">Active</span>
              </td>
            </tr>

            <tr>
              <td>
                <div class="user-info">
                  <img src="https://i.pravatar.cc/102" alt="Mario's Pizza">
                  <span>Mario's Pizza</span>
                </div>
              </td>

              <td>Restaurant Admin</td>
              <td>1,204</td>
              <td>
                <span class="status active-status">Active</span>
              </td>
            </tr>

            <tr>
              <td>
                <div class="user-info">
                  <img src="https://i.pravatar.cc/103" alt="John Doe">
                  <span>John Doe</span>
                </div>
              </td>

              <td>Delivery Agent</td>
              <td>89</td>
              <td>
                <span class="status suspended">Suspended</span>
              </td>
            </tr>

          </tbody>
        </table>

      </div>

      <!-- RIGHT PANEL -->
      <div class="right-panel">

        <!-- PENDING APPROVALS -->
        <div class="box">

          <h3>Pending Approvals</h3>

          <div class="approval-item">
            <div>
              <strong>Burger Haven</strong>
              <p>Restaurant Request</p>
            </div>

            <div class="btn-group">
              <button class="approve-btn">&#10004;</button>
              <button class="reject-btn">&#10006;</button>
            </div>
          </div>

          <div class="approval-item">
            <div>
              <strong>David Wilson</strong>
              <p>Agent Application</p>
            </div>

            <div class="btn-group">
              <button class="approve-btn">&#10004;</button>
              <button class="reject-btn">&#10006;</button>
            </div>
          </div>

        </div>

        <!-- GLOBAL SETTINGS -->
        <div class="box settings">

          <h3>Global Settings</h3>

          <label for="commission">Commission Rate (%)</label>
          <input id="commission" type="number" value="15">

          <label for="delivery-fee">Base Delivery Fee ($)</label>
          <input
            id="delivery-fee"
            type="number"
            value="2.99"
            step="0.01"
          >

          <button class="save-btn">
            Save Configurations
          </button>

        </div>

      </div>
      <!-- /.right-panel -->

    </div>
    <!-- /.content -->

  </div>
  <!-- /.main -->

</div>
<!-- /.admin-wrap -->

</body>
</html>