<?php
/**
 * MODEL: delivery_assignments
 * Table: id, order_id, agent_id, assigned_at, picked_up_at, delivered_at, status
 */

class DeliveryAssignmentModel
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }


    public function getAvailable()
    {
        $stmt = $this->conn->prepare(
            "SELECT o.id AS order_id, o.delivery_address, o.total_amount,
                    o.delivery_fee, o.estimated_delivery_minutes, o.payment_method,
                    o.created_at,
                    r.name AS restaurant_name, r.address AS restaurant_address,
                    u.name AS customer_name
             FROM orders o
             JOIN restaurants r ON r.id = o.restaurant_id
             JOIN users u ON u.id = o.customer_id
             WHERE o.status = 'ready'
               AND o.agent_id IS NULL
             ORDER BY o.created_at ASC"
        );
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows;
    }


    public function accept($order_id, $agent_user_id)
    {
        
        $stmt = $this->conn->prepare(
            "SELECT id FROM orders WHERE id = ? AND status = 'ready' AND agent_id IS NULL"
        );
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows > 0;
        $stmt->close();

        if (!$exists) return false;

        
        $s1 = $this->conn->prepare(
            "UPDATE orders SET agent_id = ? WHERE id = ?"
        );
        $s1->bind_param("ii", $agent_user_id, $order_id);
        $s1->execute();
        $s1->close();


        $s2 = $this->conn->prepare(
            "INSERT INTO delivery_assignments (order_id, agent_id, assigned_at, status)
             VALUES (?, ?, NOW(), 'accepted')"
        );
        $s2->bind_param("ii", $order_id, $agent_user_id);
        $result = $s2->execute();
        $s2->close();

        return $result;
    }


    public function decline($assignment_id, $agent_user_id)
    {
        
        $stmt = $this->conn->prepare(
            "SELECT order_id FROM delivery_assignments WHERE id = ? AND agent_id = ?"
        );
        $stmt->bind_param("ii", $assignment_id, $agent_user_id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$row) return false;

        $order_id = $row["order_id"];

        
        $s1 = $this->conn->prepare(
            "UPDATE delivery_assignments SET status = 'declined' WHERE id = ?"
        );
        $s1->bind_param("i", $assignment_id);
        $s1->execute();
        $s1->close();

        
        $s2 = $this->conn->prepare(
            "UPDATE orders SET agent_id = NULL WHERE id = ?"
        );
        $s2->bind_param("i", $order_id);
        $s2->execute();
        $s2->close();

        return true;
    }


    public function updateStatus($assignment_id, $agent_user_id, $new_status)
    {
        $allowed = ["picked_up", "on_the_way", "delivered"];
        if (!in_array($new_status, $allowed)) return false;

        
        $stmt = $this->conn->prepare(
            "SELECT order_id FROM delivery_assignments WHERE id = ? AND agent_id = ?"
        );
        $stmt->bind_param("ii", $assignment_id, $agent_user_id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$row) return false;

        $order_id = $row["order_id"];

        if ($new_status == "picked_up") {

            $s1 = $this->conn->prepare(
                "UPDATE delivery_assignments SET status = 'picked_up', picked_up_at = NOW() WHERE id = ?"
            );
            $s1->bind_param("i", $assignment_id);
            $s1->execute();
            $s1->close();

            $s2 = $this->conn->prepare("UPDATE orders SET status = 'picked_up' WHERE id = ?");
            $s2->bind_param("i", $order_id);
            $s2->execute();
            $s2->close();

        } elseif ($new_status == "on_the_way") {

            $s1 = $this->conn->prepare(
                "UPDATE delivery_assignments SET status = 'on_the_way' WHERE id = ?"
            );
            $s1->bind_param("i", $assignment_id);
            $s1->execute();
            $s1->close();

        } elseif ($new_status == "delivered") {

            $s1 = $this->conn->prepare(
                "UPDATE delivery_assignments SET status = 'delivered', delivered_at = NOW() WHERE id = ?"
            );
            $s1->bind_param("i", $assignment_id);
            $s1->execute();
            $s1->close();

            $s2 = $this->conn->prepare("UPDATE orders SET status = 'delivered' WHERE id = ?");
            $s2->bind_param("i", $order_id);
            $s2->execute();
            $s2->close();

            
            $s3 = $this->conn->prepare(
                "UPDATE delivery_agents da
                 JOIN orders o ON o.id = ?
                 SET da.total_earnings = da.total_earnings + o.delivery_fee
                 WHERE da.user_id = ?"
            );
            $s3->bind_param("ii", $order_id, $agent_user_id);
            $s3->execute();
            $s3->close();
        }

        return true;
    }

    
    public function getActiveForAgent($agent_user_id)
    {
        $stmt = $this->conn->prepare(
            "SELECT da.id AS assignment_id, da.status AS assignment_status,
                    da.assigned_at, da.picked_up_at,
                    o.id AS order_id, o.delivery_address, o.total_amount,
                    o.delivery_fee, o.payment_method, o.estimated_delivery_minutes,
                    r.name AS restaurant_name, r.address AS restaurant_address,
                    u.name AS customer_name
             FROM delivery_assignments da
             JOIN orders o ON o.id = da.order_id
             JOIN restaurants r ON r.id = o.restaurant_id
             JOIN users u ON u.id = o.customer_id
             WHERE da.agent_id = ?
               AND da.status NOT IN ('delivered','declined')
             ORDER BY da.assigned_at DESC
             LIMIT 1"
        );
        $stmt->bind_param("i", $agent_user_id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row;
    }


    public function getOrderItems($order_id)
    {
        $stmt = $this->conn->prepare(
            "SELECT oi.quantity, oi.unit_price, mi.name AS item_name
             FROM order_items oi
             JOIN menu_items mi ON mi.id = oi.menu_item_id
             WHERE oi.order_id = ?"
        );
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows;
    }


    public function getHistoryForAgent($agent_user_id)
    {
        $stmt = $this->conn->prepare(
            "SELECT da.id AS assignment_id, da.assigned_at, da.delivered_at,
                    TIMESTAMPDIFF(MINUTE, da.assigned_at, da.delivered_at) AS delivery_minutes,
                    o.id AS order_id, o.delivery_address, o.delivery_fee,
                    r.name AS restaurant_name,
                    u.name AS customer_name
             FROM delivery_assignments da
             JOIN orders o ON o.id = da.order_id
             JOIN restaurants r ON r.id = o.restaurant_id
             JOIN users u ON u.id = o.customer_id
             WHERE da.agent_id = ? AND da.status = 'delivered'
             ORDER BY da.delivered_at DESC"
        );
        $stmt->bind_param("i", $agent_user_id);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows;
    }

 
    public function hasNewAvailable()
    {
        $stmt = $this->conn->prepare(
            "SELECT COUNT(*) AS cnt FROM orders
             WHERE status = 'ready' AND agent_id IS NULL"
        );
        $stmt->execute();
        $cnt = (int)$stmt->get_result()->fetch_assoc()["cnt"];
        $stmt->close();
        return $cnt > 0;
    }


    public function getLatestAvailable()
    {
        $stmt = $this->conn->prepare(
            "SELECT o.id AS order_id, r.name AS restaurant_name,
                    o.delivery_address, o.total_amount
             FROM orders o
             JOIN restaurants r ON r.id = o.restaurant_id
             WHERE o.status = 'ready' AND o.agent_id IS NULL
             ORDER BY o.created_at DESC
             LIMIT 1"
        );
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row;
    }
}
?>
