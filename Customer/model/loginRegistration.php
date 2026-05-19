<?php
function customerLogin($conn, $email, $password)
{
    $sql = "SELECT * FROM users WHERE email = ? AND role = 'customer' LIMIT 1";
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

function customerEmailExists($conn, $email)
{
    $sql = 'SELECT id FROM users WHERE email = ? LIMIT 1';
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    return $user != null;
}

function customerRegister($conn, $name, $email, $phone, $password)
{
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    $role = 'customer';

    $sql = 'INSERT INTO users (name, email, password_hash, phone, role) VALUES (?, ?, ?, ?, ?)';
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'sssss', $name, $email, $passwordHash, $phone, $role);

    if (mysqli_stmt_execute($stmt)) {
        return mysqli_insert_id($conn);
    }

    return false;
}
