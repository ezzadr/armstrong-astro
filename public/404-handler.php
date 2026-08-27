<?php
/**
 * Armstrong Locksmith — 404 Handler
 * This PHP file ensures a proper HTTP 404 status code is returned
 * along with the custom 404 error page content.
 * Works reliably on Cloudways Nginx/Apache/Varnish stack because
 * PHP requests always get proxied through the full stack.
 */
http_response_code(404);
readfile(__DIR__ . '/404.html');
exit;
