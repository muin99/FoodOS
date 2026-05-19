<?php
include '../../dirCommon/dbconnect.php';
include '../model/orderModel.php';

$filters = [
    'status' => $_GET['status'] ?? '',
    'restaurant_id' => $_GET['restaurant_id'] ?? '',
    'customer_id' => $_GET['customer_id'] ?? '',
    'from' => $_GET['from'] ?? '',
    'to' => $_GET['to'] ?? ''
];

$orders = getAllOrders($conn, $filters);