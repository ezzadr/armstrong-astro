<?php
// contact.php - Real-time Twilio SMS Dispatch for Armstrong Locksmith Lead Form
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method Not Allowed']);
    exit();
}

// 1. Read JSON input
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data) {
    $data = $_POST;
}

$name    = isset($data['name']) ? trim($data['name']) : '';
$phone   = isset($data['phone']) ? trim($data['phone']) : '';
$service = isset($data['service']) ? trim($data['service']) : 'General Locksmith';
$details = isset($data['details']) ? trim($data['details']) : 'None specified';
$email   = isset($data['email']) ? trim($data['email']) : 'Not provided';
$notes   = isset($data['notes']) ? trim($data['notes']) : '';

if (empty($name) || empty($phone)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Name and Phone are required.']);
    exit();
}

// 2. Twilio Active Configuration
$accountSid = 'AC1c283a892ca8f15081d8b000a2a5d5b2';
$authToken  = '795ea3068ae5a0193043254012d1c7b4';
$twilioFrom = '+16293389619';
$toPhone    = '+16156258000';

// Format SMS Alert Message
$messageBody = "🚨 NEW LOCKSMITH LEAD!\n"
             . "👤 Name: " . $name . "\n"
             . "📞 Phone: " . $phone . "\n"
             . "🔑 Service: " . $service . "\n"
             . "🚗 Details: " . $details . "\n";

if (!empty($notes)) {
    $messageBody .= "📝 Notes: " . $notes . "\n";
}
if (!empty($email) && $email !== 'Not provided') {
    $messageBody .= "✉️ Email: " . $email . "\n";
}
$messageBody .= "⚡ Call customer back immediately!";

// 3. Send SMS via Twilio REST API
$url = "https://api.twilio.com/2010-04-01/Accounts/" . $accountSid . "/Messages.json";

$postFields = [
    'From' => $twilioFrom,
    'To'   => $toPhone,
    'Body' => $messageBody
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postFields));
curl_setopt($ch, CURLOPT_USERPWD, $accountSid . ":" . $authToken);
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

$responseData = json_decode($response, true);

if ($httpCode >= 200 && $httpCode < 300) {
    echo json_encode([
        'success' => true,
        'message' => 'Quote request received and SMS alert sent successfully!',
        'sid'     => $responseData['sid'] ?? null
    ]);
} else {
    echo json_encode([
        'success' => false,
        'error'   => $responseData['message'] ?? 'Twilio API returned code ' . $httpCode,
        'code'    => $responseData['code'] ?? $httpCode
    ]);
}
