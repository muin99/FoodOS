<?php

function agentLogin($conn, $email, $password)
{
    $sql = "SELECT u.*, da.id AS agent_id, da.is_approved, da.vehicle_type, da.is_online, da.total_earnings
            FROM users u
            INNER JOIN delivery_agents da ON da.user_id = u.id
            WHERE u.email = ? AND u.role = 'agent'
            LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    if ($user == null) return false;
    if ($user['is_active'] != 1) return false;
    if (password_verify($password, $user['password_hash'])) return $user;

    return false;
}

function agentEmailExists($conn, $email)
{
    $sql = 'SELECT id FROM users WHERE email = ? LIMIT 1';
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($result) != null;
}

function agentRegister($conn, $name, $email, $phone, $password, $vehicleType)
{
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    $role = 'agent';

    mysqli_begin_transaction($conn);

    $sql = 'INSERT INTO users (name, email, password_hash, phone, role)
            VALUES (?, ?, ?, ?, ?)';
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'sssss', $name, $email, $passwordHash, $phone, $role);

    if (!mysqli_stmt_execute($stmt)) {
        mysqli_rollback($conn);
        return false;
    }

    $userId = mysqli_insert_id($conn);

    $sql2 = 'INSERT INTO delivery_agents (user_id, vehicle_type)
             VALUES (?, ?)';
    $stmt2 = mysqli_prepare($conn, $sql2);
    mysqli_stmt_bind_param($stmt2, 'is', $userId, $vehicleType);

    if (!mysqli_stmt_execute($stmt2)) {
        mysqli_rollback($conn);
        return false;
    }

    mysqli_commit($conn);
    return $userId;
}

function getAgentProfile($conn, $userId)
{
    $sql = "SELECT u.name, u.email, u.phone, u.profile_pic,
                   da.vehicle_type, da.is_online, da.total_earnings
            FROM users u
            INNER JOIN delivery_agents da ON da.user_id = u.id
            WHERE u.id = ?
            LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $userId);
    mysqli_stmt_execute($stmt);
    return mysqli_fetch_assoc(mysqli_stmt_get_result($stmt)) ?: null;
}

function getAvailableAssignments($conn)
{
    $sql = "SELECT o.id AS order_id, o.delivery_address, o.delivery_fee, o.total_amount,
                   o.estimated_delivery_minutes, o.created_at,
                   r.name AS restaurant_name, r.address AS restaurant_address, r.city,
                   COUNT(oi.id) AS item_count
            FROM orders o
            INNER JOIN restaurants r ON r.id = o.restaurant_id
            LEFT JOIN order_items oi ON oi.order_id = o.id
            WHERE o.status = 'ready'
              AND o.agent_id IS NULL
              AND NOT EXISTS (
                  SELECT 1 FROM delivery_assignments da
                  WHERE da.order_id = o.id AND da.status IN ('assigned', 'picked_up')
              )
            GROUP BY o.id, o.delivery_address, o.delivery_fee, o.total_amount,
                     o.estimated_delivery_minutes, o.created_at,
                     r.name, r.address, r.city
            ORDER BY o.created_at ASC";
    $result = mysqli_query($conn, $sql);
    return $result ? mysqli_fetch_all($result, MYSQLI_ASSOC) : [];
}

function acceptAssignment($conn, $orderId, $agentId, $userId)
{
    if ($orderId <= 0 || $agentId <= 0) return false;

    mysqli_begin_transaction($conn);

    $activeSql = "SELECT id FROM delivery_assignments
                  WHERE agent_id = ? AND status IN ('assigned', 'picked_up')
                  LIMIT 1";
    $activeStmt = mysqli_prepare($conn, $activeSql);
    mysqli_stmt_bind_param($activeStmt, 'i', $agentId);
    mysqli_stmt_execute($activeStmt);
    if (mysqli_fetch_assoc(mysqli_stmt_get_result($activeStmt))) {
        mysqli_rollback($conn);
        return false;
    }

    $sql = "UPDATE orders
            SET agent_id = ?
            WHERE id = ? AND status = 'ready' AND agent_id IS NULL";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'ii', $agentId, $orderId);

    if (!mysqli_stmt_execute($stmt) || mysqli_stmt_affected_rows($stmt) !== 1) {
        mysqli_rollback($conn);
        return false;
    }

    $sql2 = "INSERT INTO delivery_assignments (order_id, agent_id, assigned_at, picked_up_at, delivered_at, status)
             VALUES (?, ?, NOW(), NULL, NULL, 'assigned')
             ON DUPLICATE KEY UPDATE
                agent_id = VALUES(agent_id),
                assigned_at = NOW(),
                picked_up_at = NULL,
                delivered_at = NULL,
                status = 'assigned'";
    $stmt2 = mysqli_prepare($conn, $sql2);
    mysqli_stmt_bind_param($stmt2, 'ii', $orderId, $agentId);

    if (!mysqli_stmt_execute($stmt2)) {
        mysqli_rollback($conn);
        return false;
    }

    mysqli_commit($conn);
    return true;
}

function declineAssignment($conn, $orderId, $agentId)
{
    if ($orderId <= 0 || $agentId <= 0) return false;

    mysqli_begin_transaction($conn);

    $sql = "UPDATE delivery_assignments
            SET status = 'cancelled'
            WHERE order_id = ? AND agent_id = ? AND status = 'assigned' AND picked_up_at IS NULL";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'ii', $orderId, $agentId);
    mysqli_stmt_execute($stmt);

    $sql2 = "UPDATE orders
             SET agent_id = NULL, status = 'ready'
             WHERE id = ? AND agent_id = ? AND status = 'ready'";
    $stmt2 = mysqli_prepare($conn, $sql2);
    mysqli_stmt_bind_param($stmt2, 'ii', $orderId, $agentId);

    if (!mysqli_stmt_execute($stmt2)) {
        mysqli_rollback($conn);
        return false;
    }

    mysqli_commit($conn);
    return true;
}

function getOrderItemsForOrder($conn, $orderId)
{
    $sql = "SELECT mi.name, oi.quantity, oi.unit_price
            FROM order_items oi
            INNER JOIN menu_items mi ON mi.id = oi.menu_item_id
            WHERE oi.order_id = ?
            ORDER BY oi.id ASC";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $orderId);
    mysqli_stmt_execute($stmt);
    return mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);
}

function getAgentActiveDelivery($conn, $agentId)
{
    $sql = "SELECT o.id AS order_id, o.delivery_address, o.delivery_fee, o.total_amount,
                   o.status AS order_status, o.created_at,
                   da.status AS assignment_status, da.assigned_at, da.picked_up_at, da.delivered_at,
                   r.name AS restaurant_name, r.address AS restaurant_address, r.city,
                   u.name AS customer_name, u.phone AS customer_phone
            FROM delivery_assignments da
            INNER JOIN orders o ON o.id = da.order_id
            INNER JOIN restaurants r ON r.id = o.restaurant_id
            INNER JOIN users u ON u.id = o.customer_id
            WHERE da.agent_id = ? AND da.status IN ('assigned', 'picked_up')
            ORDER BY da.assigned_at DESC
            LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $agentId);
    mysqli_stmt_execute($stmt);
    $delivery = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    if (!$delivery) return null;

    $delivery['items'] = getOrderItemsForOrder($conn, (int)$delivery['order_id']);
    $delivery['special_instructions'] = '';

    if ($delivery['assignment_status'] === 'assigned' && empty($delivery['picked_up_at'])) {
        $delivery['delivery_stage'] = 'accepted';
        $delivery['delivery_stage_label'] = 'Accepted';
    } elseif ($delivery['assignment_status'] === 'assigned') {
        $delivery['delivery_stage'] = 'picked_up';
        $delivery['delivery_stage_label'] = 'Picked Up';
    } elseif ($delivery['assignment_status'] === 'picked_up') {
        $delivery['delivery_stage'] = 'on_the_way';
        $delivery['delivery_stage_label'] = 'On the Way';
    } else {
        $delivery['delivery_stage'] = $delivery['assignment_status'];
        $delivery['delivery_stage_label'] = ucwords(str_replace('_', ' ', $delivery['assignment_status']));
    }

    return $delivery;
}

function updateDeliveryStatus($conn, $orderId, $agentId, $newStatus)
{
    if ($orderId <= 0 || $agentId <= 0) return false;

    mysqli_begin_transaction($conn);

    if ($newStatus === 'picked_up') {
        $sql = "UPDATE delivery_assignments
                SET picked_up_at = COALESCE(picked_up_at, NOW())
                WHERE order_id = ? AND agent_id = ? AND status = 'assigned' AND picked_up_at IS NULL";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'ii', $orderId, $agentId);
        $ok1 = mysqli_stmt_execute($stmt) && mysqli_stmt_affected_rows($stmt) === 1;

        $sql2 = "UPDATE orders SET status = 'picked_up'
                 WHERE id = ? AND agent_id = ? AND status = 'ready'";
        $stmt2 = mysqli_prepare($conn, $sql2);
        mysqli_stmt_bind_param($stmt2, 'ii', $orderId, $agentId);
        $ok2 = mysqli_stmt_execute($stmt2);
    } elseif ($newStatus === 'on_the_way') {
        $sql = "UPDATE delivery_assignments
                SET status = 'picked_up'
                WHERE order_id = ? AND agent_id = ? AND status = 'assigned' AND picked_up_at IS NOT NULL";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'ii', $orderId, $agentId);
        $ok1 = mysqli_stmt_execute($stmt) && mysqli_stmt_affected_rows($stmt) === 1;

        $sql2 = "UPDATE orders SET status = 'picked_up'
                 WHERE id = ? AND agent_id = ? AND status = 'picked_up'";
        $stmt2 = mysqli_prepare($conn, $sql2);
        mysqli_stmt_bind_param($stmt2, 'ii', $orderId, $agentId);
        $ok2 = mysqli_stmt_execute($stmt2);
    } elseif ($newStatus === 'delivered') {
        $sql = "UPDATE delivery_assignments
                SET status = 'delivered', delivered_at = NOW()
                WHERE order_id = ? AND agent_id = ? AND status = 'picked_up'";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'ii', $orderId, $agentId);
        $ok1 = mysqli_stmt_execute($stmt) && mysqli_stmt_affected_rows($stmt) === 1;

        $sql2 = "UPDATE orders SET status = 'delivered'
                 WHERE id = ? AND agent_id = ? AND status = 'picked_up'";
        $stmt2 = mysqli_prepare($conn, $sql2);
        mysqli_stmt_bind_param($stmt2, 'ii', $orderId, $agentId);
        $ok2 = mysqli_stmt_execute($stmt2);

        $sql3 = "UPDATE delivery_agents da
                 INNER JOIN orders o ON o.agent_id = da.id
                 SET da.total_earnings = da.total_earnings + o.delivery_fee
                 WHERE o.id = ? AND da.id = ?";
        $stmt3 = mysqli_prepare($conn, $sql3);
        mysqli_stmt_bind_param($stmt3, 'ii', $orderId, $agentId);
        $ok2 = $ok2 && mysqli_stmt_execute($stmt3);
    } else {
        mysqli_rollback($conn);
        return false;
    }

    if (!$ok1 || !$ok2) {
        mysqli_rollback($conn);
        return false;
    }

    mysqli_commit($conn);
    return true;
}

function toggleAgentOnlineStatus($conn, $userId, $isOnline)
{
    $sql = "UPDATE delivery_agents SET is_online = ? WHERE user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'ii', $isOnline, $userId);
    return mysqli_stmt_execute($stmt);
}

function updateAgentProfile($conn, $userId, $name, $phone, $vehicleType, $profilePic = null)
{
    mysqli_begin_transaction($conn);

    if ($profilePic !== null && $profilePic !== '') {
        $sql = "UPDATE users SET name = ?, phone = ?, profile_pic = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'sssi', $name, $phone, $profilePic, $userId);
    } else {
        $sql = "UPDATE users SET name = ?, phone = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'ssi', $name, $phone, $userId);
    }

    if (!mysqli_stmt_execute($stmt)) {
        mysqli_rollback($conn);
        return false;
    }

    $sql2 = "UPDATE delivery_agents SET vehicle_type = ? WHERE user_id = ?";
    $stmt2 = mysqli_prepare($conn, $sql2);
    mysqli_stmt_bind_param($stmt2, 'si', $vehicleType, $userId);

    if (!mysqli_stmt_execute($stmt2)) {
        mysqli_rollback($conn);
        return false;
    }

    mysqli_commit($conn);
    return true;
}

function getAgentDeliveryHistory($conn, $agentId)
{
    $sql = "SELECT da.delivered_at, o.delivery_fee,
                   o.delivery_address AS customer_area,
                   r.name AS restaurant_name
            FROM delivery_assignments da
            INNER JOIN orders o ON o.id = da.order_id
            INNER JOIN restaurants r ON r.id = o.restaurant_id
            WHERE da.agent_id = ? AND da.status = 'delivered'
            ORDER BY da.delivered_at DESC";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $agentId);
    mysqli_stmt_execute($stmt);
    return mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);
}

function getAgentEarningsSummary($conn, $agentId)
{
    $sql = "SELECT
                COALESCE(SUM(CASE WHEN DATE(da.delivered_at) = CURDATE() THEN o.delivery_fee ELSE 0 END), 0) AS today,
                COALESCE(SUM(CASE WHEN YEARWEEK(da.delivered_at, 1) = YEARWEEK(CURDATE(), 1) THEN o.delivery_fee ELSE 0 END), 0) AS week,
                COALESCE(SUM(CASE WHEN YEAR(da.delivered_at) = YEAR(CURDATE()) AND MONTH(da.delivered_at) = MONTH(CURDATE()) THEN o.delivery_fee ELSE 0 END), 0) AS month,
                COALESCE(SUM(o.delivery_fee), 0) AS all_time
            FROM delivery_assignments da
            INNER JOIN orders o ON o.id = da.order_id
            WHERE da.agent_id = ? AND da.status = 'delivered'";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $agentId);
    mysqli_stmt_execute($stmt);
    return mysqli_fetch_assoc(mysqli_stmt_get_result($stmt)) ?: ['today' => 0, 'week' => 0, 'month' => 0, 'all_time' => 0];
}

function getAgentPerformanceStats($conn, $agentId, $userId)
{
    $sql = "SELECT COUNT(*) AS total_deliveries,
                   COALESCE(AVG(TIMESTAMPDIFF(MINUTE, assigned_at, delivered_at)), 0) AS average_delivery_time
            FROM delivery_assignments
            WHERE agent_id = ? AND status = 'delivered'";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $agentId);
    mysqli_stmt_execute($stmt);
    $stats = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    $profile = getAgentProfile($conn, $userId);
    $agentName = '%' . ($profile['name'] ?? '') . '%';
    $agentEmail = '%' . ($profile['email'] ?? '') . '%';
    $agentToken = '%agent #' . $agentId . '%';

    $sql2 = "SELECT COUNT(*) AS complaints_count
             FROM complaints
             WHERE subject LIKE ?
                OR description LIKE ?
                OR subject LIKE ?
                OR description LIKE ?
                OR subject LIKE ?
                OR description LIKE ?";
    $stmt2 = mysqli_prepare($conn, $sql2);
    mysqli_stmt_bind_param($stmt2, 'ssssss', $agentName, $agentName, $agentEmail, $agentEmail, $agentToken, $agentToken);
    mysqli_stmt_execute($stmt2);
    $complaints = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt2));

    return [
        'total_deliveries' => (int)($stats['total_deliveries'] ?? 0),
        'average_delivery_time' => round((float)($stats['average_delivery_time'] ?? 0)),
        'complaints_count' => (int)($complaints['complaints_count'] ?? 0)
    ];
}

function getNewAssignmentsSince($conn, $lastChecked)
{
    $sql = "SELECT COUNT(*) AS new_count
            FROM orders o
            WHERE o.status = 'ready'
              AND o.agent_id IS NULL
              AND o.created_at > ?
              AND NOT EXISTS (
                  SELECT 1 FROM delivery_assignments da
                  WHERE da.order_id = o.id AND da.status IN ('assigned', 'picked_up')
              )";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 's', $lastChecked);
    mysqli_stmt_execute($stmt);
    $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    return (int)($row['new_count'] ?? 0);
}
