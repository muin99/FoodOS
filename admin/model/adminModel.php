<?php

function adminLogin($conn, $email)
{
    $sql = "SELECT * FROM users WHERE email = '$email' AND role = 'admin' LIMIT 1";
    $result = mysqli_query($conn, $sql);

    return mysqli_fetch_assoc($result);
}

