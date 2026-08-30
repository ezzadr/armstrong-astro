<?php
/**
 * Armstrong Locksmith — 404 Handler & Fallback 301 Redirect Engine
 * This PHP file intercepts 404 errors on Cloudways and:
 * 1. Checks if the requested URL was an old deleted city/doorway/suburb page -> 301 to /service-areas/
 * 2. Checks if it was a legacy category/tag page -> 301 to /blog/
 * 3. Otherwise returns a genuine HTTP 404 status code with the custom 404 page content.
 */

$requestUri = strtok($_SERVER['REQUEST_URI'] ?? '', '?');
$cleanUri = trim(strtolower($requestUri), '/');

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

// True 404
http_response_code(404);
readfile(__DIR__ . '/404.html');
exit;
