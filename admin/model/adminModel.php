<?php

function adminLogin($conn, $email)
{
    $sql = "SELECT * FROM users WHERE email = ? AND role = 'admin' LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    return mysqli_fetch_assoc($result);
}
