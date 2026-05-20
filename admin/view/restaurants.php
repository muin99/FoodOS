
<!-- is_approved = 1 → Pending  -->
<!-- is_approved = 2 → Approved  -->
<!-- is_approved = 3 → Blocked  -->
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

<link rel="stylesheet" href="../assets/css/restaurants.css">


</head>

<body>

<div class="admin-wrap">

    <?php include __DIR__ . '/../partials/sidebar.php'; ?>

    <div class="main">
\
        <div class="topbar">

            <div>
                <h2>Restaurant Management</h2>
            </div>

            <div class="search-box">
                <input type="text"
                       id="searchRestaurant"
                       placeholder="Search restaurants...">
            </div>

        </div>

        <div class="cards">

            <div class="card">
                <p>Total Restaurants</p>
                <h3><?= count($restaurants) ?></h3>
            </div>

            <div class="card">
                <p>Pending</p>
                <h3>
                    <?= count(array_filter($restaurants, fn($r) => $r['is_approved'] == 1)) ?>
                </h3>
            </div>

            <div class="card">
                <p>Approved</p>
                <h3>
                    <?= count(array_filter($restaurants, fn($r) => $r['is_approved'] == 2)) ?>
                </h3>
            </div>

            <div class="card">
                <p>Blocked</p>
                <h3>
                    <?= count(array_filter($restaurants, fn($r) => $r['is_approved'] == 3)) ?>
                </h3>
            </div>

        </div>


        <div class="content">
            <div class="table-section">

                <div class="table-header">
                    <h3>Restaurant List</h3>
                </div>

                <div class="table-wrapper">

                    <table id="restaurantTable">

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
                                <td>

                                    <div class="user-info">

                                        <img src="../assets/default-restaurant.png"
                                             alt="restaurant">

                                        <span>
                                            <?= htmlspecialchars($r['name']) ?>
                                        </span>

                                    </div>

                                </td>
                                <td>
                                    <?= htmlspecialchars($r['manager_name'] ?? 'N/A') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($r['city']) ?>
                                </td>

                                <td>

                                    <?php if ($r['is_approved'] == 2): ?>

                                        <span class="status active-status">
                                            Approved
                                        </span>

                                    <?php elseif ($r['is_approved'] == 3): ?>

                                        <span class="status suspended">
                                            Blocked
                                        </span>

                                    <?php endif; ?>

                                </td>

                                <td>

                                    <form method="POST"
                                          action="../controller/restaurantController.php"
                                          style="display:flex; gap:5px;">

                                        <input type="hidden"
                                               name="id"
                                               value="<?= $r['id'] ?>">

                                        <!-- VIEW -->
                                        <button type="button"
                                                class="btn"
                                                onclick='viewRestaurant(<?= json_encode($r) ?>)'>

                                            View

                                        </button>

                                        <?php if ($r['is_approved'] == 2): ?>

                                            <button type="submit"
                                                    name="action"
                                                    value="block"
                                                    class="reject-btn">

                                                Block

                                            </button>

                                        <?php elseif ($r['is_approved'] == 3): ?>

                                            <button type="submit"
                                                    name="action"
                                                    value="approve"
                                                    class="approve-btn">

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

            </div>


            <!-- RIGHT PANEL -->
            <div class="right-panel">

                <div class="box">

                    <h3>Pending Approvals</h3>

                    <?php
                    $pendingList = array_filter(
                        $restaurants,
                        fn($r) => $r['is_approved'] == 1
                    );
                    ?>

                    <?php if (count($pendingList) === 0): ?>

                        <p style="font-size:13px;color:#999;">
                            No pending restaurants.
                        </p>

                    <?php else: ?>

                        <?php foreach ($pendingList as $p): ?>

                            <div class="box" style="margin-bottom:10px;">

                                <p style="font-weight:600;margin:0;">
                                    <?= htmlspecialchars($p['name']) ?>
                                </p>

                                <p style="font-size:12px;color:#888;margin:4px 0;">
                                    <?= htmlspecialchars($p['city']) ?>
                                </p>

                                <p style="font-size:12px;color:#888;margin:4px 0;">

                                    Manager:
                                    <?= htmlspecialchars($p['manager_name'] ?? 'N/A') ?>

                                </p>

                                <form method="POST"
                                      action="../controller/restaurantController.php"
                                      style="margin-top:8px;">

                                    <input type="hidden"
                                           name="id"
                                           value="<?= $p['id'] ?>">

                                    <button type="submit"
                                            name="action"
                                            value="approve"
                                            class="approve-btn"
                                            style="width:100%;">

                                        Approve

                                    </button>

                                </form>

                            </div>

                        <?php endforeach; ?>

                    <?php endif; ?>

                </div>

            </div>

        </div>

    </div>

</div>


<!-- MODAL -->
<div id="restaurantModal" class="modal">

    <div class="modal-content">

        <h3>Restaurant Details</h3>

        <p>
            <b>Name:</b>
            <span id="m_name"></span>
        </p>

        <p>
            <b>Manager:</b>
            <span id="m_manager"></span>
        </p>

        <p>
            <b>Manager Email:</b>
            <span id="m_email"></span>
        </p>

        <p>
            <b>City:</b>
            <span id="m_city"></span>
        </p>

        <p>
            <b>Status:</b>
            <span id="m_status"></span>
        </p>

        <button onclick="closeModal()" class="btn">
            Close
        </button>

    </div>

</div>


<script>

// VIEW MODAL
function viewRestaurant(r)
{
    document.getElementById("m_name").innerText =
        r.name;

    document.getElementById("m_manager").innerText =
        r.manager_name ?? 'N/A';

    document.getElementById("m_email").innerText =
        r.manager_email ?? 'N/A';

    document.getElementById("m_city").innerText =
        r.city;

    document.getElementById("m_status").innerText =
        (r.is_approved == 1)
        ? "Pending"
        : (r.is_approved == 2)
            ? "Approved"
            : "Blocked";

    document.getElementById("restaurantModal").style.display = "flex";
}


// CLOSE MODAL
function closeModal()
{
    document.getElementById("restaurantModal").style.display = "none";
}


// SEARCH
document.getElementById("searchRestaurant")
.addEventListener("keyup", function () {

    let val = this.value.toLowerCase();

    let rows = document.querySelectorAll(
        "#restaurantTable tbody tr"
    );

    rows.forEach(row => {

        row.style.display =
            row.innerText.toLowerCase().includes(val)
            ? ""
            : "none";

    });

});

</script>

</body>
</html>