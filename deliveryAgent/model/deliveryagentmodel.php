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
    $user = mysqli_fetch_assoc($result);

    return $user != null;
}

function agentRegister($conn, $name, $email, $phone, $password, $vehicleType)
{
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    $role = 'agent';

    $sql = 'INSERT INTO users (name, email, password_hash, phone, role)
            VALUES (?, ?, ?, ?, ?)';

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'sssss',
        $name,
        $email,
        $passwordHash,
        $phone,
        $role
    );

    if (!mysqli_stmt_execute($stmt)) return false;

    $userId = mysqli_insert_id($conn);

    $sql2 = 'INSERT INTO delivery_agents (user_id, vehicle_type)
             VALUES (?, ?)';

    $stmt2 = mysqli_prepare($conn, $sql2);
    mysqli_stmt_bind_param($stmt2, 'is',
        $userId,
        $vehicleType
    );

    if (!mysqli_stmt_execute($stmt2)) return false;

    return $userId;
}
