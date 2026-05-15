<?php
/**
 * ROLE 2 — RESTAURANT MANAGER: DiscountController
 * PDF features:
 * - Create limited-time discount offers: discount %, validity dates
 * - Activate or deactivate discounts
 * - View discount campaigns and performance (orders that used the discount)
 */

require_once dirname(__DIR__, 3) . '/includes/bootstrap.php';
requireRole(['manager']);

// TODO: index(), create(), toggleActive(), performance()
