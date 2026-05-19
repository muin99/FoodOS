<?php

include '../../dirCommon/dbconnect.php';
include '../model/complaintModel.php';

/* Resolve complaint */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['resolve_id'])) {

    resolveComplaint($conn, $_POST['resolve_id']);

    header("Location: complaints.php");
    exit;
}

/* Load complaints */
$complaints = getAllComplaints($conn);

/* Selected complaint */
$selectedComplaint = null;

if (isset($_GET['view_id'])) {
    $selectedComplaint = getComplaintById($conn, $_GET['view_id']);
}