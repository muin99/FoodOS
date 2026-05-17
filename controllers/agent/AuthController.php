<?php
// AuthController.php
include "../../config.php";
include "../../models/DeliveryAgentModel.php";

$model   = new DeliveryAgentModel($conn);
$success = "";
$error   = "";

// -------------------------------------------------------
// REGISTER
// -------------------------------------------------------
if (isset($_POST["register"])) {

    $name     = trim($_POST["name"]);
    $email    = trim($_POST["email"]);
    $phone    = trim($_POST["phone"]);
    $vehicle  = $_POST["vehicle"];
    $password = $_POST["password"];
    $confirm  = $_POST["confirm_password"];

    if (empty($name) || empty($email) || empty($phone) || empty($password) || empty($vehicle)) {
        $error = "Please fill all the fields.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";

    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";

    } elseif ($password !== $confirm) {
        $error = "Passwords do not match.";

    } elseif ($model->emailExists($email)) {
        $error = "This email is already registered.";

    } else {
        $result = $model->registration($name, $email, $phone, $vehicle, $password);
        if ($result) {
            $success = "Registration submitted! Wait for admin approval before logging in.";
        } else {
            $error = "Registration failed. Please try again.";
        }
    }
}

// -------------------------------------------------------
// LOGIN
// -------------------------------------------------------
if (isset($_POST["login"])) {

    $email    = trim($_POST["email"]);
    $password = $_POST["password"];

    if (empty($email) || empty($password)) {
        $error = "Please fill all the fields.";
    } else {
        $result = $model->login($email, $password);
        if ($result["success"]) {
            header("location:../../views/agent/dashboard.php");
            exit();
        } else {
            $error = $result["message"];
        }
    }
}
?>
