-- FoodOS shared database schema
-- Target: MySQL/MariaDB (XAMPP)

CREATE DATABASE IF NOT EXISTS foodos_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE foodos_db;

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS delivery_assignments;
DROP TABLE IF EXISTS order_items;
DROP TABLE IF EXISTS reviews;
DROP TABLE IF EXISTS saved_restaurants;
DROP TABLE IF EXISTS delivery_addresses;
DROP TABLE IF EXISTS complaints;
DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS discounts;
DROP TABLE IF EXISTS menu_items;
DROP TABLE IF EXISTS menu_categories;
DROP TABLE IF EXISTS delivery_agents;
DROP TABLE IF EXISTS restaurants;
DROP TABLE IF EXISTS platform_settings;
DROP TABLE IF EXISTS users;

SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE users (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(120) NOT NULL,
  email VARCHAR(191) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  phone VARCHAR(30) NULL,
  role ENUM('customer', 'manager', 'agent', 'admin') NOT NULL,
  profile_pic VARCHAR(255) NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_users_email (email),
  KEY idx_users_role (role),
  KEY idx_users_is_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE restaurants (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  manager_id INT UNSIGNED NOT NULL,
  name VARCHAR(160) NOT NULL,
  description TEXT NULL,
  cuisine_type VARCHAR(100) NULL,
  address VARCHAR(255) NOT NULL,
  city VARCHAR(100) NOT NULL,
  logo_path VARCHAR(255) NULL,
  opening_hours VARCHAR(160) NULL,
  delivery_radius_km DECIMAL(5,2) NOT NULL DEFAULT 5.00,
  is_open TINYINT(1) NOT NULL DEFAULT 0,
  is_approved TINYINT(1) NOT NULL DEFAULT 0,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_restaurants_manager (manager_id),
  KEY idx_restaurants_city (city),
  KEY idx_restaurants_is_open (is_open),
  KEY idx_restaurants_is_approved (is_approved),
  CONSTRAINT fk_restaurants_manager
    FOREIGN KEY (manager_id)
    REFERENCES users (id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE menu_categories (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  restaurant_id INT UNSIGNED NOT NULL,
  name VARCHAR(120) NOT NULL,
  display_order INT NOT NULL DEFAULT 0,
  PRIMARY KEY (id),
  UNIQUE KEY uq_menu_categories_restaurant_name (restaurant_id, name),
  KEY idx_menu_categories_restaurant_order (restaurant_id, display_order),
  CONSTRAINT fk_menu_categories_restaurant
    FOREIGN KEY (restaurant_id)
    REFERENCES restaurants (id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE menu_items (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  restaurant_id INT UNSIGNED NOT NULL,
  category_id INT UNSIGNED NOT NULL,
  name VARCHAR(160) NOT NULL,
  description TEXT NULL,
  price DECIMAL(10,2) NOT NULL,
  image_path VARCHAR(255) NULL,
  is_available TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_menu_items_restaurant (restaurant_id),
  KEY idx_menu_items_category (category_id),
  KEY idx_menu_items_is_available (is_available),
  CONSTRAINT fk_menu_items_restaurant
    FOREIGN KEY (restaurant_id)
    REFERENCES restaurants (id)
    ON UPDATE CASCADE
    ON DELETE CASCADE,
  CONSTRAINT fk_menu_items_category
    FOREIGN KEY (category_id)
    REFERENCES menu_categories (id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE discounts (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  menu_item_id INT UNSIGNED NOT NULL,
  restaurant_id INT UNSIGNED NOT NULL,
  discount_pct DECIMAL(5,2) NOT NULL,
  valid_from DATETIME NOT NULL,
  valid_until DATETIME NOT NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (id),
  KEY idx_discounts_menu_item (menu_item_id),
  KEY idx_discounts_restaurant (restaurant_id),
  KEY idx_discounts_active_window (is_active, valid_from, valid_until),
  CONSTRAINT fk_discounts_menu_item
    FOREIGN KEY (menu_item_id)
    REFERENCES menu_items (id)
    ON UPDATE CASCADE
    ON DELETE CASCADE,
  CONSTRAINT fk_discounts_restaurant
    FOREIGN KEY (restaurant_id)
    REFERENCES restaurants (id)
    ON UPDATE CASCADE
    ON DELETE CASCADE,
  CONSTRAINT chk_discounts_pct CHECK (discount_pct >= 0 AND discount_pct <= 100),
  CONSTRAINT chk_discounts_window CHECK (valid_until > valid_from)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE delivery_agents (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id INT UNSIGNED NOT NULL,
  vehicle_type VARCHAR(60) NOT NULL,
  is_online TINYINT(1) NOT NULL DEFAULT 0,
  current_location_text VARCHAR(255) NULL,
  total_earnings DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  is_approved TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (id),
  UNIQUE KEY uq_delivery_agents_user_id (user_id),
  KEY idx_delivery_agents_is_online (is_online),
  KEY idx_delivery_agents_is_approved (is_approved),
  CONSTRAINT fk_delivery_agents_user
    FOREIGN KEY (user_id)
    REFERENCES users (id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE orders (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  customer_id INT UNSIGNED NOT NULL,
  restaurant_id INT UNSIGNED NOT NULL,
  agent_id INT UNSIGNED NULL,
  delivery_address VARCHAR(255) NOT NULL,
  payment_method VARCHAR(50) NOT NULL,
  subtotal DECIMAL(10,2) NOT NULL,
  delivery_fee DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  total_amount DECIMAL(10,2) NOT NULL,
  status ENUM('pending', 'accepted', 'preparing', 'ready', 'picked_up', 'delivered', 'cancelled') NOT NULL DEFAULT 'pending',
  estimated_delivery_minutes INT UNSIGNED NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_orders_customer (customer_id),
  KEY idx_orders_restaurant (restaurant_id),
  KEY idx_orders_agent (agent_id),
  KEY idx_orders_status_created (status, created_at),
  CONSTRAINT fk_orders_customer
    FOREIGN KEY (customer_id)
    REFERENCES users (id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT,
  CONSTRAINT fk_orders_restaurant
    FOREIGN KEY (restaurant_id)
    REFERENCES restaurants (id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT,
  CONSTRAINT fk_orders_agent
    FOREIGN KEY (agent_id)
    REFERENCES delivery_agents (id)
    ON UPDATE CASCADE
    ON DELETE SET NULL,
  CONSTRAINT chk_orders_totals CHECK (
    subtotal >= 0 AND delivery_fee >= 0 AND total_amount >= 0
  )
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE order_items (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  order_id INT UNSIGNED NOT NULL,
  menu_item_id INT UNSIGNED NOT NULL,
  quantity INT UNSIGNED NOT NULL,
  unit_price DECIMAL(10,2) NOT NULL,
  PRIMARY KEY (id),
  KEY idx_order_items_order (order_id),
  KEY idx_order_items_menu_item (menu_item_id),
  CONSTRAINT fk_order_items_order
    FOREIGN KEY (order_id)
    REFERENCES orders (id)
    ON UPDATE CASCADE
    ON DELETE CASCADE,
  CONSTRAINT fk_order_items_menu_item
    FOREIGN KEY (menu_item_id)
    REFERENCES menu_items (id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT,
  CONSTRAINT chk_order_items_qty_price CHECK (quantity > 0 AND unit_price >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE delivery_assignments (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  order_id INT UNSIGNED NOT NULL,
  agent_id INT UNSIGNED NOT NULL,
  assigned_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  picked_up_at DATETIME NULL,
  delivered_at DATETIME NULL,
  status ENUM('assigned', 'picked_up', 'delivered', 'cancelled') NOT NULL DEFAULT 'assigned',
  PRIMARY KEY (id),
  UNIQUE KEY uq_delivery_assignments_order (order_id),
  KEY idx_delivery_assignments_agent_status (agent_id, status),
  CONSTRAINT fk_delivery_assignments_order
    FOREIGN KEY (order_id)
    REFERENCES orders (id)
    ON UPDATE CASCADE
    ON DELETE CASCADE,
  CONSTRAINT fk_delivery_assignments_agent
    FOREIGN KEY (agent_id)
    REFERENCES delivery_agents (id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT,
  CONSTRAINT chk_delivery_assignment_times CHECK (
    (picked_up_at IS NULL OR picked_up_at >= assigned_at) AND
    (delivered_at IS NULL OR delivered_at >= assigned_at)
  )
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE reviews (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  order_id INT UNSIGNED NOT NULL,
  customer_id INT UNSIGNED NOT NULL,
  restaurant_id INT UNSIGNED NOT NULL,
  rating TINYINT UNSIGNED NOT NULL,
  comment TEXT NULL,
  manager_reply TEXT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_reviews_order (order_id),
  KEY idx_reviews_customer (customer_id),
  KEY idx_reviews_restaurant (restaurant_id),
  CONSTRAINT fk_reviews_order
    FOREIGN KEY (order_id)
    REFERENCES orders (id)
    ON UPDATE CASCADE
    ON DELETE CASCADE,
  CONSTRAINT fk_reviews_customer
    FOREIGN KEY (customer_id)
    REFERENCES users (id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT,
  CONSTRAINT fk_reviews_restaurant
    FOREIGN KEY (restaurant_id)
    REFERENCES restaurants (id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT,
  CONSTRAINT chk_reviews_rating CHECK (rating >= 1 AND rating <= 5)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE saved_restaurants (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  customer_id INT UNSIGNED NOT NULL,
  restaurant_id INT UNSIGNED NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_saved_restaurants_customer_restaurant (customer_id, restaurant_id),
  KEY idx_saved_restaurants_restaurant (restaurant_id),
  CONSTRAINT fk_saved_restaurants_customer
    FOREIGN KEY (customer_id)
    REFERENCES users (id)
    ON UPDATE CASCADE
    ON DELETE CASCADE,
  CONSTRAINT fk_saved_restaurants_restaurant
    FOREIGN KEY (restaurant_id)
    REFERENCES restaurants (id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE delivery_addresses (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  customer_id INT UNSIGNED NOT NULL,
  label VARCHAR(80) NOT NULL,
  address_line VARCHAR(255) NOT NULL,
  city VARCHAR(100) NOT NULL,
  is_default TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (id),
  KEY idx_delivery_addresses_customer (customer_id),
  CONSTRAINT fk_delivery_addresses_customer
    FOREIGN KEY (customer_id)
    REFERENCES users (id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE complaints (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  submitter_id INT UNSIGNED NOT NULL,
  subject VARCHAR(180) NOT NULL,
  description TEXT NOT NULL,
  status ENUM('open', 'resolved') NOT NULL DEFAULT 'open',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_complaints_submitter (submitter_id),
  KEY idx_complaints_status_created (status, created_at),
  CONSTRAINT fk_complaints_submitter
    FOREIGN KEY (submitter_id)
    REFERENCES users (id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE platform_settings (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  setting_key VARCHAR(120) NOT NULL,
  setting_value TEXT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uq_platform_settings_key (setting_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
