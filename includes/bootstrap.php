<?php
/**
 * BOOTSTRAP
 * ---------
 * Start session, load config, autoload or require common files.
 * Include at top of index.php and API endpoints.
 */

session_start();

require_once dirname(__DIR__) . '/config/app.php';
require_once dirname(__DIR__) . '/config/database.php';
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/auth.php';
