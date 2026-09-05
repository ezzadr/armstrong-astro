<?php
/**
 * Armstrong Locksmith — Smart 404 Router & 301 Redirect Engine
 * 
 * On Cloudways, Nginx's try_files falls back to /index.php when no static
 * file matches. This script:
 * 1. Checks if the URL is for the homepage.
 * 2. Checks if an exact static HTML file exists for this route.
 * 3. Intercepts legacy deleted city/doorway/suburb URLs and 301 redirects to /service-areas/.
 * 4. Intercepts legacy WordPress categories/tags and 301 redirects to /blog/.
 * 5. Otherwise serves the custom 404 page with a proper HTTP 404 status.
 */

$requestUri = strtok($_SERVER['REQUEST_URI'] ?? '', '?');
$rawUri   = trim($requestUri, '/');   // exact path as typed, for serving files
$cleanUri = strtolower($rawUri);       // lowercased, for redirect matching only

// If requesting homepage, serve it directly
if ($cleanUri === '' || $cleanUri === 'index.html' || $cleanUri === 'index.php') {
    readfile(__DIR__ . '/index.html');
    exit;
}

// Serve ONLY built pages: a directory's index.html or a bare .html file,
// matched case-sensitively against the URL as typed. Never readfile() an
// arbitrary existing path: Nginx only falls through to this script when the
// exact file is missing, so a case-variant URL such as /api/REVIEWS.PHP used
// to lowercase-match api/reviews.php and echo its PHP source (and key.php).
$safePath = preg_match('#^[A-Za-z0-9._/-]+$#', $rawUri) === 1 && strpos($rawUri, '..') === false;
if ($safePath) {
    $htmlPath = __DIR__ . '/' . $rawUri . '/index.html';
    if (is_file($htmlPath)) {
        readfile($htmlPath);
        exit;
    }
    if (substr($rawUri, -5) === '.html' && is_file(__DIR__ . '/' . $rawUri)) {
        readfile(__DIR__ . '/' . $rawUri);
        exit;
    }
}

// ==============================================================================
// 301 REDIRECT ENGINE FOR DELETED CITY / SUBURB / DOORWAY PAGES
// ==============================================================================

// 1. Service area subpaths & location folders
if (preg_match('#^(service-area|locations?|cit(y|ies)|areas?)/#i', $cleanUri)) {
    header('Location: /service-areas/', true, 301);
    exit;
}

// 2. Generic location searches
if (preg_match('#^(locksmith-near-me|emergency-locksmith-near-me|mobile-locksmith-near-me|24-hour-locksmith|24-7-locksmith|cheap-locksmith-nashville|locksmith-service-areas)$#i', $cleanUri)) {
    header('Location: /service-areas/', true, 301);
    exit;
}

// 3. Middle TN cities & suburbs regex list
$citiesPattern = 'brentwood|franklin|cool-springs|coolsprings|murfreesboro|hendersonville|mount-juliet|mt-juliet|mtjuliet|lebanon|smyrna|la-vergne|lavergne|gallatin|spring-hill|springhill|antioch|donelson|hermitage|green-hills|greenhills|belle-meade|bellemeade|nolensville|east-nashville|goodlettsville|madison|columbia|dickson|clarksville|fairview|thompsons-station|thompson-station|berry-hill|berryhill|inglewood|old-hickory|oldhickory|bellevue|west-end|the-gulch|gulch|germantown';

// Matches locksmith-[city], [city]-locksmith, commercial-locksmith-[city], [city], etc.
if (preg_match('#(locksmith.*(' . $citiesPattern . ')|(' . $citiesPattern . ').*locksmith|^(' . $citiesPattern . ')(-tn)?$)#i', $cleanUri)) {
    header('Location: /service-areas/', true, 301);
    exit;
}

// 4. Legacy WordPress taxonomy & author archives
if (preg_match('#^(category|tag|author)/#i', $cleanUri)) {
    header('Location: /blog/', true, 301);
    exit;
}

// No match found — serve 404 with proper HTTP status
http_response_code(404);
readfile(__DIR__ . '/404.html');
exit;
