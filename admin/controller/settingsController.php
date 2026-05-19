<?php

include '../../dirCommon/dbconnect.php';
include '../model/settingsModel.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['commission_rate'])) {
        setPlatformSetting($conn, 'commission_rate', $_POST['commission_rate']);
    }

    if (isset($_POST['delivery_fee'])) {
        setPlatformSetting($conn, 'base_delivery_fee', $_POST['delivery_fee']);
    }
}

$commissionRate = getPlatformSetting($conn, 'commission_rate');
$deliveryFee    = getPlatformSetting($conn, 'base_delivery_fee');


$totalRevenue   = getTotalRevenue($conn);
$orderStatus    = getOrdersByStatus($conn);
$topRestaurants = getTopRestaurants($conn);
$topAgents      = getTopAgents($conn);
$peakHours      = getPeakHours($conn);

$avgDeliveryTime = getAverageDeliveryTime($conn);
$onTimeRate      = getOnTimeRate($conn);
$failedDeliveries = getFailedDeliveries($conn);

$monthlySummary = getMonthlySummary($conn);