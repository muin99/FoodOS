<?php

include '../../dirCommon/dbconnect.php';
include '../model/dashboardModel.php';

// if (isset($_GET['action']) && $_GET['action'] == 'stats') {

//     header('Content-Type: application/json');

//     echo json_encode([
//         "totalUsers" => $totalUsers,
//         "totalRestaurants" => $totalRestaurants,
//         "totalActiveAgents" => $totalActiveAgents,
//         "totalOrdersToday" => $totalOrdersToday
//     ]);

//     exit;
// }

$totalUsers = getTotalUsers($conn);
$totalRestaurants = getActiveRestaurants($conn);
$totalOrdersToday = getTotalOrdersToday($conn);
$totalActiveAgents = getActiveDeliveryAgents($conn);
$users = getAllUsers($conn);

?>