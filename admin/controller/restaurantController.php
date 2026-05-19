<?php

session_start();

include '../../dirCommon/dbconnect.php';
include '../model/restaurantModel.php';

/* =========================
   LOAD DATA
========================= */
$restaurants = getAllRestaurants($conn);

/* =========================
   ACTION HANDLER (POST)
========================= */
if (isset($_POST['action'])) {

    $id = $_POST['id'];

    if ($_POST['action'] == 'approve') {
        approveRestaurant($conn, $id);
    }

    if ($_POST['action'] == 'reject') {
        rejectRestaurant($conn, $id);
    }

    if ($_POST['action'] == 'suspend') {
        suspendRestaurant($conn, $id);
    }

    if ($_POST['action'] == 'reactivate') {
        reactivateRestaurant($conn, $id);
    }

    header("Location: ../view/restaurants.php");
    exit;
}