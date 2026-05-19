<?php

function getAllComplaints($conn)
{
    $sql = "
        SELECT c.*, u.name AS customer_name
        FROM complaints c
        JOIN users u ON c.submitter_id = u.id
        ORDER BY c.created_at DESC
    ";

    $result = $conn->query($sql);

    if (!$result) {
        die("SQL ERROR (getAllComplaints): " . $conn->error);
    }

    return $result;
}

function getComplaintById($conn, $id)
{
    $id = intval($id);

    $sql = "
        SELECT c.*, u.name AS customer_name
        FROM complaints c
        JOIN users u ON c.submitter_id = u.id
        WHERE c.id = $id
    ";

    $result = $conn->query($sql);

    if (!$result) {
        die("SQL ERROR (getComplaintById): " . $conn->error);
    }

    return $result->fetch_assoc();
}

function resolveComplaint($conn, $id)
{
    $id = intval($id);

    $sql = "
        UPDATE complaints
        SET status = 'resolved'
        WHERE id = $id
    ";

    $result = $conn->query($sql);

    if (!$result) {
        die("SQL ERROR (resolveComplaint): " . $conn->error);
    }

    return $result;
}