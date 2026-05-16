<?php
/**
 * MODEL: delivery_assignments
 * Table: id, order_id, agent_id, assigned_at, picked_up_at, delivered_at, status
 * Agent: view available (ready + unassigned), accept, decline, status sequence.
 * Customer tracking page reflects agent updates (via AJAX).
 */

class DeliveryAssignmentModel
{
    function createAssignment(
$conn,
$order_id,
$agent_id,
$status
)

{

$sql=

"INSERT INTO delivery_assignments
(order_id,agent_id,status)

VALUES
('$order_id','$agent_id','$status')";

return $conn->query($sql);

}



function getAssignments(
$conn,
$agent_id
)

{

$sql=

"SELECT * FROM delivery_assignments

WHERE agent_id='$agent_id'";

return $conn->query($sql);

}



function updateAssignmentStatus(
$conn,
$id,
$status
)

{

$sql=

"UPDATE delivery_assignments

SET status='$status'

WHERE id='$id'";

return $conn->query($sql);

}
}

?>
