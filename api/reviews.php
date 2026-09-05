<?php
// reviews.php - Auto-sync latest Google Reviews via Google Places API with local cache
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$cacheFile = __DIR__ . '/google_reviews_cache.json';
$archiveFile = __DIR__ . '/google_reviews_archive.json';
$cacheDuration = 3600; // Cache for 1 hour to stay fast and avoid exceeding Google API rate limits

// ---------------------------------------------------------------------------
// Service-matched reviews: /api/reviews.php?service=bmw
// Google only ever returns the 5 newest reviews, so the archive file (grown
// by every hourly refresh below) is the matching pool - it gets richer over
// time. Returns only 4-star-plus reviews with text whose wording matches the
// service; an empty list is a valid answer and the page simply shows nothing.
// ---------------------------------------------------------------------------
if (isset($_GET['service'])) {
    $serviceMap = [
        'bmw'           => '/\bbmw\b/i',
        'mercedes-benz' => '/mercedes|benz|\besl\b/i',
        'audi'          => '/\baudi\b/i',
        'land-rover'    => '/land\s*rover|range\s*rover/i',
        'bentley'       => '/bentley/i',
        'toyota'        => '/toyota|camry|corolla|rav4|tacoma|4runner/i',
        'honda'         => '/honda|civic|accord|cr-?v/i',
        'kia'           => '/\bkia\b|sorento|optima|sportage|telluride/i',
        'hyundai'       => '/hyundai|elantra|sonata|tucson|santa fe/i',
        'acura'         => '/acura/i',
        'hummer'        => '/hummer/i',
        'key-fob'       => '/\bfob\b|remote|battery/i',
        'rekey'         => '/re-?key|deadbolt|house|home lock|door lock/i',
        'commercial'    => '/commercial|storefront|office|business door|panic|mortise/i',
        'lockout'       => '/locked out|lock-?out|unlock|locked my keys|keys in/i',
        'dealer'        => '/dealer/i',
    ];
    $svc = preg_replace('/[^a-z-]/', '', strtolower($_GET['service']));
    $pattern = isset($serviceMap[$svc]) ? $serviceMap[$svc] : null;
    $out = ['service' => $svc, 'reviews' => []];
    if ($pattern && file_exists($archiveFile)) {
        $arch = json_decode(file_get_contents($archiveFile), true);
        if (is_array($arch)) {
            $matches = array_values(array_filter($arch, function ($r) use ($pattern) {
                return (isset($r['rating']) ? $r['rating'] : 0) >= 4
                    && trim(isset($r['text']) ? $r['text'] : '') !== ''
                    && preg_match($pattern, $r['text']);
            }));
            usort($matches, function ($a, $b) {
                return (isset($b['time']) ? $b['time'] : 0) - (isset($a['time']) ? $a['time'] : 0);
            });
            $out['reviews'] = array_slice(array_map(function ($r) {
                return [
                    'author_name' => $r['author_name'],
                    'rating' => $r['rating'],
                    'relative_time_description' => isset($r['relative_time_description']) ? $r['relative_time_description'] : '',
                    'text' => $r['text'],
                ];
            }, $matches), 0, 6);
        }
    }
    echo json_encode($out);
    exit();
}

// Check cache
if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < $cacheDuration)) {
    $cachedData = file_get_contents($cacheFile);
    if (!empty($cachedData)) {
        echo $cachedData;
        exit();
    }
}

// Google Places API Details
// Key sources, in order: server env var, then an untracked api/key.php that
// simply returns the key string (<?php return 'AIza...'; ). key.php is
// gitignored so deploys never overwrite it and the key never enters the repo;
// requesting it over HTTP executes it and outputs nothing.
$apiKey = getenv('GOOGLE_PLACES_API_KEY') ?: '';
if (empty($apiKey) && file_exists(__DIR__ . '/key.php')) {
    $included = include __DIR__ . '/key.php';
    if (is_string($included)) { $apiKey = trim($included); }
}
$placeId = getenv('GOOGLE_PLACE_ID') ?: 'ChIJ17Fz8F9vZIgRBLkG2KTBuao'; // Default Armstrong Locksmith Place ID

if (empty($apiKey)) {
    // No API key configured: never invent reviews. Serve the real ones already
    // archived from earlier Google fetches (if any), otherwise an empty list,
    // with the last known totals so the counters still render.
    $archived = file_exists($archiveFile) ? json_decode(file_get_contents($archiveFile), true) : [];
    if (!is_array($archived)) { $archived = []; }
    usort($archived, function ($a, $b) { return ($b['time'] ?? 0) - ($a['time'] ?? 0); });
    $fallback = [
        'rating' => 4.9,
        'user_ratings_total' => 777,
        'reviews' => array_slice(array_values(array_map(function ($r) {
            return [
                'author_name' => $r['author_name'] ?? '',
                'rating' => $r['rating'] ?? 5,
                'relative_time_description' => $r['relative_time_description'] ?? '',
                'text' => $r['text'] ?? '',
                'service' => 'Verified Customer',
            ];
        }, array_filter($archived, function ($r) {
            return ($r['rating'] ?? 0) >= 4 && trim($r['text'] ?? '') !== '';
        }))), 0, 6),
    ];
    echo json_encode($fallback);
    exit();
}

// Fetch live from Google Places API
$url = "https://maps.googleapis.com/maps/api/place/details/json?place_id=$placeId&fields=name,rating,user_ratings_total,reviews&reviews_sort=newest&key=$apiKey";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);

if (isset($data['result']['reviews'])) {
    // Testimonial section shows 4-star-plus only (the card UI renders five
    // stars); the full unfiltered list is one click away on Google itself.
    // Also require some text - star-only reviews make an empty-looking card.
    $displayable = array_values(array_filter($data['result']['reviews'], function($r) {
        return ($r['rating'] ?? 0) >= 4 && trim($r['text'] ?? '') !== '';
    }));
    $resultData = [
        'rating' => $data['result']['rating'] ?? 4.9,
        'user_ratings_total' => $data['result']['user_ratings_total'] ?? 777,
        'reviews' => array_map(function($r) {
            return [
                'author_name' => $r['author_name'],
                'rating' => $r['rating'],
                'relative_time_description' => $r['relative_time_description'],
                'text' => $r['text'],
                'service' => 'Verified Customer'
            ];
        }, $displayable)
    ];
    
    // Grow the all-time archive that powers ?service= matching. Google only
    // hands back the 5 newest reviews, so each refresh appends any not yet
    // seen (deduped by time+author) and the pool compounds over time.
    $archive = file_exists($archiveFile) ? json_decode(file_get_contents($archiveFile), true) : [];
    if (!is_array($archive)) { $archive = []; }
    $seen = [];
    foreach ($archive as $r) {
        $seen[(isset($r['time']) ? $r['time'] : 0) . '|' . (isset($r['author_name']) ? $r['author_name'] : '')] = true;
    }
    foreach ($data['result']['reviews'] as $r) {
        $k = (isset($r['time']) ? $r['time'] : 0) . '|' . (isset($r['author_name']) ? $r['author_name'] : '');
        if (!isset($seen[$k])) {
            $archive[] = [
                'author_name' => isset($r['author_name']) ? $r['author_name'] : '',
                'rating' => isset($r['rating']) ? $r['rating'] : 5,
                'relative_time_description' => isset($r['relative_time_description']) ? $r['relative_time_description'] : '',
                'text' => isset($r['text']) ? $r['text'] : '',
                'time' => isset($r['time']) ? $r['time'] : 0,
            ];
            $seen[$k] = true;
        }
    }
    file_put_contents($archiveFile, json_encode($archive));

    file_put_contents($cacheFile, json_encode($resultData));
    echo json_encode($resultData);
} else {
    // Fallback if API returned error
    if (file_exists($cacheFile)) {
        echo file_get_contents($cacheFile);
    } else {
        echo json_encode(['error' => 'Could not fetch reviews', 'status' => $data['status'] ?? 'UNKNOWN']);
    }
}
