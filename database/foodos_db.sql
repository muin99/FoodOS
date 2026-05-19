-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 19, 2026 at 11:41 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `foodos_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `complaints`
--

CREATE TABLE `complaints` (
  `id` int(10) UNSIGNED NOT NULL,
  `submitter_id` int(10) UNSIGNED NOT NULL,
  `subject` varchar(180) NOT NULL,
  `description` text NOT NULL,
  `status` enum('open','resolved') NOT NULL DEFAULT 'open',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `delivery_addresses`
--

CREATE TABLE `delivery_addresses` (
  `id` int(10) UNSIGNED NOT NULL,
  `customer_id` int(10) UNSIGNED NOT NULL,
  `label` varchar(80) NOT NULL,
  `address_line` varchar(255) NOT NULL,
  `city` varchar(100) NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `delivery_agents`
--

CREATE TABLE `delivery_agents` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `vehicle_type` varchar(60) NOT NULL,
  `is_online` tinyint(1) NOT NULL DEFAULT 0,
  `current_location_text` varchar(255) DEFAULT NULL,
  `total_earnings` decimal(12,2) NOT NULL DEFAULT 0.00,
  `is_approved` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `delivery_assignments`
--

CREATE TABLE `delivery_assignments` (
  `id` int(10) UNSIGNED NOT NULL,
  `order_id` int(10) UNSIGNED NOT NULL,
  `agent_id` int(10) UNSIGNED NOT NULL,
  `assigned_at` datetime NOT NULL DEFAULT current_timestamp(),
  `picked_up_at` datetime DEFAULT NULL,
  `delivered_at` datetime DEFAULT NULL,
  `status` enum('assigned','picked_up','delivered','cancelled') NOT NULL DEFAULT 'assigned'
) ;

-- --------------------------------------------------------

--
-- Table structure for table `discounts`
--

CREATE TABLE `discounts` (
  `id` int(10) UNSIGNED NOT NULL,
  `menu_item_id` int(10) UNSIGNED NOT NULL,
  `restaurant_id` int(10) UNSIGNED NOT NULL,
  `discount_pct` decimal(5,2) NOT NULL,
  `valid_from` datetime NOT NULL,
  `valid_until` datetime NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ;

-- --------------------------------------------------------

--
-- Table structure for table `menu_categories`
--

CREATE TABLE `menu_categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `restaurant_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(120) NOT NULL,
  `display_order` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `menu_categories`
--

INSERT INTO `menu_categories` (`id`, `restaurant_id`, `name`, `display_order`) VALUES
(1, 7, 'Burgers', 1),
(2, 7, 'Pizza', 2),
(3, 7, 'Drinks', 3),
(4, 7, 'Sides', 4),
(5, 7, 'Desserts', 5);

-- --------------------------------------------------------

--
-- Table structure for table `menu_items`
--

CREATE TABLE `menu_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `restaurant_id` int(10) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(160) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `menu_items`
--

INSERT INTO `menu_items` (`id`, `restaurant_id`, `category_id`, `name`, `description`, `price`, `image_path`, `is_available`, `created_at`) VALUES
(17, 7, 1, 'Classic Burger', 'Juicy beef burger with lettuce, tomato, and cheese', 300.00, 'images/burger1.jpg', 1, '2026-05-18 20:14:48'),
(18, 7, 1, 'Cheese Burger', 'Beef patty with double cheese and onions', 380.00, 'images/burger2.jpg', 1, '2026-05-18 20:14:48'),
(19, 7, 1, 'Chicken Burger', 'Grilled chicken breast with mayo and lettuce', 370.00, 'images/burger3.jpg', 1, '2026-05-18 20:14:48'),
(20, 7, 2, 'Pepperoni Pizza', '12-inch pizza with pepperoni and mozzarella', 800.00, 'images/pizza1.jpg', 1, '2026-05-18 20:14:48'),
(21, 7, 2, 'Margherita Pizza', 'Tomato, mozzarella, and basil', 700.00, 'images/pizza2.jpg', 1, '2026-05-18 20:14:48'),
(22, 7, 2, 'BBQ Chicken Pizza', 'Grilled chicken with BBQ sauce and cheese', 850.00, 'images/pizza3.jpg', 1, '2026-05-18 20:14:48'),
(23, 7, 3, 'Coca Cola', '330ml chilled soft drink', 70.00, 'images/coke.jpg', 1, '2026-05-18 20:14:48'),
(24, 7, 3, 'Pepsi', '330ml chilled soft drink', 50.00, 'images/pepsi.jpg', 1, '2026-05-18 20:14:48'),
(25, 7, 4, 'French Fries', 'Crispy golden fries', 120.00, 'images/fries.jpg', 1, '2026-05-18 20:14:48'),
(26, 7, 5, 'Chocolate Cake', 'Rich chocolate layered cake', 250.00, 'images/choc_cake.jpg', 1, '2026-05-18 20:14:48'),
(27, 7, 1, 'Bacon Burger', 'Beef patty with crispy bacon, lettuce, tomato, and cheese', 450.00, 'images/burger4.jpg', 1, '2026-05-18 21:30:30'),
(28, 7, 1, 'Veggie Burger', 'Grilled veggie patty with fresh lettuce and tomato', 300.00, 'images/burger5.jpg', 1, '2026-05-18 21:30:30'),
(29, 7, 1, 'Double Cheese Burger', 'Two beef patties with double cheese', 500.00, 'images/burger6.jpg', 1, '2026-05-18 21:30:30'),
(30, 7, 2, 'Hawaiian Pizza', 'Pizza with ham and pineapple', 750.00, 'images/pizza4.jpg', 1, '2026-05-18 21:30:30'),
(31, 7, 2, 'Four Cheese Pizza', 'Mozzarella, cheddar, parmesan, and gouda', 900.00, 'images/pizza5.jpg', 1, '2026-05-18 21:30:30'),
(32, 7, 2, 'Veggie Delight Pizza', 'Tomatoes, capsicum, onions, and olives', 700.00, 'images/pizza6.jpg', 1, '2026-05-18 21:30:30'),
(33, 7, 3, 'Sprite', '330ml chilled soft drink', 50.00, 'images/sprite.jpg', 1, '2026-05-18 21:30:30'),
(34, 7, 3, 'Fanta', '330ml chilled orange soda', 50.00, 'images/fanta.jpg', 1, '2026-05-18 21:30:30'),
(36, 7, 4, 'Masala Fries', 'Spicy masala coated fries', 150.00, 'images/masala_fries.jpg', 1, '2026-05-18 21:30:30'),
(37, 7, 4, 'Cheese Fries', 'Fries topped with melted cheese', 180.00, 'images/cheese_fries.jpg', 1, '2026-05-18 21:30:30'),
(38, 7, 4, 'Onion Rings', 'Crispy deep-fried onion rings', 200.00, 'images/onion_rings.jpg', 1, '2026-05-18 21:30:30'),
(39, 7, 5, 'Brownie', 'Chocolate brownie with ice cream', 220.00, 'images/brownie.jpg', 1, '2026-05-18 21:30:30'),
(40, 7, 5, 'Ice Cream Sundae', 'Vanilla ice cream with chocolate syrup and nuts', 250.00, 'images/sundae.jpg', 1, '2026-05-18 21:30:30'),
(41, 7, 5, 'Cheesecake', 'Classic New York style cheesecake', 300.00, 'images/cheesecake.jpg', 1, '2026-05-18 21:30:30');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(10) UNSIGNED NOT NULL,
  `customer_id` int(10) UNSIGNED NOT NULL,
  `restaurant_id` int(10) UNSIGNED NOT NULL,
  `agent_id` int(10) UNSIGNED DEFAULT NULL,
  `delivery_address` varchar(255) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `delivery_fee` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','accepted','preparing','ready','picked_up','delivered','cancelled') NOT NULL DEFAULT 'pending',
  `estimated_delivery_minutes` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_id`, `restaurant_id`, `agent_id`, `delivery_address`, `payment_method`, `subtotal`, `delivery_fee`, `total_amount`, `status`, `estimated_delivery_minutes`, `created_at`) VALUES
(11, 1, 7, NULL, 'Mohammadpur, Dhaka', 'Cash', 320.00, 50.00, 370.00, 'accepted', 30, '2026-05-18 03:07:28'),
(12, 2, 7, NULL, 'Farmgate, Dhaka', 'Card', 560.00, 60.00, 620.00, 'accepted', 40, '2026-05-18 03:07:28'),
(13, 3, 7, NULL, 'Gulshan, Dhaka', 'Cash', 780.00, 70.00, 850.00, 'accepted', 35, '2026-05-18 03:07:28'),
(14, 4, 7, NULL, 'Badda, Dhaka', 'Cash', 450.00, 50.00, 500.00, 'preparing', 25, '2026-05-18 03:07:28'),
(15, 5, 7, NULL, 'Uttara, Dhaka', 'Card', 690.00, 60.00, 750.00, 'ready', 20, '2026-05-18 03:07:28'),
(16, 1, 7, NULL, 'Banani, Dhaka', 'Cash', 900.00, 70.00, 970.00, 'delivered', 45, '2026-05-18 03:07:28'),
(17, 2, 7, NULL, 'Mirpur 10, Dhaka', 'Card', 280.00, 50.00, 330.00, 'cancelled', 15, '2026-05-18 03:07:28'),
(18, 1, 7, NULL, 'Dhanmondi, Dhaka', 'Cash', 420.00, 50.00, 470.00, 'delivered', 30, '2026-05-19 01:06:44'),
(19, 1, 7, NULL, 'Dhanmondi, Dhaka', 'Cash', 420.00, 50.00, 470.00, 'delivered', 30, '2026-05-19 01:06:57'),
(20, 4, 7, NULL, 'Uttara, Dhaka', 'Card', 560.00, 60.00, 620.00, 'delivered', 25, '2026-05-19 01:10:40'),
(21, 1, 7, NULL, 'Dhanmondi, Dhaka', 'Cash', 420.00, 50.00, 470.00, 'delivered', 30, '2026-05-19 01:12:11'),
(22, 1, 7, NULL, 'Dhanmondi, Dhaka', 'Cash', 420.00, 50.00, 470.00, 'delivered', 30, '2026-05-19 09:30:22');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `order_id` int(10) UNSIGNED NOT NULL,
  `menu_item_id` int(10) UNSIGNED NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL,
  `unit_price` decimal(10,2) NOT NULL
) ;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `menu_item_id`, `quantity`, `unit_price`) VALUES
(11, 11, 17, 2, 350.00),
(12, 11, 18, 1, 380.00),
(13, 12, 20, 1, 800.00),
(14, 13, 17, 2, 350.00),
(15, 13, 21, 1, 700.00),
(16, 14, 18, 1, 380.00),
(17, 15, 20, 2, 800.00),
(18, 16, 17, 3, 350.00),
(19, 18, 18, 2, 380.00),
(20, 20, 21, 1, 700.00);

-- --------------------------------------------------------

--
-- Table structure for table `platform_settings`
--

CREATE TABLE `platform_settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `setting_key` varchar(120) NOT NULL,
  `setting_value` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `restaurants`
--

CREATE TABLE `restaurants` (
  `id` int(10) UNSIGNED NOT NULL,
  `manager_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(160) NOT NULL,
  `description` text DEFAULT NULL,
  `cuisine_type` varchar(100) DEFAULT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(100) NOT NULL,
  `logo_path` varchar(255) DEFAULT NULL,
  `opening_hours` varchar(160) DEFAULT NULL,
  `delivery_radius_km` decimal(5,2) NOT NULL DEFAULT 5.00,
  `is_open` tinyint(1) NOT NULL DEFAULT 0,
  `is_approved` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `restaurants`
--

INSERT INTO `restaurants` (`id`, `manager_id`, `name`, `description`, `cuisine_type`, `address`, `city`, `logo_path`, `opening_hours`, `delivery_radius_km`, `is_open`, `is_approved`, `created_at`) VALUES
(7, 1, 'Khana\'s Kitchen', 'Fast food restaurant', 'Fast Food', 'Gulshan, Dhaka', 'Dhaka', NULL, '10:00 AM - 11:00 PM', 5.00, 1, 1, '2026-05-18 03:06:38');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(10) UNSIGNED NOT NULL,
  `order_id` int(10) UNSIGNED NOT NULL,
  `customer_id` int(10) UNSIGNED NOT NULL,
  `restaurant_id` int(10) UNSIGNED NOT NULL,
  `rating` tinyint(3) UNSIGNED NOT NULL,
  `comment` text DEFAULT NULL,
  `manager_reply` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `order_id`, `customer_id`, `restaurant_id`, `rating`, `comment`, `manager_reply`, `created_at`) VALUES
(1, 11, 1, 7, 5, 'Food was fresh and delivered on time.', NULL, '2026-05-18 04:15:00'),
(2, 12, 2, 7, 4, 'Good taste but delivery took a little longer.', NULL, '2026-05-18 05:30:00'),
(3, 13, 3, 7, 5, 'Excellent food quality. Loved it.', 'Thank you for your feedback.', '2026-05-18 06:45:00'),
(4, 14, 4, 7, 3, 'Food was okay but could be warmer.', NULL, '2026-05-17 20:20:00'),
(5, 15, 5, 7, 4, 'Nice packaging and good portion size.', NULL, '2026-05-17 21:50:00'),
(6, 16, 1, 7, 5, 'Amazing burger and fries combo.', 'We appreciate your support.', '2026-05-17 23:10:00'),
(7, 17, 2, 7, 2, 'Delivery was very late.', NULL, '2026-05-18 00:40:00'),
(8, 18, 3, 7, 5, 'Pizza was delicious and cheesy.', NULL, '2026-05-18 01:15:00');

-- --------------------------------------------------------

--
-- Table structure for table `saved_restaurants`
--

CREATE TABLE `saved_restaurants` (
  `id` int(10) UNSIGNED NOT NULL,
  `customer_id` int(10) UNSIGNED NOT NULL,
  `restaurant_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(120) NOT NULL,
  `email` varchar(191) NOT NULL,
  `password_hash` varchar(255) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `role` enum('customer','manager','agent','admin') NOT NULL,
  `profile_pic` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password_hash`, `phone`, `role`, `profile_pic`, `is_active`, `created_at`) VALUES
(1, 'Rahim Ahmed', 'rahim@gmail.com', '123456', '01712-345678', 'customer', NULL, 1, '2026-05-18 03:02:46'),
(2, 'Tanvir Hasan', 'tanvir@gmail.com', '123456', '01755-112233', 'customer', NULL, 1, '2026-05-18 03:02:46'),
(3, 'Sakib Hasan', 'sakib@gmail.com', '123456', '01987-654321', 'customer', NULL, 1, '2026-05-18 03:02:46'),
(4, 'Farhana Islam', 'farhana@gmail.com', '123456', '01645-789123', 'customer', NULL, 1, '2026-05-18 03:02:46'),
(5, 'Nusrat Jahan', 'nusrat@gmail.com', '123456', '01823-456789', 'customer', NULL, 1, '2026-05-18 03:02:46'),
(7, 'john Cena', 'manager@gmail.com', '$2y$10$b1bjuLJzyRR.LR37OO8U1ullyTVoyiOD87EGaYK.daohZSIFfGbEm', '007', 'manager', NULL, 1, '2026-05-18 12:42:04');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `complaints`
--
ALTER TABLE `complaints`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_complaints_submitter` (`submitter_id`),
  ADD KEY `idx_complaints_status_created` (`status`,`created_at`);

--
-- Indexes for table `delivery_addresses`
--
ALTER TABLE `delivery_addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_delivery_addresses_customer` (`customer_id`);

--
-- Indexes for table `delivery_agents`
--
ALTER TABLE `delivery_agents`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_delivery_agents_user_id` (`user_id`),
  ADD KEY `idx_delivery_agents_is_online` (`is_online`),
  ADD KEY `idx_delivery_agents_is_approved` (`is_approved`);

--
-- Indexes for table `delivery_assignments`
--
ALTER TABLE `delivery_assignments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_delivery_assignments_order` (`order_id`),
  ADD KEY `idx_delivery_assignments_agent_status` (`agent_id`,`status`);

--
-- Indexes for table `discounts`
--
ALTER TABLE `discounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_discounts_menu_item` (`menu_item_id`),
  ADD KEY `idx_discounts_restaurant` (`restaurant_id`),
  ADD KEY `idx_discounts_active_window` (`is_active`,`valid_from`,`valid_until`);

--
-- Indexes for table `menu_categories`
--
ALTER TABLE `menu_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_menu_categories_restaurant_name` (`restaurant_id`,`name`),
  ADD KEY `idx_menu_categories_restaurant_order` (`restaurant_id`,`display_order`);

--
-- Indexes for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_menu_items_restaurant` (`restaurant_id`),
  ADD KEY `idx_menu_items_category` (`category_id`),
  ADD KEY `idx_menu_items_is_available` (`is_available`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_orders_customer` (`customer_id`),
  ADD KEY `idx_orders_restaurant` (`restaurant_id`),
  ADD KEY `idx_orders_agent` (`agent_id`),
  ADD KEY `idx_orders_status_created` (`status`,`created_at`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_order_items_order` (`order_id`),
  ADD KEY `idx_order_items_menu_item` (`menu_item_id`);

--
-- Indexes for table `platform_settings`
--
ALTER TABLE `platform_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_platform_settings_key` (`setting_key`);

--
-- Indexes for table `restaurants`
--
ALTER TABLE `restaurants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_restaurants_manager` (`manager_id`),
  ADD KEY `idx_restaurants_city` (`city`),
  ADD KEY `idx_restaurants_is_open` (`is_open`),
  ADD KEY `idx_restaurants_is_approved` (`is_approved`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_reviews_order` (`order_id`),
  ADD KEY `idx_reviews_customer` (`customer_id`),
  ADD KEY `idx_reviews_restaurant` (`restaurant_id`);

--
-- Indexes for table `saved_restaurants`
--
ALTER TABLE `saved_restaurants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_saved_restaurants_customer_restaurant` (`customer_id`,`restaurant_id`),
  ADD KEY `idx_saved_restaurants_restaurant` (`restaurant_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_users_email` (`email`),
  ADD KEY `idx_users_role` (`role`),
  ADD KEY `idx_users_is_active` (`is_active`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `complaints`
--
ALTER TABLE `complaints`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `delivery_addresses`
--
ALTER TABLE `delivery_addresses`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `delivery_agents`
--
ALTER TABLE `delivery_agents`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `delivery_assignments`
--
ALTER TABLE `delivery_assignments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `discounts`
--
ALTER TABLE `discounts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `menu_categories`
--
ALTER TABLE `menu_categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `menu_items`
--
ALTER TABLE `menu_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `platform_settings`
--
ALTER TABLE `platform_settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `restaurants`
--
ALTER TABLE `restaurants`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `saved_restaurants`
--
ALTER TABLE `saved_restaurants`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `complaints`
--
ALTER TABLE `complaints`
  ADD CONSTRAINT `fk_complaints_submitter` FOREIGN KEY (`submitter_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `delivery_addresses`
--
ALTER TABLE `delivery_addresses`
  ADD CONSTRAINT `fk_delivery_addresses_customer` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `delivery_agents`
--
ALTER TABLE `delivery_agents`
  ADD CONSTRAINT `fk_delivery_agents_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `delivery_assignments`
--
ALTER TABLE `delivery_assignments`
  ADD CONSTRAINT `fk_delivery_assignments_agent` FOREIGN KEY (`agent_id`) REFERENCES `delivery_agents` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_delivery_assignments_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `discounts`
--
ALTER TABLE `discounts`
  ADD CONSTRAINT `fk_discounts_menu_item` FOREIGN KEY (`menu_item_id`) REFERENCES `menu_items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_discounts_restaurant` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `menu_categories`
--
ALTER TABLE `menu_categories`
  ADD CONSTRAINT `fk_menu_categories_restaurant` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD CONSTRAINT `fk_menu_items_category` FOREIGN KEY (`category_id`) REFERENCES `menu_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_menu_items_restaurant` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_agent` FOREIGN KEY (`agent_id`) REFERENCES `delivery_agents` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_orders_customer` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_orders_restaurant` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `fk_order_items_menu_item` FOREIGN KEY (`menu_item_id`) REFERENCES `menu_items` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_order_items_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `restaurants`
--
ALTER TABLE `restaurants`
  ADD CONSTRAINT `fk_restaurants_manager` FOREIGN KEY (`manager_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `fk_reviews_customer` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_reviews_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_reviews_restaurant` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `saved_restaurants`
--
ALTER TABLE `saved_restaurants`
  ADD CONSTRAINT `fk_saved_restaurants_customer` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_saved_restaurants_restaurant` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
