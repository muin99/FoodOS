<?php

$pageTitle  = 'Customers - Admin';
$activePage = 'customers';
$basePath   = '../../';

include '../controller/customerController.php';

if (!isset($customers)) {
    $customers = [];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $pageTitle ?></title>

<link rel="stylesheet" href="../assets/css/adminDashboard.css">
<link rel="stylesheet" href="../assets/css/customers.css">

</head>

<body>

<div class="admin-wrap">

<?php include __DIR__ . '/../partials/sidebar.php'; ?>

<div class="main">

    <div class="topbar">
        <h2>Customer Management</h2>

        <div class="search-box">
            <input type="text" id="searchCustomer" placeholder="Search customers...">
        </div>
    </div>

    <div class="cards">

        <div class="card">
            <p>Total Customers</p>
            <h3><?= count($customers) ?></h3>
        </div>

        <div class="card">
            <p>Active</p>
            <h3><?= count(array_filter($customers, fn($c) => $c['is_active'] == 1)) ?></h3>
        </div>

        <div class="card">
            <p>Inactive</p>
            <h3><?= count(array_filter($customers, fn($c) => $c['is_active'] == 0)) ?></h3>
        </div>

    </div>

    <div class="content">

        <div class="table-section">

            <div class="table-header">
                <h3>Customer List</h3>
            </div>

            <div class="table-wrapper">

                <table id="customerTable">

                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php foreach ($customers as $c): ?>

                        <tr>

                            <td>
                                <div class="user-info">
                                    <img src="https://i.pravatar.cc/150?img=<?= $c['id'] ?>">
                                    <span><?= htmlspecialchars($c['name']) ?></span>
                                </div>
                            </td>

                            <td><?= htmlspecialchars($c['email']) ?></td>
                            <td><?= htmlspecialchars($c['phone']) ?></td>

                            <td>
                                <?php if ($c['is_active'] == 1): ?>
                                    <span class="status active">Active</span>
                                <?php else: ?>
                                    <span class="status inactive">Inactive</span>
                                <?php endif; ?>
                            </td>

                            <td>

                                <form method="POST" action="../controller/customerController.php">

                                    <input type="hidden" name="id" value="<?= $c['id'] ?>">

                                    <button type="button"
                                            class="btn view"
                                            onclick='viewCustomer(<?= json_encode($c) ?>)'>
                                        View
                                    </button>

                                    <?php if ($c['is_active'] == 1): ?>
                                        <button type="submit" name="action" value="deactivate" class="btn danger">
                                            Deactivate
                                        </button>
                                    <?php else: ?>
                                        <button type="submit" name="action" value="activate" class="btn success">
                                            Reactivate
                                        </button>
                                    <?php endif; ?>

                                </form>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

</div>

<div id="customerModal" class="modal">

    <div class="modal-content">

        <h3>Customer Details</h3>

        <p><b>Name:</b> <span id="m_name"></span></p>
        <p><b>Email:</b> <span id="m_email"></span></p>
        <p><b>Phone:</b> <span id="m_phone"></span></p>
        <p><b>Status:</b> <span id="m_status"></span></p>

        <button onclick="closeModal()" class="btn danger">Close</button>

    </div>

</div>

<script>

function viewCustomer(c)
{
    document.getElementById("m_name").innerText = c.name;
    document.getElementById("m_email").innerText = c.email;
    document.getElementById("m_phone").innerText = c.phone;
    document.getElementById("m_status").innerText = (c.is_active == 1) ? "Active" : "Inactive";

    document.getElementById("customerModal").style.display = "flex";
}

function closeModal()
{
    document.getElementById("customerModal").style.display = "none";
}


document.getElementById("searchCustomer").addEventListener("keyup", function () {

    let val = this.value.toLowerCase();
    let rows = document.querySelectorAll("#customerTable tbody tr");

    rows.forEach(row => {
        row.style.display = row.innerText.toLowerCase().includes(val)
            ? ""
            : "none";
    });

});

</script>

</body>
</html>