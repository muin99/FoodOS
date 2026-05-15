<?php
/**
 * ROLE 1 — CUSTOMER: CartController
 * PDF features:
 * - Session-based cart: add items, update quantities, remove items, running total
 * - Apply active discount offers automatically when adding discounted items to cart
 */

require_once dirname(__DIR__, 3) . '/includes/bootstrap.php';
requireRole(['customer']);

// TODO: viewCart(), addItem(), updateQty(), removeItem() — use $_SESSION['cart']
