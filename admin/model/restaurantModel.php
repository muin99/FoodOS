<?php

// Get all restaurants with manager info
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
    $sql = "UPDATE restaurants SET is_approved = 1 WHERE id = $id";
    return mysqli_query($conn, $sql);
}

function rejectRestaurant($conn, $id)
{
    $sql = "DELETE FROM restaurants WHERE id = $id";
    return mysqli_query($conn, $sql);
}

function suspendRestaurant($conn, $id)
{
    $sql = "UPDATE restaurants SET is_open = 0 WHERE id = $id";
    return mysqli_query($conn, $sql);
}

function reactivateRestaurant($conn, $id)
{
    $sql = "UPDATE restaurants SET is_open = 1 WHERE id = $id";
    return mysqli_query($conn, $sql);
}