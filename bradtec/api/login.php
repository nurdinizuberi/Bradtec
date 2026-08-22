<?php
require __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(['ok' => false, 'error' => 'Method not allowed'], 405);
}

if (ADMIN_PASSWORD === '') {
    json_response(['ok' => false, 'error' => 'Admin password is not configured. See README §2.'], 500);
}

/* ---------- Brute-force protection: lock out after repeated failures ---------- */
$ATTEMPT_WINDOW = 15 * 60; // seconds
$ATTEMPT_MAX    = 5;       // failures before lockout

if (!rate_limit_allow('login_attempts', $ATTEMPT_MAX, $ATTEMPT_WINDOW)) {
    $waitMin = (int)ceil(rate_limit_retry_after('login_attempts', $ATTEMPT_WINDOW) / 60);
    json_response([
        'ok' => false,
        'error' => 'Too many failed attempts. Try again in ' . $waitMin . ' minute' . ($waitMin === 1 ? '' : 's') . '.',
    ], 429);
}

$body = read_json_body();
$password = isset($body['password']) ? (string)$body['password'] : '';

if ($password === '' || !hash_equals(ADMIN_PASSWORD, $password)) {
    /* Small delay to slow down brute-force attempts (count already recorded) */
    usleep(400000);
    json_response(['ok' => false, 'error' => 'Incorrect password'], 401);
}

/* Success — clear this IP's failure record */
rate_limit_clear('login_attempts');

session_regenerate_id(true);
$_SESSION['bradtec_admin'] = true;
json_response(['ok' => true]);
