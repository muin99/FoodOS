<?php

function getAllAgents($conn)
{
    $sql = "
        SELECT *
        FROM users
        WHERE role = 'agent'
        ORDER BY id DESC
    ";

    $res = mysqli_query($conn, $sql);

    $data = [];

    while ($row = mysqli_fetch_assoc($res)) {
        $data[] = $row;
    }

    return $data;
}


function approveAgent($conn, $id)
{
    $sql = "UPDATE users SET is_approved = 2 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    return $stmt->execute();
}


function rejectAgent($conn, $id)
{
    $sql = "UPDATE users SET is_approved = 3 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    return $stmt->execute();
}


function activateAgent($conn, $id)
{
    $sql = "UPDATE users SET is_active = 1 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    return $stmt->execute();
}


function deactivateAgent($conn, $id)
{
    $sql = "UPDATE users SET is_active = 0 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    return $stmt->execute();
}