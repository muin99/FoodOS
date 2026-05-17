<?php
// ProfileController.php
include "../../config.php";
include "../../models/DeliveryAgentModel.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "agent") {
    header("location:../../views/agent/login.php");
    exit();
}

$model   = new DeliveryAgentModel($conn);
$agent   = $model->getByUserId($_SESSION["user_id"]);
$success = "";
$error   = "";

// -------------------------------------------------------
// UPDATE PROFILE
// -------------------------------------------------------
if (isset($_POST["update_profile"])) {

    $name         = trim($_POST["name"]);
    $phone        = trim($_POST["phone"]);
    $vehicle_type = $_POST["vehicle_type"];
    $profile_pic  = null;

    if (empty($name) || empty($phone) || empty($vehicle_type)) {
        $error = "Please fill all the fields.";
    } else {
        // Profile picture upload
        if (!empty($_FILES["profile_pic"]["name"])) {
            $allowed = ["jpg", "jpeg", "png", "gif"];
            $ext     = strtolower(pathinfo($_FILES["profile_pic"]["name"], PATHINFO_EXTENSION));

            if (!in_array($ext, $allowed)) {
                $error = "Only JPG, PNG, GIF images allowed.";
            } else {
                $upload_dir = "../../assets/uploads/profiles/";
                if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
                $filename = "agent_" . $_SESSION["user_id"] . "_" . time() . "." . $ext;
                if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $upload_dir . $filename)) {
                    $profile_pic = "assets/uploads/profiles/" . $filename;
                } else {
                    $error = "Failed to upload image.";
                }
            }
        }

        if (empty($error)) {
            $model->updateProfile($_SESSION["user_id"], $name, $phone, $vehicle_type, $profile_pic);
            $_SESSION["name"] = $name;
            $success = "Profile updated successfully.";
            $agent   = $model->getByUserId($_SESSION["user_id"]);
        }
    }
}

// -------------------------------------------------------
// CHANGE PASSWORD
// -------------------------------------------------------
if (isset($_POST["change_password"])) {

    $new_password = $_POST["new_password"];
    $confirm      = $_POST["confirm_password"];

    if (empty($new_password) || empty($confirm)) {
        $error = "Please fill all fields.";
    } elseif (strlen($new_password) < 6) {
        $error = "Password must be at least 6 characters.";
    } elseif ($new_password !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        $model->changePassword($_SESSION["user_id"], $new_password);
        $success = "Password changed successfully.";
    }
}

// -------------------------------------------------------
// TOGGLE ONLINE / OFFLINE
// -------------------------------------------------------
if (isset($_POST["toggle_online"])) {
    $new_status = $agent["is_online"] ? 0 : 1;
    $model->setOnline($_SESSION["user_id"], $new_status);
    header("location:../../views/agent/dashboard.php");
    exit();
}
?>
