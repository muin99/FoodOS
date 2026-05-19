<?php

$pageTitle  = 'Complaints - Admin';
$activePage = 'complaints';
$basePath   = '../../';

include '../controller/complaintController.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title><?= $pageTitle ?></title>

<link rel="stylesheet" href="../assets/css/adminDashboard.css">
<link rel="stylesheet" href="../assets/css/complaints.css">
</head>

<body>

<div class="admin-wrap">

<?php include __DIR__ . '/../partials/sidebar.php'; ?>

<div class="main">

    <div class="topbar">
        <h2>🛑 Customer Complaints</h2>
    </div>

    <div class="table-section">

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Subject</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>

            <?php if ($complaints && $complaints->num_rows > 0): ?>

                <?php while($c = $complaints->fetch_assoc()): ?>
                <tr>
                    <td>#<?= $c['id'] ?></td>
                    <td><?= htmlspecialchars($c['customer_name']) ?></td>
                    <td><?= htmlspecialchars($c['subject']) ?></td>
                    <td><?= htmlspecialchars($c['description']) ?></td>

                    <td>
                        <span class="status <?= $c['status'] ?>">
                            <?= $c['status'] ?>
                        </span>
                    </td>

                    <td>
                        <a href="?view_id=<?= $c['id'] ?>" class="btn view">View</a>
                    </td>
                </tr>
                <?php endwhile; ?>

            <?php else: ?>
                <tr>
                    <td colspan="6">No complaints found</td>
                </tr>
            <?php endif; ?>

            </tbody>
        </table>

    </div>

    <?php if (!empty($selectedComplaint)): ?>

        <div class="complaint-box">

            <h3>Complaint Details</h3>

            <p><b>Customer:</b> <?= htmlspecialchars($selectedComplaint['customer_name']) ?></p>
            <p><b>Subject:</b> <?= htmlspecialchars($selectedComplaint['subject']) ?></p>
            <p><b>Message:</b> <?= htmlspecialchars($selectedComplaint['description']) ?></p>
            <p><b>Status:</b> <?= htmlspecialchars($selectedComplaint['status']) ?></p>

            <?php if ($selectedComplaint['status'] !== 'resolved'): ?>

                <form method="POST">

                    <input type="hidden" name="resolve_id" value="<?= $selectedComplaint['id'] ?>">

                    <button type="submit" class="btn success">
                        Mark as Resolved
                    </button>

                </form>

            <?php else: ?>

                <p class="status-msg">✔ Already resolved</p>

            <?php endif; ?>

        </div>

    <?php endif; ?>

</div>

</div>

</body>
</html>