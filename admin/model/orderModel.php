<?php

function getAllOrders($conn, $filters = []) {

    $sql = "
        SELECT 
            o.*,
            u.name AS customer_name,
            r.name AS restaurant_name
        FROM orders o
        JOIN users u ON o.customer_id = u.id
        JOIN restaurants r ON o.restaurant_id = r.id
        WHERE 1=1
    ";

    // STATUS FILTER
    if (!empty($filters['status'])) {
        $status = $conn->real_escape_string($filters['status']);
        $sql .= " AND o.status = '$status'";
    }

    // RESTAURANT FILTER
    if (!empty($filters['restaurant_id'])) {
        $sql .= " AND o.restaurant_id = " . intval($filters['restaurant_id']);
    }

    // CUSTOMER FILTER
    if (!empty($filters['customer_id'])) {
        $sql .= " AND o.customer_id = " . intval($filters['customer_id']);
    }

    // DATE FILTER
    if (!empty($filters['from']) && !empty($filters['to'])) {
        $from = $conn->real_escape_string($filters['from']);
        $to   = $conn->real_escape_string($filters['to']);

        $sql .= " AND DATE(o.created_at) BETWEEN '$from' AND '$to'";
    }

    $sql .= " ORDER BY o.created_at DESC";

    return $conn->query($sql);
}