<?php
/**
 * MODEL: orders
 * Table: id, customer_id, restaurant_id, agent_id, delivery_address, payment_method,
 *        subtotal, delivery_fee, total_amount, status, estimated_delivery_minutes, created_at
 * Status values: pending | accepted | preparing | ready | picked_up | delivered | cancelled
 * Customer: place order, history, cancel pending, track status.
 * Manager: accept/reject, lifecycle updates (Preparing → Ready for Pickup).
 * Agent: assignments for ready orders.
 * Admin: platform-wide orders with filters.
 */

class OrderModel
{
    // TODO: create, updateStatus, getByCustomer, getByRestaurant, getActive, cancelPending, etc.
}
