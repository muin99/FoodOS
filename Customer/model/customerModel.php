<?php

function getApprovedRestaurants($conn, $search = '', $cuisine = '')
{
    $sql = "
        SELECT
            r.*,
            COALESCE(AVG(rv.rating), 0) AS rating,
            COUNT(rv.id) AS review_count
        FROM restaurants r
        LEFT JOIN reviews rv ON rv.restaurant_id = r.id
        WHERE r.is_approved = 1
    ";

    $types = '';
    $params = [];

    if ($search !== '') {
        $sql .= " AND (r.name LIKE ? OR r.cuisine_type LIKE ? OR r.description LIKE ?)";
        $searchTerm = '%' . $search . '%';
        $types .= 'sss';
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $params[] = $searchTerm;
    }

    if ($cuisine !== '') {
        $sql .= " AND r.cuisine_type = ?";
        $types .= 's';
        $params[] = $cuisine;
    }

    $sql .= " GROUP BY r.id ORDER BY r.is_open DESC, r.name ASC";

    $stmt = mysqli_prepare($conn, $sql);
    if ($types !== '') {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $restaurants = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $restaurants[] = $row;
    }

    return $restaurants;
}

function getCuisineTypes($conn)
{
    $sql = "SELECT DISTINCT cuisine_type FROM restaurants WHERE is_approved = 1 AND cuisine_type IS NOT NULL AND cuisine_type != '' ORDER BY cuisine_type";
    $result = mysqli_query($conn, $sql);
    $types = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $types[] = $row['cuisine_type'];
    }

    return $types;
}

function getRestaurantById($conn, $restaurantId)
{
    $sql = "
        SELECT r.*, COALESCE(AVG(rv.rating), 0) AS rating, COUNT(rv.id) AS review_count
        FROM restaurants r
        LEFT JOIN reviews rv ON rv.restaurant_id = r.id
        WHERE r.id = ? AND r.is_approved = 1
        GROUP BY r.id
        LIMIT 1
    ";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $restaurantId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    return mysqli_fetch_assoc($result);
}

function getRestaurantMenu($conn, $restaurantId)
{
    $sql = "
        SELECT
            mi.*,
            mc.name AS category_name
        FROM menu_items mi
        INNER JOIN menu_categories mc ON mc.id = mi.category_id
        WHERE mi.restaurant_id = ?
        ORDER BY mc.display_order, mi.name
    ";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $restaurantId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $items = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $items[] = $row;
    }

    return $items;
}

function getCustomerAddresses($conn, $customerId)
{
    $sql = "SELECT * FROM delivery_addresses WHERE customer_id = ? ORDER BY is_default DESC, id DESC";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $customerId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $addresses = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $addresses[] = $row;
    }

    return $addresses;
}

function saveCustomerAddress($conn, $customerId, $label, $addressLine, $city)
{
    $isDefault = 0;
    $existing = getCustomerAddresses($conn, $customerId);
    if (count($existing) === 0) {
        $isDefault = 1;
    }

    $sql = "INSERT INTO delivery_addresses (customer_id, label, address_line, city, is_default) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'isssi', $customerId, $label, $addressLine, $city, $isDefault);

    return mysqli_stmt_execute($stmt);
}

function createCustomerOrder($conn, $customerId, $restaurantId, $items, $deliveryAddress, $paymentMethod)
{
    if ($customerId <= 0 || $restaurantId <= 0 || count($items) === 0 || trim($deliveryAddress) === '') {
        return false;
    }

    $ids = [];
    foreach ($items as $item) {
        $id = (int)($item['id'] ?? 0);
        $quantity = (int)($item['quantity'] ?? 0);
        if ($id > 0 && $quantity > 0) {
            $ids[$id] = ($ids[$id] ?? 0) + $quantity;
        }
    }

    if (count($ids) === 0) return false;

    $sql = "SELECT id, price FROM menu_items WHERE restaurant_id = ? AND is_available = 1";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $restaurantId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $dbItems = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $id = (int)$row['id'];
        if (isset($ids[$id])) {
            $dbItems[$id] = (float)$row['price'];
        }
    }

    if (count($dbItems) === 0) return false;

    $subtotal = 0;
    foreach ($ids as $id => $quantity) {
        if (!isset($dbItems[$id])) continue;
        $subtotal += $dbItems[$id] * $quantity;
    }

    if ($subtotal <= 0) return false;

    $deliveryFee = 50.00;
    $total = $subtotal + $deliveryFee;
    $status = 'pending';
    $estimatedMinutes = 35;

    mysqli_begin_transaction($conn);

    $sql = "
        INSERT INTO orders
            (customer_id, restaurant_id, delivery_address, payment_method, subtotal, delivery_fee, total_amount, status, estimated_delivery_minutes)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'iissdddsi', $customerId, $restaurantId, $deliveryAddress, $paymentMethod, $subtotal, $deliveryFee, $total, $status, $estimatedMinutes);

    if (!mysqli_stmt_execute($stmt)) {
        mysqli_rollback($conn);
        return false;
    }

    $orderId = mysqli_insert_id($conn);

    $sql = "INSERT INTO order_items (order_id, menu_item_id, quantity, unit_price) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    foreach ($ids as $id => $quantity) {
        if (!isset($dbItems[$id])) continue;
        $unitPrice = $dbItems[$id];
        mysqli_stmt_bind_param($stmt, 'iiid', $orderId, $id, $quantity, $unitPrice);

        if (!mysqli_stmt_execute($stmt)) {
            mysqli_rollback($conn);
            return false;
        }
    }

    mysqli_commit($conn);
    return $orderId;
}

function getCustomerOrders($conn, $customerId)
{
    $sql = "
        SELECT o.*, r.name AS restaurant_name
        FROM orders o
        INNER JOIN restaurants r ON r.id = o.restaurant_id
        WHERE o.customer_id = ?
        ORDER BY o.created_at DESC, o.id DESC
    ";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $customerId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $orders = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $orders[] = $row;
    }

    return $orders;
}

function getCustomerProfile($conn, $customerId)
{
    $sql = "SELECT id, name, email, phone, created_at FROM users WHERE id = ? AND role = 'customer' LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $customerId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    return mysqli_fetch_assoc($result);
}

function updateCustomerProfile($conn, $customerId, $name, $phone)
{
    $sql = "UPDATE users SET name = ?, phone = ? WHERE id = ? AND role = 'customer'";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'ssi', $name, $phone, $customerId);

    return mysqli_stmt_execute($stmt);
}
