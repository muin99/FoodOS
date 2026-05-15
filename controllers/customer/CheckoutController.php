<?php
/**
 * ROLE 1 — CUSTOMER: CheckoutController
 * PDF features:
 * - Checkout: confirm delivery address (saved or new), payment method (Cash / Card), order summary
 * - Place order; view confirmation page with order ID and estimated delivery time
 */

require_once dirname(__DIR__, 3) . '/includes/bootstrap.php';
requireRole(['customer']);

// TODO: checkoutForm(), placeOrder(), confirmation($orderId)
