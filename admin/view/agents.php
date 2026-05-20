<!-- is_active = 2 → Pending -->
<!-- is_active = 1 → Active -->
<!-- is_active = 0 → Blocked -->
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

<link rel="stylesheet" href="../assets/css/restaurants.css">

</head>

<body>

<div class="admin-wrap">

    <?php include __DIR__ . '/../partials/sidebar.php'; ?>

    <div class="main">

        <div class="topbar">

            <h2>Delivery Agent Management</h2>

            <div class="search-box">
                <input type="text"
                       id="searchAgent"
                       placeholder="Search agents...">
            </div>

        </div>

        <div class="cards">

            <div class="card">
                <p>Total Agents</p>
                <h3><?= count($agents) ?></h3>
            </div>

            <div class="card">
                <p>Pending</p>
                <h3>
                    <?= count(array_filter($agents, fn($a) => $a['is_active'] == 2)) ?>
                </h3>
            </div>

            <div class="card">
                <p>Active</p>
                <h3>
                    <?= count(array_filter($agents, fn($a) => $a['is_active'] == 1)) ?>
                </h3>
            </div>

            <div class="card">
                <p>Block</p>
                <h3>
                    <?= count(array_filter($agents, fn($a) => $a['is_active'] == 0)) ?>
                </h3>
            </div>

        </div>


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

                            <?php if ($a['is_active'] != 2): ?>

                            <tr>

                                <!-- AGENT -->
                                <td>

                                    <div class="user-info">

                                        <img src="https://i.pravatar.cc/150?img=<?= $a['id'] ?>">

                                        <span>
                                            <?= htmlspecialchars($a['name']) ?>
                                        </span>

                                    </div>

                                </td>
                                <td>
                                    <?= htmlspecialchars($a['email']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($a['phone']) ?>
                                </td>

                                <td>

                                    <?php if ($a['is_active'] == 1): ?>

                                        <span class="status active">
                                            Active
                                        </span>

                                    <?php elseif ($a['is_active'] == 0): ?>

                                        <span class="status inactive">
                                            Inactive
                                        </span>

                                    <?php endif; ?>

                                </td>

                                <td>

                                    <form method="POST"
                                          action="../controller/agentController.php"
                                          class="admin-ajax-form"
                                          style="display:flex; gap:5px;">

                                        <input type="hidden"
                                               name="id"
                                               value="<?= $a['id'] ?>">

                                        <!-- VIEW -->
                                        <button type="button"
                                                class="btn view"
                                                onclick='viewAgent(<?= json_encode($a) ?>)'>

                                            View

                                        </button>

                                        <!-- DEACTIVATE -->
                                        <?php if ($a['is_active'] == 1): ?>

                                            <button type="submit"
                                                    name="action"
                                                    value="deactivate"
                                                    class="reject-btn">

                                                Block

                                            </button>

                                        <?php elseif ($a['is_active'] == 0): ?>

                                            <button type="submit"
                                                    name="action"
                                                    value="activate"
                                                    class="approve-btn">

                                                Reactivate

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

                    <h3>Pending Agent Approvals</h3>

                    <?php
                    $pendingAgents = array_filter(
                        $agents,
                        fn($a) => $a['is_active'] == 2
                    );
                    ?>

                    <?php if (count($pendingAgents) === 0): ?>

                        <p style="font-size:13px;color:#999;">
                            No pending agents.
                        </p>

                    <?php else: ?>

                        <?php foreach ($pendingAgents as $p): ?>

                            <div class="box" style="margin-bottom:10px;">

                                <p style="font-weight:600;margin:0;">
                                    <?= htmlspecialchars($p['name']) ?>
                                </p>

                                <p style="font-size:12px;color:#888;margin:4px 0;">
                                    <?= htmlspecialchars($p['email']) ?>
                                </p>

                                <p style="font-size:12px;color:#888;margin:4px 0;">
                                    <?= htmlspecialchars($p['phone']) ?>
                                </p>

                                <form method="POST"
                                      action="../controller/agentController.php"
                                      class="admin-ajax-form"
                                      style="margin-top:8px;">

                                    <input type="hidden"
                                           name="id"
                                           value="<?= $p['id'] ?>">

                                    <button type="submit"
                                            name="action"
                                            value="activate"
                                            class="btn success"
                                            style="width:100%;">

                                        Approve Agent

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
<div id="agentModal" class="modal">

    <div class="modal-content">

        <h3>Agent Details</h3>

        <p>
            <b>Name:</b>
            <span id="m_name"></span>
        </p>

        <p>
            <b>Email:</b>
            <span id="m_email"></span>
        </p>

        <p>
            <b>Phone:</b>
            <span id="m_phone"></span>
        </p>

        <p>
            <b>Status:</b>
            <span id="m_status"></span>
        </p>

        <button onclick="closeModal()" class="btn danger">
            Close
        </button>

    </div>

</div>


<script>

function viewAgent(a)
{
    document.getElementById("m_name").innerText =
        a.name;

    document.getElementById("m_email").innerText =
        a.email;

    document.getElementById("m_phone").innerText =
        a.phone;

    document.getElementById("m_status").innerText =
        (a.is_active == 2)
        ? "Pending"
        : (a.is_active == 1)
            ? "Active"
            : "Inactive";

    document.getElementById("agentModal").style.display = "flex";
}


function closeModal()
{
    document.getElementById("agentModal").style.display = "none";
}


document.getElementById("searchAgent")
.addEventListener("keyup", function () {

    let val = this.value.toLowerCase();

    let rows = document.querySelectorAll(
        "#agentTable tbody tr"
    );

    rows.forEach(row => {

        row.style.display =
            row.innerText.toLowerCase().includes(val)
            ? ""
            : "none";

    });

});

document.querySelectorAll(".admin-ajax-form").forEach(function(form) {
    form.addEventListener("submit", function(event) {
        event.preventDefault();

        const button = event.submitter;
        const formData = new FormData(form);
        if (button && button.name) {
            formData.append(button.name, button.value);
        }
        formData.append("ajax", "1");

        if (button) {
            button.disabled = true;
        }

        fetch(form.action, {
            method: "POST",
            headers: {
                "X-Requested-With": "XMLHttpRequest"
            },
            body: formData
        })
        .then(function(response) {
            return response.json();
        })
        .then(function(data) {
            alert(data.message || "Agent action completed.");
            if (data.success) {
                location.reload();
                return;
            }
            if (button) {
                button.disabled = false;
            }
        })
        .catch(function() {
            alert("Agent action failed.");
            if (button) {
                button.disabled = false;
            }
        });
    });
});

</script>

</body>
</html>
