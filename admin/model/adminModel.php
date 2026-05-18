<?php

function adminLogin($conn, $email)
{
    $sql = "SELECT * FROM admins WHERE email = '$email' LIMIT 1";
    $result = mysqli_query($conn, $sql);

    return mysqli_fetch_assoc($result);
}