<?php
/**
 * Armstrong Locksmith — 404 Handler & Fallback 301 Redirect Engine
 * Delegates to index.php so Apache ErrorDocument 404 and Nginx try_files
 * share a single source of truth for all 301 redirects and 404 responses.
 */
require __DIR__ . '/index.php';
