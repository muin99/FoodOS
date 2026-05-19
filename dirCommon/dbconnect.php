<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'foodos_db';

mysqli_report(MYSQLI_REPORT_OFF);
$conn = @mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    http_response_code(500);

    $isApiRequest = strpos($_SERVER['REQUEST_URI'] ?? '', '/controller/') !== false;

    if ($isApiRequest) {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => 'Database connection failed. Please start MySQL from XAMPP Control Panel.'
        ]);
    } else {
        echo 'Database connection failed. Please start MySQL from XAMPP Control Panel.';
    }

    exit;
}
