-- FoodOS demo data
-- Run this after dirCommon/schema.sql.
-- Demo login password for every account below: 123456

USE foodos_db;

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DELETE FROM platform_settings;
DELETE FROM complaints;
DELETE FROM delivery_addresses;
DELETE FROM saved_restaurants;
DELETE FROM reviews;
DELETE FROM delivery_assignments;
DELETE FROM order_items;
DELETE FROM orders;
DELETE FROM discounts;
DELETE FROM menu_items;
DELETE FROM menu_categories;
DELETE FROM delivery_agents;
DELETE FROM restaurants;
DELETE FROM users;

ALTER TABLE users AUTO_INCREMENT = 1;
ALTER TABLE restaurants AUTO_INCREMENT = 1;
ALTER TABLE menu_categories AUTO_INCREMENT = 1;
ALTER TABLE menu_items AUTO_INCREMENT = 1;
ALTER TABLE discounts AUTO_INCREMENT = 1;
ALTER TABLE delivery_agents AUTO_INCREMENT = 1;
ALTER TABLE orders AUTO_INCREMENT = 1;
ALTER TABLE order_items AUTO_INCREMENT = 1;
ALTER TABLE delivery_assignments AUTO_INCREMENT = 1;
ALTER TABLE reviews AUTO_INCREMENT = 1;
ALTER TABLE saved_restaurants AUTO_INCREMENT = 1;
ALTER TABLE delivery_addresses AUTO_INCREMENT = 1;
ALTER TABLE complaints AUTO_INCREMENT = 1;
ALTER TABLE platform_settings AUTO_INCREMENT = 1;

SET FOREIGN_KEY_CHECKS = 1;

INSERT INTO users (id, name, email, password_hash, phone, role, is_active, created_at) VALUES
(1, 'Admin User', 'admin@bitebuddy.test', '$2y$10$POJLXnF1MqS28adSSJI6p.weOLw5y4nLeUWyboFczWJqBaEV5Ti6a', '01700-000001', 'admin', 1, '2026-05-01 09:00:00'),
(2, 'Rahim Manager', 'manager@bitebuddy.test', '$2y$10$POJLXnF1MqS28adSSJI6p.weOLw5y4nLeUWyboFczWJqBaEV5Ti6a', '01700-000002', 'manager', 1, '2026-05-01 09:05:00'),
(3, 'Nadia Manager', 'nadia.manager@bitebuddy.test', '$2y$10$POJLXnF1MqS28adSSJI6p.weOLw5y4nLeUWyboFczWJqBaEV5Ti6a', '01700-000003', 'manager', 1, '2026-05-01 09:10:00'),
(4, 'Ayesha Khan', 'ayesha@bitebuddy.test', '$2y$10$POJLXnF1MqS28adSSJI6p.weOLw5y4nLeUWyboFczWJqBaEV5Ti6a', '01700-000004', 'customer', 1, '2026-05-01 09:15:00'),
(5, 'Tanvir Hasan', 'tanvir@bitebuddy.test', '$2y$10$POJLXnF1MqS28adSSJI6p.weOLw5y4nLeUWyboFczWJqBaEV5Ti6a', '01700-000005', 'customer', 1, '2026-05-01 09:20:00'),
(6, 'Farhana Islam', 'farhana@bitebuddy.test', '$2y$10$POJLXnF1MqS28adSSJI6p.weOLw5y4nLeUWyboFczWJqBaEV5Ti6a', '01700-000006', 'customer', 1, '2026-05-01 09:25:00'),
(7, 'Sakib Ahmed', 'sakib@bitebuddy.test', '$2y$10$POJLXnF1MqS28adSSJI6p.weOLw5y4nLeUWyboFczWJqBaEV5Ti6a', '01700-000007', 'customer', 1, '2026-05-01 09:30:00'),
(8, 'Imran Rider', 'agent@bitebuddy.test', '$2y$10$POJLXnF1MqS28adSSJI6p.weOLw5y4nLeUWyboFczWJqBaEV5Ti6a', '01700-000008', 'agent', 1, '2026-05-01 09:35:00'),
(9, 'Mitu Rider', 'mitu.agent@bitebuddy.test', '$2y$10$POJLXnF1MqS28adSSJI6p.weOLw5y4nLeUWyboFczWJqBaEV5Ti6a', '01700-000009', 'agent', 1, '2026-05-01 09:40:00');

INSERT INTO restaurants (id, manager_id, name, description, cuisine_type, address, city, logo_path, opening_hours, delivery_radius_km, is_open, is_approved, created_at) VALUES
(1, 2, 'Khana Kitchen', 'Local favorites, burgers, rice bowls, and quick meals.', 'Fast Food', 'Gulshan Avenue, Dhaka', 'Dhaka', 'Customer/assets/images/burger-culture.svg', '10:00 AM - 11:00 PM', 6.50, 1, 1, '2026-05-02 10:00:00'),
(2, 3, 'Sakura Sushi', 'Fresh sushi sets, ramen, and Japanese comfort food.', 'Japanese', 'Banani Road 11, Dhaka', 'Dhaka', 'Customer/assets/images/sakura-sushi.svg', '12:00 PM - 10:30 PM', 5.00, 1, 1, '2026-05-02 10:30:00'),
(3, 2, 'Green Bowl', 'Healthy bowls, salads, smoothies, and wraps.', 'Healthy', 'Dhanmondi 27, Dhaka', 'Dhaka', 'Customer/assets/images/green-bowl.svg', '09:00 AM - 09:30 PM', 4.50, 0, 1, '2026-05-02 11:00:00');

INSERT INTO menu_categories (id, restaurant_id, name, display_order) VALUES
(1, 1, 'Burgers', 1),
(2, 1, 'Pizza', 2),
(3, 1, 'Drinks', 3),
(4, 2, 'Sushi', 1),
(5, 2, 'Ramen', 2),
(6, 2, 'Sides', 3),
(7, 3, 'Bowls', 1),
(8, 3, 'Salads', 2),
(9, 3, 'Smoothies', 3);

INSERT INTO menu_items (id, restaurant_id, category_id, name, description, price, image_path, is_available, created_at) VALUES
(1, 1, 1, 'Classic Burger', 'Beef patty, lettuce, tomato, and cheese.', 320.00, 'restaurantManager/assets/images/burger.png', 1, '2026-05-03 10:00:00'),
(2, 1, 1, 'Double Cheese Burger', 'Two patties with extra cheddar.', 480.00, 'restaurantManager/assets/images/burger.png', 1, '2026-05-03 10:05:00'),
(3, 1, 2, 'Pepperoni Pizza', 'Twelve inch pizza with mozzarella and pepperoni.', 780.00, 'restaurantManager/assets/images/pizza.png', 1, '2026-05-03 10:10:00'),
(4, 1, 3, 'Coca Cola', 'Chilled 330ml can.', 70.00, 'restaurantManager/assets/images/coca.png', 1, '2026-05-03 10:15:00'),
(5, 1, 3, 'French Fries', 'Crispy salted fries.', 140.00, 'restaurantManager/assets/images/fries.png', 1, '2026-05-03 10:20:00'),
(6, 2, 4, 'Salmon Sushi Set', 'Eight pieces with soy sauce and wasabi.', 850.00, 'Customer/assets/images/sakura-sushi.svg', 1, '2026-05-03 11:00:00'),
(7, 2, 4, 'California Roll', 'Crab, avocado, cucumber, and sesame.', 620.00, 'Customer/assets/images/sakura-sushi.svg', 1, '2026-05-03 11:05:00'),
(8, 2, 5, 'Chicken Ramen', 'Warm broth with chicken, egg, and noodles.', 590.00, 'Customer/assets/images/sakura-sushi.svg', 1, '2026-05-03 11:10:00'),
(9, 2, 6, 'Gyoza', 'Pan fried dumplings.', 260.00, 'Customer/assets/images/sakura-sushi.svg', 1, '2026-05-03 11:15:00'),
(10, 3, 7, 'Chicken Power Bowl', 'Rice, grilled chicken, greens, and sauce.', 430.00, 'Customer/assets/images/green-bowl.svg', 1, '2026-05-03 12:00:00'),
(11, 3, 8, 'Garden Salad', 'Fresh vegetables with house dressing.', 280.00, 'Customer/assets/images/green-bowl.svg', 1, '2026-05-03 12:05:00'),
(12, 3, 9, 'Mango Smoothie', 'Fresh mango blended with yogurt.', 190.00, 'Customer/assets/images/green-bowl.svg', 1, '2026-05-03 12:10:00');

INSERT INTO discounts (id, menu_item_id, restaurant_id, discount_pct, valid_from, valid_until, is_active) VALUES
(1, 1, 1, 10.00, '2026-05-01 00:00:00', '2026-06-01 23:59:59', 1),
(2, 6, 2, 15.00, '2026-05-01 00:00:00', '2026-06-01 23:59:59', 1),
(3, 10, 3, 8.00, '2026-05-01 00:00:00', '2026-06-01 23:59:59', 1);

INSERT INTO delivery_agents (id, user_id, vehicle_type, is_online, current_location_text, total_earnings, is_approved) VALUES
(1, 8, 'bike', 1, 'Gulshan, Dhaka', 420.00, 1),
(2, 9, 'cycle', 0, 'Dhanmondi, Dhaka', 120.00, 1);

INSERT INTO orders (id, customer_id, restaurant_id, agent_id, delivery_address, payment_method, subtotal, delivery_fee, total_amount, status, estimated_delivery_minutes, created_at) VALUES
(1, 4, 1, 1, 'House 12, Road 5, Gulshan, Dhaka', 'Cash', 390.00, 50.00, 440.00, 'delivered', 30, '2026-05-15 12:10:00'),
(2, 5, 1, 1, 'Mirpur 10, Dhaka', 'Card', 920.00, 60.00, 980.00, 'delivered', 45, '2026-05-16 14:25:00'),
(3, 6, 2, 2, 'Banani DOHS, Dhaka', 'Cash', 1110.00, 70.00, 1180.00, 'delivered', 40, '2026-05-17 19:30:00'),
(4, 7, 2, 1, 'Bashundhara R/A, Dhaka', 'Card', 850.00, 70.00, 920.00, 'picked_up', 35, '2026-05-19 11:20:00'),
(5, 4, 1, NULL, 'Dhanmondi 15, Dhaka', 'Cash', 620.00, 50.00, 670.00, 'ready', 25, '2026-05-19 12:05:00'),
(6, 5, 3, NULL, 'Mohammadpur, Dhaka', 'Cash', 620.00, 50.00, 670.00, 'accepted', 30, '2026-05-19 12:25:00'),
(7, 6, 1, NULL, 'Uttara Sector 7, Dhaka', 'Card', 480.00, 70.00, 550.00, 'pending', 35, '2026-05-19 12:45:00'),
(8, 7, 2, NULL, 'Farmgate, Dhaka', 'Cash', 590.00, 50.00, 640.00, 'preparing', 30, '2026-05-19 13:10:00');

INSERT INTO order_items (id, order_id, menu_item_id, quantity, unit_price) VALUES
(1, 1, 1, 1, 320.00),
(2, 1, 4, 1, 70.00),
(3, 2, 2, 1, 480.00),
(4, 2, 5, 2, 140.00),
(5, 2, 4, 2, 70.00),
(6, 3, 6, 1, 850.00),
(7, 3, 9, 1, 260.00),
(8, 4, 6, 1, 850.00),
(9, 5, 7, 1, 620.00),
(10, 6, 10, 1, 430.00),
(11, 6, 12, 1, 190.00),
(12, 7, 2, 1, 480.00),
(13, 8, 8, 1, 590.00);

INSERT INTO delivery_assignments (id, order_id, agent_id, assigned_at, picked_up_at, delivered_at, status) VALUES
(1, 1, 1, '2026-05-15 12:15:00', '2026-05-15 12:35:00', '2026-05-15 13:00:00', 'delivered'),
(2, 2, 1, '2026-05-16 14:35:00', '2026-05-16 15:00:00', '2026-05-16 15:30:00', 'delivered'),
(3, 3, 2, '2026-05-17 19:40:00', '2026-05-17 20:00:00', '2026-05-17 20:35:00', 'delivered'),
(4, 4, 1, '2026-05-19 11:30:00', '2026-05-19 11:55:00', NULL, 'picked_up');

INSERT INTO reviews (id, order_id, customer_id, restaurant_id, rating, comment, manager_reply, created_at) VALUES
(1, 1, 4, 1, 5, 'Burger was fresh and delivery was quick.', 'Thank you for ordering from us.', '2026-05-15 13:20:00'),
(2, 2, 5, 1, 4, 'Good food, fries could be warmer.', NULL, '2026-05-16 16:00:00'),
(3, 3, 6, 2, 5, 'Loved the sushi set.', 'Happy to hear that.', '2026-05-17 21:00:00');

INSERT INTO saved_restaurants (id, customer_id, restaurant_id, created_at) VALUES
(1, 4, 1, '2026-05-10 10:00:00'),
(2, 4, 2, '2026-05-10 10:05:00'),
(3, 5, 1, '2026-05-10 10:10:00'),
(4, 6, 3, '2026-05-10 10:15:00');

INSERT INTO delivery_addresses (id, customer_id, label, address_line, city, is_default) VALUES
(1, 4, 'Home', 'House 12, Road 5, Gulshan', 'Dhaka', 1),
(2, 4, 'Office', 'Dhanmondi 15', 'Dhaka', 0),
(3, 5, 'Home', 'Mirpur 10', 'Dhaka', 1),
(4, 6, 'Home', 'Banani DOHS', 'Dhaka', 1),
(5, 7, 'Home', 'Bashundhara R/A', 'Dhaka', 1);

INSERT INTO complaints (id, submitter_id, subject, description, status, created_at) VALUES
(1, 5, 'Late delivery', 'My order took longer than the estimate.', 'open', '2026-05-18 10:00:00'),
(2, 6, 'Missing item', 'One side item was missing from the package.', 'resolved', '2026-05-18 12:30:00');

INSERT INTO platform_settings (id, setting_key, setting_value) VALUES
(1, 'platform_name', 'BiteBuddy FoodOS'),
(2, 'default_delivery_fee', '50'),
(3, 'support_email', 'support@bitebuddy.test');
