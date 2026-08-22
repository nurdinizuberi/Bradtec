<?php
require __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(['ok' => false, 'error' => 'Method not allowed'], 405);
}

/* ---------- Rate limit: max submissions per IP per window ---------- */
$RATE_WINDOW = 15 * 60; // seconds
$RATE_MAX    = 5;       // submissions before throttling

if (!rate_limit_allow('contact_attempts', $RATE_MAX, $RATE_WINDOW)) {
    $waitMin = (int)ceil(rate_limit_retry_after('contact_attempts', $RATE_WINDOW) / 60);
    json_response([
        'ok' => false,
        'error' => 'Too many messages sent. Try again in ' . $waitMin . ' minute' . ($waitMin === 1 ? '' : 's') . '.',
    ], 429);
}

$b = read_json_body();

$fields = ['name', 'company', 'email', 'phone', 'interest', 'subject', 'message'];
$data = [];
foreach ($fields as $f) {
    $data[$f] = isset($b[$f]) ? trim((string)$b[$f]) : '';
}

/* Basic validation */
if ($data['name'] === '') {
    json_response(['ok' => false, 'error' => 'Please enter your full name.'], 422);
}
if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
    json_response(['ok' => false, 'error' => 'Please enter a valid email address.'], 422);
}
if ($data['message'] === '') {
    json_response(['ok' => false, 'error' => 'Please enter a message.'], 422);
}
/* Light honeypot for spam */
if (!empty($b['website'])) {
    json_response(['ok' => true]); // pretend success, drop silently
}

/* Serialize concurrent submissions so two simultaneous posts can't lose one */
json_lock('inquiries.json', true);

$inquiries = json_read('inquiries.json', []);
$entry = [
    'id' => 'inq-' . time() . '-' . substr(bin2hex(random_bytes(3)), 0, 6),
    'name' => $data['name'],
    'company' => $data['company'],
    'email' => $data['email'],
    'phone' => $data['phone'],
    'interest' => $data['interest'],
    'subject' => $data['subject'],
    'message' => $data['message'],
    'read' => false,
    'created_at' => date('Y-m-d H:i:s'),
];
array_unshift($inquiries, $entry);

$write = json_write('inquiries.json', $inquiries);
if ($write !== true) {
    json_response(['ok' => false, 'error' => $write], 500);
}

/* Best-effort email notification (shared-hosting mail()) */
$company = load_company();
$to = $company['email'] !== '' ? $company['email'] : 'info@bradtec.com';
$subject = '[BRADTEC Website] ' . ($data['subject'] !== '' ? $data['subject'] : 'New inquiry') . ' — ' . $data['interest'];
$body = "New website inquiry received:\n\n"
    . "Name: {$data['name']}\n"
    . "Company: " . ($data['company'] ?: '—') . "\n"
    . "Email: {$data['email']}\n"
    . "Phone: " . ($data['phone'] ?: '—') . "\n"
    . "Interested in: {$data['interest']}\n"
    . "Subject: " . ($data['subject'] ?: '—') . "\n\n"
    . "Message:\n{$data['message']}\n";
$headers = "From: {$company['name']} <no-reply@" . (isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : 'bradtec.com') . ">\r\n"
    . "Reply-To: {$data['email']}\r\n";
@mail($to, $subject, $body, $headers);

json_response(['ok' => true, 'message' => 'Thank you! Your inquiry has been sent. We will get back to you shortly.']);
