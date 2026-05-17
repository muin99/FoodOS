<?php
/**
 * MODEL: delivery_agents
 * Table: id, user_id, vehicle_type, is_online, current_location_text,
 *        total_earnings, is_approved
 */

class DeliveryAgentModel
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    // -------------------------------------------------------
    // EMAIL EXISTS CHECK
    // -------------------------------------------------------
    public function emailExists($email)
    {
        $stmt = $this->conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        $count = $stmt->num_rows;
        $stmt->close();
        return $count > 0;
    }

    // -------------------------------------------------------
    // REGISTRATION
    // -------------------------------------------------------
    public function registration($name, $email, $phone, $vehicle, $password)
    {
        $password_hash = password_hash($password, PASSWORD_BCRYPT);

        // Step 1: users table e insert
        $stmt = $this->conn->prepare(
            "INSERT INTO users (name, email, phone, password_hash, role, is_active)
             VALUES (?, ?, ?, ?, 'agent', 1)"
        );
        $stmt->bind_param("ssss", $name, $email, $phone, $password_hash);

        if (!$stmt->execute()) {
            $stmt->close();
            return false;
        }

        $user_id = $this->conn->insert_id;
        $stmt->close();

        // Step 2: delivery_agents table e insert
        $stmt2 = $this->conn->prepare(
            "INSERT INTO delivery_agents (user_id, vehicle_type, is_online, total_earnings, is_approved)
             VALUES (?, ?, 0, 0.00, 0)"
        );
        $stmt2->bind_param("is", $user_id, $vehicle);
        $result = $stmt2->execute();
        $stmt2->close();

        return $result;
    }

    // -------------------------------------------------------
    // LOGIN
    // -------------------------------------------------------
    public function login($email, $password)
    {
        $stmt = $this->conn->prepare(
            "SELECT u.id, u.name, u.email, u.phone, u.password_hash,
                    u.is_active, da.is_approved, da.vehicle_type, da.is_online
             FROM users u
             JOIN delivery_agents da ON da.user_id = u.id
             WHERE u.email = ? AND u.role = 'agent'"
        );
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user   = $result->fetch_assoc();
        $stmt->close();

        if (!$user) {
            return ["success" => false, "message" => "Email or password is wrong."];
        }

        if (!password_verify($password, $user["password_hash"])) {
            return ["success" => false, "message" => "Email or password is wrong."];
        }

        if (!$user["is_active"]) {
            return ["success" => false, "message" => "Your account has been deactivated."];
        }

        if (!$user["is_approved"]) {
            return ["success" => false, "message" => "Admin has not approved your account yet. Please wait."];
        }

        $_SESSION["user_id"] = $user["id"];
        $_SESSION["role"]    = "agent";
        $_SESSION["name"]    = $user["name"];

        return ["success" => true, "user" => $user];
    }

    // -------------------------------------------------------
    // GET AGENT BY USER ID
    // -------------------------------------------------------
    public function getByUserId($user_id)
    {
        $stmt = $this->conn->prepare(
            "SELECT da.*, u.name, u.email, u.phone, u.profile_pic, u.is_active
             FROM delivery_agents da
             JOIN users u ON u.id = da.user_id
             WHERE da.user_id = ?"
        );
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row;
    }

    // -------------------------------------------------------
    // UPDATE PROFILE
    // -------------------------------------------------------
    public function updateProfile($user_id, $name, $phone, $vehicle_type, $profile_pic = null)
    {
        if ($profile_pic != null) {
            $stmt = $this->conn->prepare(
                "UPDATE users SET name = ?, phone = ?, profile_pic = ? WHERE id = ?"
            );
            $stmt->bind_param("sssi", $name, $phone, $profile_pic, $user_id);
        } else {
            $stmt = $this->conn->prepare(
                "UPDATE users SET name = ?, phone = ? WHERE id = ?"
            );
            $stmt->bind_param("ssi", $name, $phone, $user_id);
        }
        $stmt->execute();
        $stmt->close();

        $stmt2 = $this->conn->prepare(
            "UPDATE delivery_agents SET vehicle_type = ? WHERE user_id = ?"
        );
        $stmt2->bind_param("si", $vehicle_type, $user_id);
        $result = $stmt2->execute();
        $stmt2->close();
        return $result;
    }

    // -------------------------------------------------------
    // CHANGE PASSWORD
    // -------------------------------------------------------
    public function changePassword($user_id, $new_password)
    {
        $hash = password_hash($new_password, PASSWORD_BCRYPT);
        $stmt = $this->conn->prepare(
            "UPDATE users SET password_hash = ? WHERE id = ?"
        );
        $stmt->bind_param("si", $hash, $user_id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    // -------------------------------------------------------
    // ONLINE / OFFLINE TOGGLE
    // -------------------------------------------------------
    public function setOnline($user_id, $is_online)
    {
        $stmt = $this->conn->prepare(
            "UPDATE delivery_agents SET is_online = ? WHERE user_id = ?"
        );
        $stmt->bind_param("ii", $is_online, $user_id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    // -------------------------------------------------------
    // EARNINGS SUMMARY
    // -------------------------------------------------------
    public function getEarningsSummary($user_id)
    {
        $stmt = $this->conn->prepare(
            "SELECT total_earnings FROM delivery_agents WHERE user_id = ?"
        );
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $row      = $stmt->get_result()->fetch_assoc();
        $all_time = (float)($row["total_earnings"] ?? 0);
        $stmt->close();

        $stmt2 = $this->conn->prepare(
            "SELECT
                SUM(CASE WHEN DATE(da.delivered_at) = CURDATE()
                    THEN o.delivery_fee ELSE 0 END) AS today,
                SUM(CASE WHEN YEARWEEK(da.delivered_at,1) = YEARWEEK(NOW(),1)
                    THEN o.delivery_fee ELSE 0 END) AS this_week,
                SUM(CASE WHEN MONTH(da.delivered_at) = MONTH(NOW())
                    AND YEAR(da.delivered_at) = YEAR(NOW())
                    THEN o.delivery_fee ELSE 0 END) AS this_month
             FROM delivery_assignments da
             JOIN orders o ON o.id = da.order_id
             WHERE da.agent_id = ? AND da.status = 'delivered'"
        );
        $stmt2->bind_param("i", $user_id);
        $stmt2->execute();
        $row2 = $stmt2->get_result()->fetch_assoc();
        $stmt2->close();

        return [
            "today"      => (float)($row2["today"]      ?? 0),
            "this_week"  => (float)($row2["this_week"]  ?? 0),
            "this_month" => (float)($row2["this_month"] ?? 0),
            "all_time"   => $all_time
        ];
    }

    // -------------------------------------------------------
    // PERFORMANCE STATS
    // -------------------------------------------------------
    public function getPerformanceStats($user_id)
    {
        $stmt = $this->conn->prepare(
            "SELECT
                COUNT(*) AS total_deliveries,
                AVG(TIMESTAMPDIFF(MINUTE, da.assigned_at, da.delivered_at)) AS avg_minutes
             FROM delivery_assignments da
             WHERE da.agent_id = ? AND da.status = 'delivered'"
        );
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return [
            "total_deliveries"     => (int)($row["total_deliveries"] ?? 0),
            "avg_delivery_minutes" => round((float)($row["avg_minutes"] ?? 0))
        ];
    }
}
?>
