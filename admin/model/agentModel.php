<?php

// GET ALL DELIVERY AGENTS
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


// ACTIVATE AGENT
function activateAgent($conn, $id)
{
    $sql = "UPDATE users SET is_active = 1 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}


// DEACTIVATE AGENT
function deactivateAgent($conn, $id)
{
    $sql = "UPDATE users SET is_active = 0 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}