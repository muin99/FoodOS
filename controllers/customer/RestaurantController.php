<?php
/**
 * ROLE 1 — CUSTOMER: RestaurantController
 * PDF features:
 * - Browse all approved restaurants
 * - Search by name, cuisine type, or city
 * - Filter by rating or cuisine
 * - View restaurant detail: logo, description, cuisine, opening hours, average rating, full menu by category
 * - Browse menu items: price, description, image
 */

require_once dirname(__DIR__, 3) . '/includes/bootstrap.php';
requireRole(['customer']);

// TODO: browse(), search(), detail($restaurantId)
