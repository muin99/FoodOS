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

    if ($password == $user['password_hash'] || password_verify($password, $user['password_hash'])) {
    return $user;
}

return false;
}