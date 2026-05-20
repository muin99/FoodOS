<?php

function managerLogin($conn, $email, $password)
{
    $sql = "SELECT * FROM users WHERE email = ? AND role = 'manager' LIMIT 1";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, 's', $email);

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $user = mysqli_fetch_assoc($result);

    if ($user == null) {
        return false;
    }

    if ($user['is_active'] != 1) {
        return false;
    }

    if (password_verify($password, $user['password_hash']) || $password === $user['password_hash']) {
        return $user;
    }

    return false;
}

function managerEmailExists($conn, $email)
{
    $sql = "SELECT id FROM users WHERE email = ? LIMIT 1";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, 's', $email);

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $user = mysqli_fetch_assoc($result);

    return $user != null;
}

function managerRegister($conn, $name, $email, $phone, $password, $restaurantName, $cuisineType, $address)
{
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    $role = 'manager';

    mysqli_begin_transaction($conn);

    $sql = "INSERT INTO users (name, email, password_hash, phone, role, is_active)
            VALUES (?, ?, ?, ?, ?, 2)";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, 'sssss',
        $name,
        $email,
        $passwordHash,
        $phone,
        $role
    );

    if (!mysqli_stmt_execute($stmt)) {
        mysqli_rollback($conn);
        return false;
    }

    $userId = mysqli_insert_id($conn);
    $city = 'Dhaka';
    $description = $cuisineType . ' restaurant';

    $sql = "INSERT INTO restaurants (manager_id, name, description, cuisine_type, address, city, is_open, is_approved)
            VALUES (?, ?, ?, ?, ?, ?, 0, 1)";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        'isssss',
        $userId,
        $restaurantName,
        $description,
        $cuisineType,
        $address,
        $city
    );

    if (!mysqli_stmt_execute($stmt)) {
        mysqli_rollback($conn);
        return false;
    }

    mysqli_commit($conn);
    return $userId;
}
