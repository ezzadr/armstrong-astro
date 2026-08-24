<?php
/**
 * Armstrong Locksmith — Production Astro Entrypoint
 * Serves index.html cleanly on Cloudways Apache / Nginx stacks
 */
if (file_exists(__DIR__ . '/index.html')) {
    readfile(__DIR__ . '/index.html');
    exit;
}
