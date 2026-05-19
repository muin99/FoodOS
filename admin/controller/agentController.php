<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../../dirCommon/dbconnect.php';
include '../model/agentModel.php';

$action = $_POST['action'] ?? null;
$id     = $_POST['id'] ?? null;


// HANDLE ACTIONS
if ($action && $id) {

    switch ($action) {

        case 'activate':
            activateAgent($conn, $id);
            break;

        case 'deactivate':
            deactivateAgent($conn, $id);
            break;
    }

    header("Location: ../view/agents.php");
    exit;
}

$agents = getAllAgents($conn);