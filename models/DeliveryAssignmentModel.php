<?php
/**
 * MODEL: delivery_assignments
 * Table: id, order_id, agent_id, assigned_at, picked_up_at, delivered_at, status
 * Agent: view available (ready + unassigned), accept, decline, status sequence.
 * Customer tracking page reflects agent updates (via AJAX).
 */

class DeliveryAssignmentModel
{
    // TODO: getAvailable, assign, decline, updateStatus, getActiveForAgent
}
