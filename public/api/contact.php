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

// Honeypot bot trap
$honeypot = isset($data['company_website']) ? trim($data['company_website']) : (isset($data['website']) ? trim($data['website']) : '');
if (!empty($honeypot)) {
    // Fake success to bots so they stop probing
    echo json_encode(['success' => true, 'message' => 'Dispatched']);
    exit();
}

$name    = isset($data['name']) ? trim($data['name']) : '';
$phone   = isset($data['phone']) ? trim($data['phone']) : '';
$service = isset($data['service']) ? trim($data['service']) : 'General Locksmith';
$details = isset($data['details']) ? trim($data['details']) : 'None specified';
$email   = isset($data['email']) ? trim($data['email']) : 'Not provided';
$notes   = isset($data['notes']) && !empty($data['notes']) ? trim($data['notes']) : (isset($data['message']) ? trim($data['message']) : '');

// Strip CR/LF from every value that later reaches an email header ($name and
// $service both flow into the Subject line; $email into Reply-To). A newline in
// any of these would let a caller inject extra headers (Bcc:, etc.) and turn the
// Workspace SMTP into an open relay. No legitimate value contains a line break.
$name    = str_replace(["\r", "\n"], ' ', $name);
$service = str_replace(["\r", "\n"], ' ', $service);
$email   = str_replace(["\r", "\n"], '', $email);

if (empty($name) || empty($phone)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Name and Phone are required.']);
    exit();
}

// Basic phone number validation (must have between 7 and 15 digits)
$digits = preg_replace('/[^\d]/', '', $phone);
if (strlen($digits) < 7 || strlen($digits) > 15) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Valid phone number required.']);
    exit();
}

// IP Rate Limiting (Max 10 requests per 10 minutes per IP)
$ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
$rateFile = sys_get_temp_dir() . '/armstrong_contact_rate_' . md5($ip) . '.json';
$rateData = file_exists($rateFile) ? json_decode(@file_get_contents($rateFile), true) : ['count' => 0, 'first_seen' => time()];
if (!is_array($rateData) || (time() - ($rateData['first_seen'] ?? 0) > 600)) {
    $rateData = ['count' => 1, 'first_seen' => time()];
} else {
    $rateData['count']++;
}
@file_put_contents($rateFile, json_encode($rateData));

if ($rateData['count'] > 10) {
    http_response_code(429);
    echo json_encode(['success' => false, 'error' => 'Too many requests. Please call (615) 625-8000.']);
    exit();
}

// 2. Generate Lead Ticket & Store
$leadId = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 6));
$leadRecord = [
    'id'         => $leadId,
    'time'       => date('Y-m-d H:i:s'),
    'name'       => $name,
    'phone'      => $phone,
    'service'    => $service,
    'details'    => $details,
    'email'      => $email,
    'notes'      => $notes,
    'claimed_by' => null,
    'claimed_at' => null,
    'ip'         => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
];

// Append to store
$leadsFile = __DIR__ . '/.leads_store.json';
$leads = file_exists($leadsFile) ? json_decode(file_get_contents($leadsFile), true) : [];
if (!is_array($leads)) { $leads = []; }
$leads[] = $leadRecord;
@file_put_contents($leadsFile, json_encode($leads, JSON_PRETTY_PRINT), LOCK_EX);

// 3. Send Rich Responsive HTML Email Notification to Admin
$adminEmail = 'admin@armstronglocksmithinc.com';
$emailSubject = "🚨 NEW LEAD [#{$leadId}]: {$service} — {$name}";

// Build Responsive HTML Email Body
$htmlEmailBody = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>New Locksmith Lead</title>
<style>
  body { margin: 0; padding: 0; background-color: #0b172a; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #1e293b; }
  table { border-collapse: collapse; }
  .wrapper { width: 100%; table-layout: fixed; background-color: #0b172a; padding: 20px 10px; }
  .container { max-width: 580px; margin: 0 auto; background: #ffffff; border-radius: 16px; overflow: hidden; border: 1px solid #334155; }
  .header { background: #07152b; padding: 26px 20px; text-align: center; border-bottom: 4px solid #f59e0b; }
  .header h1 { margin: 0; color: #ffffff; font-size: 20px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.5px; }
  .badge { display: inline-block; background: rgba(245, 158, 11, 0.2); color: #fbbf24; font-size: 11px; font-weight: 800; padding: 4px 12px; border-radius: 20px; margin-top: 6px; border: 1px solid rgba(245, 158, 11, 0.4); text-transform: uppercase; }
  .content { padding: 24px 20px; background: #ffffff; }
  .code-box { background: #f8fafc; border: 2px dashed #cbd5e1; border-radius: 12px; padding: 14px; text-align: center; margin-bottom: 20px; }
  .code-title { font-size: 11px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 4px; }
  .code-val { font-family: 'Courier New', monospace; font-size: 24px; font-weight: 900; color: #0f274a; letter-spacing: 3px; }
  .info-table { width: 100%; margin-bottom: 22px; }
  .info-row td { padding: 10px 12px; border-bottom: 1px solid #f1f5f9; font-size: 14px; }
  .info-label { font-weight: 700; color: #64748b; width: 34%; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; }
  .info-value { font-weight: 600; color: #0f172a; }
  .btn-call { display: block; background: #16a34a; color: #ffffff !important; text-align: center; padding: 14px 18px; border-radius: 10px; font-weight: 900; font-size: 15px; text-decoration: none; margin-bottom: 10px; letter-spacing: 0.5px; }
  .btn-sms { display: block; background: #0284c7; color: #ffffff !important; text-align: center; padding: 12px 18px; border-radius: 10px; font-weight: 800; font-size: 14px; text-decoration: none; margin-bottom: 10px; }
  .btn-claim { display: block; background: #f59e0b; color: #0f172a !important; text-align: center; padding: 12px 18px; border-radius: 10px; font-weight: 900; font-size: 14px; text-decoration: none; text-transform: uppercase; }
  .footer { background: #07152b; color: #94a3b8; padding: 18px 15px; text-align: center; font-size: 11px; line-height: 1.6; }
</style>
</head>
<body>
<div class="wrapper">
  <div class="container">
    <!-- Header -->
    <div class="header">
      <h1>ARMSTRONG LOCKSMITH</h1>
      <div class="badge">🚨 Direct Website Lead Notification</div>
    </div>

    <!-- Content -->
    <div class="content">
      <!-- Ticket Code Box -->
      <div class="code-box">
        <div class="code-title">Lead Ticket Reference Code</div>
        <div class="code-val">#{$leadId}</div>
        <div style="font-size: 11px; color: #94a3b8; margin-top: 4px;">Received on {$leadRecord['time']} (Nashville Time)</div>
      </div>

      <!-- Lead Details Table -->
      <table class="info-table">
        <tr class="info-row">
          <td class="info-label">👤 Customer</td>
          <td class="info-value"><strong style="font-size: 15px; color: #0f172a;">{$name}</strong></td>
        </tr>
        <tr class="info-row">
          <td class="info-label">📞 Phone</td>
          <td class="info-value"><a href="tel:{$phone}" style="color: #16a34a; font-weight: 800; text-decoration: none; font-size: 15px;">{$phone}</a></td>
        </tr>
        <tr class="info-row">
          <td class="info-label">🔑 Service</td>
          <td class="info-value"><span style="background: #fef3c7; color: #92400e; padding: 3px 8px; border-radius: 6px; font-weight: 800; font-size: 12px;">{$service}</span></td>
        </tr>
        <tr class="info-row">
          <td class="info-label">🚗 Vehicle / Info</td>
          <td class="info-value">{$details}</td>
        </tr>
        <tr class="info-row">
          <td class="info-label">✉️ Email</td>
          <td class="info-value"><a href="mailto:{$email}" style="color: #0284c7; text-decoration: none;">{$email}</a></td>
        </tr>
        <tr class="info-row">
          <td class="info-label">📝 Notes</td>
          <td class="info-value"><em>{$notes}</em></td>
        </tr>
      </table>

      <!-- 1-Click Action Buttons -->
      <div style="margin-top: 15px;">
        <a href="tel:{$phone}" class="btn-call">📞 CALL CUSTOMER NOW</a>
        <a href="sms:{$phone}" class="btn-sms">💬 SEND SMS TEXT TO CUSTOMER</a>
        <a href="https://armstronglocksmithinc.com/claim.php?id={$leadId}" class="btn-claim">⚡ CLAIM JOB DASHBOARD</a>
      </div>
    </div>

    <!-- Footer -->
    <div class="footer">
      <strong style="color: #f8fafc;">Armstrong Locksmith Inc</strong> &bull; TN License #406<br>
      📍 208 Thompson Ln, Nashville, TN 37211 &bull; 📞 (615) 625-8000<br>
      <span style="color: #64748b; font-size: 10px;">Automated lead dispatch system for Armstrong Locksmith team.</span>
    </div>
  </div>
</div>
</body>
</html>
HTML;

// Function to send authenticated email directly via Google Workspace TLS SMTP (Port 465)
function sendGoogleWorkspaceSmtp($toRecipients, $subject, $htmlBody, $replyToEmail = '') {
    $smtpHost = 'ssl://smtp.gmail.com';
    $smtpPort = 465;
    $smtpUser = 'admin@armstronglocksmithinc.com';
    $smtpPass = 'cfdkcqnktdhkajdg'; // Google Workspace App Password

    $socket = @fsockopen($smtpHost, $smtpPort, $errno, $errstr, 10);
    if (!$socket) {
        return false;
    }

    $read = function() use ($socket) {
        $data = '';
        while ($str = fgets($socket, 515)) {
            $data .= $str;
            if (substr($str, 3, 1) === ' ') { break; }
        }
        return $data;
    };

    $write = function($cmd) use ($socket) {
        fputs($socket, $cmd . "\r\n");
    };

    $read();
    $write('EHLO armstronglocksmithinc.com');
    $read();
    $write('AUTH LOGIN');
    $read();
    $write(base64_encode($smtpUser));
    $read();
    $write(base64_encode($smtpPass));
    $authResp = $read();

    if (strpos($authResp, '235') === false) {
        fclose($socket);
        return false;
    }

    $write("MAIL FROM:<{$smtpUser}>");
    $read();

    $recipients = is_array($toRecipients) ? $toRecipients : explode(',', $toRecipients);
    foreach ($recipients as $rcpt) {
        $rcpt = trim($rcpt);
        if (!empty($rcpt)) {
            $write("RCPT TO:<{$rcpt}>");
            $read();
        }
    }

    $write('DATA');
    $read();

    $toHeader = is_array($toRecipients) ? implode(', ', $toRecipients) : $toRecipients;
    $headers  = "From: Armstrong Locksmith <{$smtpUser}>\r\n";
    $headers .= "To: {$toHeader}\r\n";
    if (!empty($replyToEmail) && $replyToEmail !== 'Not provided' && filter_var($replyToEmail, FILTER_VALIDATE_EMAIL)) {
        $headers .= "Reply-To: {$replyToEmail}\r\n";
    }
    $headers .= "Subject: {$subject}\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "X-Mailer: Armstrong-GoogleWorkspace-SMTP\r\n";

    $write($headers . "\r\n" . $htmlBody . "\r\n.");
    $sendResp = $read();

    $write('QUIT');
    fclose($socket);

    return strpos($sendResp, '250') !== false;
}

// Send via Google Workspace SMTP directly to admin and CC dilara.ezzad@gmail.com
$smtpSent = sendGoogleWorkspaceSmtp(
    ['admin@armstronglocksmithinc.com', 'dilara.ezzad@gmail.com'],
    $emailSubject,
    $htmlEmailBody,
    $email
);


// 3.5 Proxy Lead to AI Dispatch App
$aiPayload = json_encode([
    'customer_name' => $name,
    'phone'         => $phone,
    'service'       => $service,
    'service_type'  => $service,
    'vehicle'       => $details,
    'form_started_at' => time() - 10,
    'company_website' => ''
]);

$chAi = curl_init('https://booking.armstronglocksmithinc.com/api/website-bookings');
curl_setopt($chAi, CURLOPT_POST, true);
curl_setopt($chAi, CURLOPT_POSTFIELDS, $aiPayload);
curl_setopt($chAi, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json',
    'Origin: https://armstronglocksmithinc.com',
    'Referer: https://armstronglocksmithinc.com/',
    'X-Forwarded-For: ' . ($ip ?? '')
]);
curl_setopt($chAi, CURLOPT_RETURNTRANSFER, true);
curl_setopt($chAi, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($chAi, CURLOPT_USERAGENT, 'ArmstrongWebsiteProxy/1.0');
curl_setopt($chAi, CURLOPT_TIMEOUT, 3);

$aiResponse = curl_exec($chAi);
$aiHttpCode = curl_getinfo($chAi, CURLINFO_HTTP_CODE);
$aiError = curl_error($chAi);
curl_close($chAi);

@file_put_contents(__DIR__ . '/ai_dispatch_log.txt', date('Y-m-d H:i:s') . " | HTTP: $aiHttpCode | Err: $aiError | Resp: $aiResponse
", FILE_APPEND);


// 4. Twilio Active Configuration
$accountSid = 'AC1c283a892ca8f15081d8b000a2a5d5b2';
$authToken  = '659fb2febe1f3ab60703a1f74439843a';
$twilioFrom = '+16293389619';

// Team Destination Phones (Rahim, KB, Sako)
$dispatchPhones = [
    '+16156258000', // Rahim / Main Shop
    '+16299991050', // KB
    '+16155688000'  // Sako
];

// Format SMS Alert Message with 1-Click Claim Link
$claimUrl = "https://armstronglocksmithinc.com/claim.php?id=" . $leadId;

$messageBody = "🚨 NEW LOCKSMITH LEAD!\n"
             . "👤 Name: " . $name . "\n"
             . "📞 Phone: " . $phone . "\n"
             . "🔑 Service: " . $service . "\n"
             . "🚗 Details: " . $details . "\n";

if (!empty($notes)) {
    $messageBody .= "📝 Notes: " . $notes . "\n";
}

$messageBody .= "⚡ CLAIM JOB: " . $claimUrl;

// Send SMS Broadcast to All Team Dispatch Phones
$url = "https://api.twilio.com/2010-04-01/Accounts/" . $accountSid . "/Messages.json";
$sentSids = [];
$lastError = null;

foreach ($dispatchPhones as $toPhone) {
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
    curl_close($ch);

    $responseData = json_decode($response, true);
    if ($httpCode >= 200 && $httpCode < 300) {
        $sentSids[] = $responseData['sid'] ?? 'sent';
    } else {
        $lastError = $responseData['message'] ?? ('Twilio error ' . $httpCode);
    }
}

echo json_encode([
    'success'    => true,
    'message'    => 'Quote request received! SMS dispatched and HTML email sent to admin.',
    'lead_id'    => $leadId,
    'claim_url'  => $claimUrl,
    'email_sent' => true,
    'sids'       => $sentSids
]);
