<?php
declare(strict_types=1);

define('DB_HOST',       'localhost');
define('DB_USER',       'root');
define('DB_PASS',       '');
define('DB_NAME',       'cs');
define('HEADER_HEIGHT', '72px');   // kept in sync with .site-header height

function db_connect(): mysqli {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($conn->connect_error) {
        // Only send header if headers haven't been sent yet
        if (!headers_sent()) {
            http_response_code(503);
            header('Content-Type: text/html; charset=UTF-8');
        }
        exit('<p style="font-family:sans-serif;color:#b71c1c;padding:2rem">
              ⚠️ Database connection failed. Please check your configuration.</p>');
    }

    $conn->set_charset('utf8mb4');
    return $conn;
}
