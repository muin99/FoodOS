<?php

function getAllRestaurants($conn)
{
    $sql = "
        SELECT 
            r.*,
            u.name AS manager_name,
            u.email AS manager_email
        FROM restaurants r
        LEFT JOIN users u ON r.manager_id = u.id
        ORDER BY r.created_at DESC
    ";

    $res = mysqli_query($conn, $sql);

    $data = [];
    while ($row = mysqli_fetch_assoc($res)) {
        $data[] = $row;
    }

    return $data;
}
function approveRestaurant($conn, $id)
{
    $sql = "UPDATE restaurants SET is_approved = 2 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

function blockRestaurant($conn, $id)
{
    $sql = "UPDATE restaurants SET is_approved = 3 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

function pendingRestaurant($conn, $id)
{
    $sql = "UPDATE restaurants SET is_approved = 1 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}