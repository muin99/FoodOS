<?php

$pageTitle  = 'Delivery Agents - Admin';
$activePage = 'agents';
$basePath   = '../../';

include '../controller/agentController.php';

if (!isset($agents)) {
    $agents = [];
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

        <h2>Delivery Agent Management</h2>

        <div class="search-box">
            <input type="text" id="searchAgent" placeholder="Search agents...">
        </div>

    </div>


    <!-- CARDS -->
    <div class="cards">

        <div class="card">
            <p>Total Agents</p>
            <h3><?= count($agents) ?></h3>
        </div>

        <div class="card">
            <p>Active</p>
            <h3><?= count(array_filter($agents, fn($a) => $a['is_active'] == 1)) ?></h3>
        </div>

        <div class="card">
            <p>Inactive</p>
            <h3><?= count(array_filter($agents, fn($a) => $a['is_active'] == 0)) ?></h3>
        </div>

    </div>


    <!-- TABLE -->
    <div class="content">

        <div class="table-section">

            <div class="table-header">
                <h3>Agent List</h3>
            </div>

            <div class="table-wrapper">

                <table id="agentTable">

                    <thead>

                        <tr>
                            <th>Agent</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>

                    </thead>

                    <tbody>

                    <?php foreach ($agents as $a): ?>

                        <tr>

                            <td>

                                <div class="user-info">
                                    <img src="https://i.pravatar.cc/150?img=<?= $a['id'] ?>">
                                    <span><?= htmlspecialchars($a['name']) ?></span>
                                </div>

                            </td>

                            <td><?= htmlspecialchars($a['email']) ?></td>

                            <td><?= htmlspecialchars($a['phone']) ?></td>

                            <td>

                                <?php if ($a['is_active'] == 1): ?>

                                    <span class="status active">Active</span>

                                <?php else: ?>

                                    <span class="status inactive">Inactive</span>

                                <?php endif; ?>

                            </td>

                            <td>

                                <form method="POST" action="../controller/agentController.php">

                                    <input type="hidden" name="id" value="<?= $a['id'] ?>">

                                    <button type="button"
                                            class="btn view"
                                            onclick='viewAgent(<?= json_encode($a) ?>)'>

                                        View

                                    </button>

                                    <?php if ($a['is_active'] == 1): ?>

                                        <button type="submit"
                                                name="action"
                                                value="deactivate"
                                                class="btn danger">

                                            Deactivate

                                        </button>

                                    <?php else: ?>

                                        <button type="submit"
                                                name="action"
                                                value="activate"
                                                class="btn success">

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


<!-- MODAL -->
<div id="agentModal" class="modal">

    <div class="modal-content">

        <h3>Agent Details</h3>

        <p><b>Name:</b> <span id="m_name"></span></p>
        <p><b>Email:</b> <span id="m_email"></span></p>
        <p><b>Phone:</b> <span id="m_phone"></span></p>
        <p><b>Status:</b> <span id="m_status"></span></p>

        <button onclick="closeModal()" class="btn danger">
            Close
        </button>

    </div>

</div>


<script>

function viewAgent(a)
{
    document.getElementById("m_name").innerText = a.name;
    document.getElementById("m_email").innerText = a.email;
    document.getElementById("m_phone").innerText = a.phone;
    document.getElementById("m_status").innerText =
        (a.is_active == 1) ? "Active" : "Inactive";

    document.getElementById("agentModal").style.display = "flex";
}


function closeModal()
{
    document.getElementById("agentModal").style.display = "none";
}


// SEARCH
document.getElementById("searchAgent").addEventListener("keyup", function () {

    let val = this.value.toLowerCase();

    let rows = document.querySelectorAll("#agentTable tbody tr");

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