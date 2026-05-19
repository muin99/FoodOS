<?php

include '../../dirCommon/dbconnect.php';
include '../model/dashboardModel.php';

$totalUsers = getTotalUsers($conn);
$totalRestaurants = getActiveRestaurants($conn);
$totalOrdersToday = getTotalOrdersToday($conn);
$totalActiveAgents = getActiveDeliveryAgents($conn);
?>