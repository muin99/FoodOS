<?php
/**
 * MODEL: delivery_agents
 * Table: id, user_id, vehicle_type, is_online, current_location_text,
 *        total_earnings, is_approved
 * Agent: register, profile, online/offline toggle, earnings.
 * Admin: approve/reject/deactivate agents.
 */

class DeliveryAgentModel
{
    function registration(
$conn,
$name,
$phone,
$vehicle,
$password
)

{

$sql=

"INSERT INTO delivery_agents
(name,phone,vehicle_type,password)

VALUES
('$name','$phone','$vehicle','$password')";

return $conn->query($sql);

}



function login(
$conn,
$phone,
$password
)

{

$sql=

"SELECT * FROM delivery_agents

WHERE phone='$phone'

AND password='$password'";

return $conn->query($sql);

}

}

?>
