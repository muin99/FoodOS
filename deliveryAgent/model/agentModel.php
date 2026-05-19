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

function getActiveAssignment($conn, $agentId)
{
    $sql = "
        SELECT
            o.id AS order_id,
            o.delivery_address,
            o.delivery_fee,
            o.total_amount,
            o.status AS order_status,
            da.status AS assignment_status,
            da.assigned_at,
            da.picked_up_at,
            r.name AS restaurant_name,
            r.address AS restaurant_address,
            u.name AS customer_name
        FROM delivery_assignments da
        INNER JOIN orders o ON o.id = da.order_id
        INNER JOIN restaurants r ON r.id = o.restaurant_id
        INNER JOIN users u ON u.id = o.customer_id
        WHERE da.agent_id = ?
          AND da.status IN ('assigned', 'picked_up')
        ORDER BY da.assigned_at DESC
        LIMIT 1
    ";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $agentId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    return mysqli_fetch_assoc($result);
}

function getAgentActiveDelivery($conn, $agentId)
{
    return getActiveAssignment($conn, $agentId);
}

function getAgentProfile($conn, $userId)
{
    $sql = "
        SELECT
            da.id AS agent_id,
            da.is_online,
            da.vehicle_type,
            da.total_earnings,
            da.is_approved,
            u.id AS user_id,
            u.name,
            u.email,
            u.phone
        FROM delivery_agents da
        INNER JOIN users u ON u.id = da.user_id
        WHERE da.user_id = ? AND u.role = 'agent'
        LIMIT 1
    ";

    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) return null;

    mysqli_stmt_bind_param($stmt, 'i', $userId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    return mysqli_fetch_assoc($result) ?: null;
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

    $sql = "
        INSERT INTO delivery_assignments (order_id, agent_id, status)
        SELECT o.id, ?, 'assigned'
        FROM orders o
        WHERE o.id = ?
          AND o.agent_id IS NULL
          AND o.status IN ('accepted', 'ready')
        LIMIT 1
    ";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'ii', $agentId, $orderId);

    if (!mysqli_stmt_execute($stmt) || mysqli_stmt_affected_rows($stmt) < 1) {
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
    return $orderId > 0 && $agentId > 0;
}

function toggleAgentOnlineStatus($conn, $userId, $agentId, $isOnline)
{
    if ($userId <= 0 || $agentId <= 0) return false;

    $checkSql = "SELECT id FROM delivery_agents WHERE user_id = ? AND id = ? LIMIT 1";
    $checkStmt = mysqli_prepare($conn, $checkSql);
    if (!$checkStmt) return false;
    mysqli_stmt_bind_param($checkStmt, 'ii', $userId, $agentId);
    mysqli_stmt_execute($checkStmt);
    $checkResult = mysqli_stmt_get_result($checkStmt);
    if (!$checkResult || mysqli_num_rows($checkResult) === 0) return false;

    $sql = "UPDATE delivery_agents SET is_online = ? WHERE user_id = ? AND id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) return false;

    mysqli_stmt_bind_param($stmt, 'iii', $isOnline, $userId, $agentId);
    return mysqli_stmt_execute($stmt);
}

function updateDeliveryStatus($conn, $orderId, $agentId, $newStatus)
{
    $allowed = ['assigned', 'picked_up', 'delivered', 'cancelled'];
    if ($orderId <= 0 || $agentId <= 0 || !in_array($newStatus, $allowed)) return false;

    mysqli_begin_transaction($conn);

    if ($newStatus == 'assigned') {
        $sql = "UPDATE delivery_assignments SET status = 'assigned' WHERE order_id = ? AND agent_id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'ii', $orderId, $agentId);
        if (!mysqli_stmt_execute($stmt)) {
            mysqli_rollback($conn);
            return false;
        }
        mysqli_commit($conn);
        return true;
    }

    if ($newStatus == 'picked_up') {
        $sql = "UPDATE delivery_assignments SET status = 'picked_up', picked_up_at = NOW() WHERE order_id = ? AND agent_id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'ii', $orderId, $agentId);
        if (!mysqli_stmt_execute($stmt)) {
            mysqli_rollback($conn);
            return false;
        }

        $sql = "UPDATE orders SET status = 'picked_up' WHERE id = ? AND agent_id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'ii', $orderId, $agentId);
        if (!mysqli_stmt_execute($stmt)) {
            mysqli_rollback($conn);
            return false;
        }

        mysqli_commit($conn);
        return true;
    }

    if ($newStatus == 'delivered') {
        $sql = "
            UPDATE delivery_assignments
            SET status = 'delivered',
                picked_up_at = IFNULL(picked_up_at, NOW()),
                delivered_at = NOW()
            WHERE order_id = ? AND agent_id = ?
        ";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'ii', $orderId, $agentId);
        if (!mysqli_stmt_execute($stmt)) {
            mysqli_rollback($conn);
            return false;
        }

        $sql = "UPDATE orders SET status = 'delivered' WHERE id = ? AND agent_id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'ii', $orderId, $agentId);
        if (!mysqli_stmt_execute($stmt)) {
            mysqli_rollback($conn);
            return false;
        }

        mysqli_commit($conn);
        return true;
    }

    $sql = "UPDATE delivery_assignments SET status = 'cancelled' WHERE order_id = ? AND agent_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'ii', $orderId, $agentId);
    if (!mysqli_stmt_execute($stmt)) {
        mysqli_rollback($conn);
        return false;
    }

    $sql = "UPDATE orders SET status = 'accepted', agent_id = NULL WHERE id = ? AND agent_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'ii', $orderId, $agentId);
    if (!mysqli_stmt_execute($stmt)) {
        mysqli_rollback($conn);
        return false;
    }

    mysqli_commit($conn);
    return true;
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

    $sql = "SELECT da.is_online, da.vehicle_type, u.name, u.email, u.phone FROM delivery_agents da INNER JOIN users u ON u.id = da.user_id WHERE da.id = ? AND da.user_id = ? LIMIT 1";
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
