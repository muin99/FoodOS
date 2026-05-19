<?php

function getAvailableAssignments($conn)
{
    $sql = "
        SELECT
            o.id AS order_id,
            o.delivery_address,
            o.total_amount,
            o.delivery_fee,
            o.status,
            o.created_at,
            r.name AS restaurant_name,
            r.address AS restaurant_address,
            u.name AS customer_name
        FROM orders o
        INNER JOIN restaurants r ON r.id = o.restaurant_id
        INNER JOIN users u ON u.id = o.customer_id
        LEFT JOIN delivery_assignments da ON da.order_id = o.id
        WHERE o.status IN ('accepted', 'ready')
          AND da.id IS NULL
        ORDER BY o.created_at ASC
    ";

    $result = mysqli_query($conn, $sql);
    $orders = [];

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $orders[] = $row;
        }
    }

    return $orders;
}

function getNewAssignmentsSince($conn, $lastChecked)
{
    $sql = "
        SELECT COUNT(*) AS total
        FROM orders o
        LEFT JOIN delivery_assignments da ON da.order_id = o.id
        WHERE o.status IN ('accepted', 'ready')
          AND da.id IS NULL
          AND o.created_at > ?
    ";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 's', $lastChecked);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    return (int)($row['total'] ?? 0);
}

function acceptAssignment($conn, $orderId, $agentId, $userId)
{
    if ($orderId <= 0 || $agentId <= 0 || $userId <= 0) return false;

    mysqli_begin_transaction($conn);

    $sql = "INSERT INTO delivery_assignments (order_id, agent_id, status) VALUES (?, ?, 'assigned')";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'ii', $orderId, $agentId);

    if (!mysqli_stmt_execute($stmt)) {
        mysqli_rollback($conn);
        return false;
    }

    $sql = "UPDATE orders SET agent_id = ? WHERE id = ? AND agent_id IS NULL";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'ii', $agentId, $orderId);

    if (!mysqli_stmt_execute($stmt) || mysqli_stmt_affected_rows($stmt) < 1) {
        mysqli_rollback($conn);
        return false;
    }

    return mysqli_commit($conn);
}

function declineAssignment($conn, $orderId, $agentId)
{
    if ($orderId <= 0 || $agentId <= 0) return false;

    $sql = "UPDATE delivery_assignments SET status = 'cancelled' WHERE order_id = ? AND agent_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'ii', $orderId, $agentId);

    return mysqli_stmt_execute($stmt);
}

function toggleAgentOnlineStatus($conn, $userId, $isOnline)
{
    $sql = "UPDATE delivery_agents SET is_online = ? WHERE user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'ii', $isOnline, $userId);

    return mysqli_stmt_execute($stmt);
}

function updateDeliveryStatus($conn, $orderId, $agentId, $newStatus)
{
    $allowed = ['assigned', 'picked_up', 'delivered', 'cancelled'];
    if ($orderId <= 0 || $agentId <= 0 || !in_array($newStatus, $allowed)) return false;

    $pickedUpAt = $newStatus == 'picked_up' ? date('Y-m-d H:i:s') : null;
    $deliveredAt = $newStatus == 'delivered' ? date('Y-m-d H:i:s') : null;

    $sql = "
        UPDATE delivery_assignments
        SET status = ?,
            picked_up_at = IF(? IS NULL, picked_up_at, ?),
            delivered_at = IF(? IS NULL, delivered_at, ?)
        WHERE order_id = ? AND agent_id = ?
    ";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'sssssii', $newStatus, $pickedUpAt, $pickedUpAt, $deliveredAt, $deliveredAt, $orderId, $agentId);

    if (!mysqli_stmt_execute($stmt)) return false;

    if ($newStatus == 'delivered') {
        $orderStatus = 'delivered';
    } elseif ($newStatus == 'picked_up') {
        $orderStatus = 'picked_up';
    } elseif ($newStatus == 'cancelled') {
        $orderStatus = 'accepted';
    } else {
        return true;
    }

    $sql = "UPDATE orders SET status = ? WHERE id = ? AND agent_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'sii', $orderStatus, $orderId, $agentId);

    return mysqli_stmt_execute($stmt);
}

function getAgentEarningsSummary($conn, $agentId)
{
    $sql = "
        SELECT
            COALESCE(SUM(o.delivery_fee), 0) AS total_earnings,
            COUNT(*) AS total_deliveries
        FROM orders o
        WHERE o.agent_id = ? AND o.status = 'delivered'
    ";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $agentId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    return mysqli_fetch_assoc($result) ?: ['total_earnings' => 0, 'total_deliveries' => 0];
}

function getAgentDeliveryHistory($conn, $agentId)
{
    $sql = "
        SELECT o.id AS order_id, o.delivery_address, o.delivery_fee, o.total_amount, o.status, da.assigned_at, da.delivered_at
        FROM delivery_assignments da
        INNER JOIN orders o ON o.id = da.order_id
        WHERE da.agent_id = ?
        ORDER BY da.assigned_at DESC
    ";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $agentId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $history = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $history[] = $row;
    }

    return $history;
}

function getAgentPerformanceStats($conn, $agentId, $userId)
{
    $earnings = getAgentEarningsSummary($conn, $agentId);

    $sql = "SELECT is_online, vehicle_type FROM delivery_agents WHERE id = ? AND user_id = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'ii', $agentId, $userId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $profile = mysqli_fetch_assoc($result) ?: [];

    return array_merge($earnings, $profile);
}

function updateAgentProfile($conn, $userId, $name, $phone, $vehicleType)
{
    if ($userId <= 0 || trim($name) == '' || trim($phone) == '' || trim($vehicleType) == '') return false;

    mysqli_begin_transaction($conn);

    $sql = "UPDATE users SET name = ?, phone = ? WHERE id = ? AND role = 'agent'";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'ssi', $name, $phone, $userId);

    if (!mysqli_stmt_execute($stmt)) {
        mysqli_rollback($conn);
        return false;
    }

    $sql = "UPDATE delivery_agents SET vehicle_type = ? WHERE user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'si', $vehicleType, $userId);

    if (!mysqli_stmt_execute($stmt)) {
        mysqli_rollback($conn);
        return false;
    }

    return mysqli_commit($conn);
}
