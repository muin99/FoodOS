<?php

function updateRestaurantProfile(
    $conn,
    $managerId,
    $name,
    $description,
    $cuisineType,
    $address,
    $city,
    $deliveryRadius,
    $isOpen
) {

    $sql = "
        UPDATE restaurants
        SET
            name = ?,
            description = ?,
            cuisine_type = ?,
            address = ?,
            city = ?,
            delivery_radius_km = ?,
            is_open = ?
        WHERE manager_id = ?
    ";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        'sssssiii',
        $name,
        $description,
        $cuisineType,
        $address,
        $city,
        $deliveryRadius,
        $isOpen,
        $managerId
    );

    return mysqli_stmt_execute($stmt);
}