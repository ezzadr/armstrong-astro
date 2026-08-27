<?php
/**
 * Armstrong Locksmith — Smart 404 Router
 * 
 * On Cloudways, Nginx's try_files falls back to /index.php when no static
 * file matches. This script checks if the requested URL has a valid static
 * page, and if not, serves the custom 404 page with a proper HTTP 404 status.
 * 
 * If the request IS for the homepage (/), it serves index.html normally.
 */

$requestUri = strtok($_SERVER['REQUEST_URI'], '?');
$requestUri = rtrim($requestUri, '/');

// If requesting homepage, serve it directly
if ($requestUri === '' || $requestUri === '/index.html' || $requestUri === '/index.php') {
    readfile(__DIR__ . '/index.html');
    exit;
}

// Check if a static HTML file exists for this route
$htmlPath = __DIR__ . $requestUri . '/index.html';
$directPath = __DIR__ . $requestUri . '.html';
$exactPath = __DIR__ . $requestUri;

if (is_file($htmlPath)) {
    readfile($htmlPath);
    exit;
} elseif (is_file($directPath)) {
    readfile($directPath);
    exit;
} elseif (is_file($exactPath)) {
    readfile($exactPath);
    exit;
}

// No match found — serve 404 with proper HTTP status
http_response_code(404);
readfile(__DIR__ . '/404.html');
exit;
