<?php

function managerLogin($conn, $email, $password)
{
    $sql = "SELECT u.*, r.id AS restaurant_id, r.is_approved
            FROM users u
            LEFT JOIN restaurants r ON r.manager_id = u.id
            WHERE u.email = ? AND u.role = 'manager'
            LIMIT 1";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $manager = mysqli_fetch_assoc($result);

    if ($manager == null) {
        return false;
    }

    if ($manager['is_active'] != 1) {
        return false;
    }

    if (password_verify($password, $manager['password_hash']) || $password === $manager['password_hash']) {
        return $manager;
    }

    return false;
}
