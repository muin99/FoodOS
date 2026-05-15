<?php
/**
 * APPLICATION CONFIG
 * ------------------
 * Base paths, URL helpers, upload directories, session name.
 * Used by bootstrap and controllers.
 */

define('BASE_PATH', dirname(__DIR__));
// Local XAMPP: /FoodOS  |  cPanel subfolder: /foodos  |  domain root: ''
define('BASE_URL', '/FoodOS');

define('UPLOAD_PROFILE', BASE_PATH . '/assets/uploads/profiles/');
define('UPLOAD_RESTAURANT', BASE_PATH . '/assets/uploads/restaurants/');
define('UPLOAD_MENU', BASE_PATH . '/assets/uploads/menu/');
