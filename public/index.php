<?php
/**
 * Armstrong Locksmith — Smart 404 Router & 301 Redirect Engine
 * 
 * On Cloudways, Nginx's try_files falls back to /index.php when no static
 * file matches. This script:
 * 1. Checks if the URL is for the homepage.
 * 2. Checks if an exact static HTML file exists for this route.
 * 3. Intercepts retired WordPress pages that still hold search traffic and 301s
 *    each to its closest live equivalent.
 * 4. Sends root-level blog slugs to their /blog/ equivalent.
 * 5. Maps the WordPress URLs from the Search Console 404 export to their
 *    nearest live page, by exact slug and then by pattern family
 *    (products, shop, date archives, blog pagination).
 * 6. Intercepts legacy deleted city/doorway/suburb URLs and 301 redirects to /service-areas/.
 * 7. Intercepts legacy WordPress categories/tags and 301 redirects to /blog/.
 * 8. Otherwise serves the custom 404 page with a proper HTTP 404 status.
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

// 3. Retired WordPress pages that still hold Search Console traffic.
//    Exact matches only, so live slugs sharing a prefix are unaffected.
$retiredPages = [
    'price-list' => '/locksmith-pricing-nashville/',
    'key-fob-locked-in-car-nashville' => '/emergency-car-lockout/',
    'automotive-transponder-keys' => '/car-key-replacement-nashville/',
    'locksmith-services-for-property-managers-and-landlords' => '/commercial-locksmith/',
    'service-in-your-city' => '/service-areas/',
    'nashville' => '/emergency-car-lockout/',
    'auto-locksmith-immediate-response-nashville' => '/automotive-locksmith-in-nashville-tn/',
    'best-places-to-hide-valuable-in-car' => '/automotive-locksmith-in-nashville-tn/',
    'these-are-the-best-places-to-hide-valuables-in-your-car' => '/automotive-locksmith-in-nashville-tn/',
];
foreach ($retiredPages as $oldPath => $newPath) {
    if (strcasecmp($cleanUri, $oldPath) === 0) {
        header('Location: ' . $newPath, true, 301);
        exit;
    }
}

// 4. Blog posts that sat at the site root under WordPress now live under
//    /blog/. If the root slug matches a real post, send it there. Exact
//    same article at the new path, so this is the strongest kind of 301,
//    and it covers any future post that moves without another code change.
if ($safePath && is_file(__DIR__ . "/blog/" . $rawUri . "/index.html")) {
    header('Location: /blog/' . $rawUri . '/', true, 301);
    exit;
}

// 5. Legacy WordPress URLs from the Search Console 404 export (2026-09-14).
//    Exact slugs first, then pattern families. Every target is the nearest
//    live page by subject; nothing here is a blanket punt to the homepage.
$legacySlugs = [
    'home' => '/',
    'contact-us' => '/contact-armstrong-locksmith/',
    'automotive-locksmith-nashville-tn' => '/automotive-locksmith-in-nashville-tn/',
    'commercial-locksmith-nashville' => '/commercial-locksmith/',
    'commercial-locksmith-nashville-tn' => '/commercial-locksmith/',
    'locksmith-services-for-property-managers' => '/commercial-locksmith/',
    'secure-business-with-commercial-locksmith' => '/commercial-locksmith/',
    'protect-your-business-with-a-commercial-locksmith' => '/commercial-locksmith/',
    'residential-vs-commercial-locksmith' => '/commercial-locksmith/',
    'buick' => '/car-key-replacement-nashville/',
    'cadillac' => '/car-key-replacement-nashville/',
    'chevrolet' => '/car-key-replacement-nashville/',
    'chrysler' => '/car-key-replacement-nashville/',
    'genesis' => '/car-key-replacement-nashville/',
    'gmc' => '/car-key-replacement-nashville/',
    'infiniti' => '/car-key-replacement-nashville/',
    'lincoln' => '/car-key-replacement-nashville/',
    'mercury' => '/car-key-replacement-nashville/',
    'mini-cooper' => '/car-key-replacement-nashville/',
    'mitsubishi' => '/car-key-replacement-nashville/',
    'scion' => '/car-key-replacement-nashville/',
    'subaru' => '/car-key-replacement-nashville/',
    'volvo' => '/car-key-replacement-nashville/',
    'nissan-car-key-replacement' => '/car-key-replacement-nashville/',
    'porsche' => '/european-car-key-specialist/',
    'maserati' => '/european-car-key-specialist/',
    'rolls-royce' => '/european-car-key-specialist/',
    'bugatti' => '/european-car-key-specialist/',
    'alfa-romeo-3' => '/european-car-key-specialist/',
    'jaguar-key-replacement-nashville-tn' => '/european-car-key-specialist/',
    'bmw-key-fobs-nashville' => '/bmw/',
    'mercedes-benz-car-key-replacement-why-you-need-a-locksmith' => '/mercedes-benz/',
    'lost-your-mercedes-key-in-nashville-why-dealerships-arent-your-only-option' => '/mercedes-benz/',
    'replace-a-lost-car-key' => '/car-key-replacement-nashville/',
    'spare-car-key-nashville' => '/car-key-replacement-nashville/',
    'key-duplication-locksmith-in-nashville' => '/car-key-replacement-nashville/',
    'transponder-keys-for-cars-how-do-they-work' => '/car-key-replacement-nashville/',
    'avoid-losing-keys-to-nashville-lakes' => '/car-key-replacement-nashville/',
    'a-guide-to-security-key-fobs' => '/key-fob-replacement-nashville/',
    'pros-and-cons-of-key-fob-entry-systems' => '/key-fob-replacement-nashville/',
    'unique-car-key-fobs-nashville' => '/key-fob-replacement-nashville/',
    'these-are-the-most-unique-and-cool-car-key-fobs' => '/key-fob-replacement-nashville/',
    'reasons-why-remote-car-key-stopped-working' => '/key-fob-replacement-nashville/',
    '6-reasons-why-your-remote-car-key-stopped-working-and-what-to-do-about-it' => '/key-fob-replacement-nashville/',
    'the-benefits-of-choosing-a-locksmith-over-your-car-dealership' => '/dealer-key/',
    'can-a-car-locksmith-fix-your-modern-key' => '/automotive-locksmith-in-nashville-tn/',
    'keyless-theft-protection-nashville' => '/automotive-locksmith-in-nashville-tn/',
    'stuck-key-ignition-nashville' => '/automotive-locksmith-in-nashville-tn/',
    'how-to-remove-a-stuck-key-from-your-cars-ignition-easy-methods' => '/automotive-locksmith-in-nashville-tn/',
    'the-trusted-mobile-car-key-replacement-expert-armstrong-locksmith' => '/automotive-locksmith-in-nashville-tn/',
    'lock-your-keys-in-the-car' => '/emergency-car-lockout/',
    'guide-to-carlockout-situations' => '/emergency-car-lockout/',
    'how-to-open-a-car-locked-with-the-key-fob-inside-tips-and-tricks' => '/emergency-car-lockout/',
    'emergency-locksmith-the-top-key-reasons-where-you-will-need-one' => '/emergency-car-lockout/',
    'locksmith-tips-and-tricks-for-when-you-accidentally-lock-yourself-out' => '/emergency-car-lockout/',
    'what-to-do-if-you-get-locked-out-of-your-house-quick-tips' => '/residential-locksmith-nashville/',
    'secure-your-front-door' => '/residential-locksmith-nashville/',
    'secure-your-garage-door' => '/residential-locksmith-nashville/',
    'types-of-locks-and-features' => '/residential-locksmith-nashville/',
    'popular-types-of-door-locks-and-uses-the-guide' => '/residential-locksmith-nashville/',
    'how-to-maintain-your-door-locks-tips-for-keeping-your-home-safe' => '/residential-locksmith-nashville/',
    'common-lock-problems-in-nashville' => '/residential-locksmith-nashville/',
    'how-to-detect-lock-tampering-signs-you-should-look-for' => '/residential-locksmith-nashville/',
    'locksmith-help-improve-home-security' => '/residential-locksmith-nashville/',
    'home-security-in-christmas' => '/residential-locksmith-nashville/',
    'benefits-of-keyless-entry-systems' => '/residential-locksmith-nashville/',
    'airbnb-security-nashville' => '/residential-locksmith-nashville/',
    'the-perfect-place-to-hide-a-key-outside-12-easy-effective-hiding-places' => '/residential-locksmith-nashville/',
    'why-you-need-to-rekey-your-home' => '/nashville-rekey-service/',
    'rekey-locks-new-home-nashville' => '/nashville-rekey-service/',
    'how-to-find-locksmith-in-nashville' => '/about-us/',
    'locksmith-ensuring-safety-in-nashville' => '/about-us/',
    'mobile-vs-store-locksmith-nashville' => '/nashville-locksmith-storefront/',
    'in-store-locksmith-vs-mobile-locksmith-service' => '/nashville-locksmith-storefront/',
    'armstrong-locksmith-warns-of-the-dangers-of-unlicensed-locksmith-operators-in-nashville-tn' => '/about-us/',
    'old-hickory-locksmith' => '/service-areas/',
    'hermitage-tn' => '/service-areas/',
    'best-locksmith-service-in-brentwood' => '/service-areas/',
    'free-things-to-do-franklin-tn' => '/service-areas/',
    'surprising-facts-about-brentwood' => '/service-areas/',
    'things-to-do-in-downtown-nashville' => '/service-areas/',
    'service-areas/belle-meade' => '/service-areas/',
    'service-areas/hermitage' => '/service-areas/',
    'service-areas/antioch' => '/service-areas/',
    'service-areas/donelson' => '/service-areas/',
];
if (isset($legacySlugs[$cleanUri])) {
    header('Location: ' . $legacySlugs[$cleanUri], true, 301);
    exit;
}

$legacyPatterns = [
    // WooCommerce product pages - the shop is gone; every one was a key or remote
    ['#^product/#i', '/key-fob-replacement-nashville/'],
    // WooCommerce category listings
    ['#^product-category/#i', '/key-fob-replacement-nashville/'],
    // WooCommerce shop index and its pagination
    ['#^shop(/|$)#i', '/key-fob-replacement-nashville/'],
    // WordPress date archives
    ['#^[0-9]{4}/[0-9]{2}/[0-9]{2}(/|$)#i', '/blog/'],
    // WordPress blog pagination
    ['#^blog/page/[0-9]+(/|$)#i', '/blog/'],
];
foreach ($legacyPatterns as $rule) {
    if (preg_match($rule[0], $cleanUri)) {
        header('Location: ' . $rule[1], true, 301);
        exit;
    }
}

// 6. Middle TN cities & suburbs regex list
$citiesPattern = 'brentwood|franklin|cool-springs|coolsprings|murfreesboro|hendersonville|mount-juliet|mt-juliet|mtjuliet|lebanon|smyrna|la-vergne|lavergne|gallatin|spring-hill|springhill|antioch|donelson|hermitage|green-hills|greenhills|belle-meade|bellemeade|nolensville|east-nashville|goodlettsville|madison|columbia|dickson|clarksville|fairview|thompsons-station|thompson-station|berry-hill|berryhill|inglewood|old-hickory|oldhickory|bellevue|west-end|the-gulch|gulch|germantown';

// Matches locksmith-[city], [city]-locksmith, commercial-locksmith-[city], [city], etc.
if (preg_match('#(locksmith.*(' . $citiesPattern . ')|(' . $citiesPattern . ').*locksmith|^(' . $citiesPattern . ')(-tn|-tennessee)?$)#i', $cleanUri)) {
    header('Location: /service-areas/', true, 301);
    exit;
}

// 7. Legacy WordPress taxonomy & author archives
if (preg_match('#^(category|tag|author)/#i', $cleanUri)) {
    header('Location: /blog/', true, 301);
    exit;
}

// No match found — serve 404 with proper HTTP status
http_response_code(404);
readfile(__DIR__ . '/404.html');
exit;
