<?php

function getPlatformSetting($conn, $key)
{
    $sql = "SELECT setting_value FROM platform_settings WHERE setting_key = '$key' LIMIT 1";
    $res = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($res);
    return $row['setting_value'] ?? null;
}

function setPlatformSetting($conn, $key, $value)
{
    $sql = "
        INSERT INTO platform_settings (setting_key, setting_value)
        VALUES ('$key', '$value')
        ON DUPLICATE KEY UPDATE setting_value = '$value'
    ";
    return mysqli_query($conn, $sql);
}

function getTotalRevenue($conn)
{
    $sql = "SELECT SUM(total_amount) AS total FROM orders WHERE status = 'delivered'";
    $res = mysqli_query($conn, $sql);
    return mysqli_fetch_assoc($res)['total'] ?? 0;
}

function getOrdersByStatus($conn)
{
    $sql = "SELECT status, COUNT(*) as total FROM orders GROUP BY status";
    $res = mysqli_query($conn, $sql);

    $data = [];
    while ($row = mysqli_fetch_assoc($res)) {
        $data[] = $row;
    }
    return $data;
}

function getTopRestaurants($conn)
{
    $sql = "
        SELECT r.name, COUNT(o.id) as total_orders
        FROM restaurants r
        JOIN orders o ON r.id = o.restaurant_id
        GROUP BY r.id
        ORDER BY total_orders DESC
        LIMIT 5
    ";
    return mysqli_query($conn, $sql);
}

function getTopAgents($conn)
{
    $sql = "
        SELECT da.id, u.name, COUNT(o.id) as total_orders
        FROM delivery_agents da
        JOIN users u ON u.id = da.user_id
        JOIN orders o ON da.id = o.agent_id
        GROUP BY da.id
        ORDER BY total_orders DESC
        LIMIT 5
    ";
    return mysqli_query($conn, $sql);
}

function getPeakHours($conn)
{
    $sql = "
        SELECT HOUR(created_at) as hour, COUNT(*) as total
        FROM orders
        GROUP BY hour
        ORDER BY total DESC
    ";
    return mysqli_query($conn, $sql);
}

function getAverageDeliveryTime($conn)
{
    $sql = "
        SELECT AVG(TIMESTAMPDIFF(MINUTE, o.created_at, da.delivered_at)) AS avg_time
        FROM orders o
        JOIN delivery_assignments da ON o.id = da.order_id
        WHERE da.delivered_at IS NOT NULL
    ";

    $result = mysqli_query($conn, $sql);

    if (!$result) {
        die("SQL Error: " . mysqli_error($conn));
    }

    $row = mysqli_fetch_assoc($result);
    return $row['avg_time'] ?? 0;
}
function getOnTimeRate($conn)
{
    $sql = "
        SELECT 
            (SUM(
                CASE 
                    WHEN TIMESTAMPDIFF(MINUTE, o.created_at, da.delivered_at) 
                         <= o.estimated_delivery_minutes 
                    THEN 1 ELSE 0 
                END
            ) / COUNT(*)) * 100 AS on_time_rate
        FROM orders o
        JOIN delivery_assignments da ON o.id = da.order_id
        WHERE da.delivered_at IS NOT NULL
    ";

    $result = mysqli_query($conn, $sql);

    if (!$result) {
        die("SQL ERROR (OnTimeRate): " . mysqli_error($conn));
    }

    $row = mysqli_fetch_assoc($result);
    return $row['on_time_rate'] ?? 0;
}
function getFailedDeliveries($conn)
{
    $sql = "SELECT COUNT(*) AS total FROM orders WHERE status = 'cancelled'";
    $res = mysqli_query($conn, $sql);
    return mysqli_fetch_assoc($res)['total'] ?? 0;
}

function getMonthlySummary($conn)
{
    $sql = "
        SELECT 
            DATE_FORMAT(created_at, '%Y-%m') as month,
            COUNT(*) as total_orders,
            SUM(total_amount) as revenue
        FROM orders
        GROUP BY month
        ORDER BY month DESC
        LIMIT 12
    ";
    return mysqli_query($conn, $sql);
}