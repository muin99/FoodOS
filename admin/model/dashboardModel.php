<?php

function getTotalUsers($conn)
{
    $sql = "SELECT COUNT(*) as total FROM users";
    $res = mysqli_query($conn, $sql);
    return mysqli_fetch_assoc($res)['total'];
}

function getActiveRestaurants($conn)
{
    $sql = "SELECT COUNT(*) as total FROM restaurants WHERE is_approved = 1 AND is_open = 1";
    $res = mysqli_query($conn, $sql);
    return mysqli_fetch_assoc($res)['total'];
}

function getTotalOrdersToday($conn)
{
    $sql = "SELECT COUNT(*) AS total 
            FROM orders 
            WHERE DATE(created_at) = CURDATE()";

    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    return $row['total'] ?? 0;
}

function getActiveDeliveryAgents($conn)
{
    $sql = "SELECT COUNT(*) AS total 
            FROM delivery_agents 
            WHERE is_approved = 1";

    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    return $row['total'] ?? 0;
}

function getAllUsers($conn)
{
    $sql = "
        SELECT 
            u.*,
            COUNT(o.id) AS total_orders
        FROM users u
        LEFT JOIN orders o 
            ON u.id = o.customer_id
        GROUP BY u.id
        ORDER BY u.id DESC
    ";

    $res = mysqli_query($conn, $sql);

    $data = [];
    while ($row = mysqli_fetch_assoc($res)) {
        $data[] = $row;
    }

    return $data;
}

function getUserWithOrderCount($conn)
{
    $sql = "
        SELECT 
            u.id,
            u.name,
            u.role,
            u.is_active,
            COUNT(o.id) AS total_orders
        FROM users u
        LEFT JOIN orders o 
            ON u.id = o.customer_id
        GROUP BY u.id
        ORDER BY u.id
    ";

    return mysqli_query($conn, $sql);
}