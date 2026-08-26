<?php
/**
 * Armstrong Locksmith - AI Dispatch Lead Processing API
 * Endpoint: /api/dispatch.php
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data || empty($data['name']) || empty($data['phone'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Missing required fields']);
    exit;
}

$name        = filter_var($data['name'] ?? '', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$phone       = filter_var($data['phone'] ?? '', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$email       = filter_var($data['email'] ?? '', FILTER_SANITIZE_EMAIL);
$serviceType = filter_var($data['service_type'] ?? 'general', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$address     = filter_var($data['address'] ?? '', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$city        = filter_var($data['city'] ?? 'Nashville', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$zip         = filter_var($data['zip'] ?? '', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$timing      = filter_var($data['timing'] ?? 'asap', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$contactPref = filter_var($data['contact_pref'] ?? 'text', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$notes       = filter_var($data['notes'] ?? '', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$refId       = filter_var($data['reference_id'] ?? ('#ARM-' . date('Y') . '-' . rand(1000, 9999)), FILTER_SANITIZE_FULL_SPECIAL_CHARS);

// Contextual vehicle / lock details
$vehicle = trim(($data['vehicle_year'] ?? '') . ' ' . ($data['vehicle_make'] ?? '') . ' ' . ($data['vehicle_model'] ?? ''));
$keyStatus = $data['key_status'] ?? '';
$resService = $data['residential_service'] ?? '';
$commService = $data['commercial_service'] ?? '';
$emergType = $data['emergency_type'] ?? '';

// Build message summary
$details = [];
if (!empty($vehicle)) $details[] = "Vehicle: $vehicle";
if (!empty($keyStatus)) $details[] = "Key Status: $keyStatus";
if (!empty($resService)) $details[] = "Residential: $resService";
if (!empty($commService)) $details[] = "Commercial: $commService";
if (!empty($emergType)) $details[] = "Emergency: $emergType";
if (!empty($notes)) $details[] = "Notes: $notes";
$detailsStr = implode("\n", $details);

$emailBody = "🚨 NEW ARMSTRONG DISPATCH REQUEST ($refId)\n\n"
           . "Name: $name\n"
           . "Phone: $phone\n"
           . "Email: $email\n"
           . "Service Category: $serviceType\n"
           . "Location: $address, $city, TN $zip\n"
           . "Preferred Time: $timing\n"
           . "Preferred Contact: $contactPref\n\n"
           . "--- DETAILS ---\n"
           . "$detailsStr\n\n"
           . "Timestamp: " . date('Y-m-d H:i:s T') . "\n";

// Send notification email to shop dispatcher
$to = 'armstronglocksmithinc@gmail.com';
$subject = "[$refId] Dispatch: $serviceType - $name ($phone)";
$headers = "From: dispatch@armstronglocksmithinc.com\r\n"
         . "Reply-To: " . (!empty($email) ? $email : 'dispatch@armstronglocksmithinc.com') . "\r\n"
         . "X-Mailer: PHP/" . phpversion();

@mail($to, $subject, $emailBody, $headers);

// Respond with success JSON
echo json_encode([
    'success'      => true,
    'reference_id' => $refId,
    'message'      => 'Service request logged and transmitted to dispatch.'
]);
