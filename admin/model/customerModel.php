<?php

function getAllCustomers($conn)
{
    $sql = "
        SELECT *
        FROM users
        WHERE role = 'customer'
        ORDER BY id DESC
    ";

    $res = mysqli_query($conn, $sql);

    $data = [];

    while ($row = mysqli_fetch_assoc($res)) {
        $data[] = $row;
    }

    return $data;
}


function activateCustomer($conn, $id)
{
    $sql = "UPDATE users SET is_active = 1 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}


function deactivateCustomer($conn, $id)
{
    $sql = "UPDATE users SET is_active = 0 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}