<?php
/**
 * BRADTEC CO. LTD — shared configuration & helpers
 * ------------------------------------------------
 * !!! BEFORE GOING LIVE !!!
 * 1. Set your admin password and real domain in a .env file (see README §2) —
 *    never hardcode them here. Both are read from the environment / .env.
 * 2. Update the contact details in data/company.json (also editable in /admin).
 * 3. Make sure the data/ folder is writable by PHP (see README.md).
 */

if (session_status() === PHP_SESSION_NONE) {
    /* Mark the cookie Secure only when actually on HTTPS, so local HTTP
       development keeps working. */
    $bradtec_https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (isset($_SERVER['SERVER_PORT']) && (int)$_SERVER['SERVER_PORT'] === 443);
    session_set_cookie_params([
        'httponly' => true,
        'samesite' => 'Lax',
        'secure' => $bradtec_https,
    ]);
    session_start();
}

/* ------------------------------------------------------------------ */
/* Site constants                                                     */
/* ------------------------------------------------------------------ */
define('SITE_NAME', 'BRADTEC CO. LTD');
define('SITE_TAGLINE', 'The Epic of Excellence');
/* Set BRADTEC_SITE_URL in .env to your real domain before going live
   (drives SEO canonical URLs, Open Graph and schema.org markup). */
define('SITE_URL', env_value('BRADTEC_SITE_URL', 'https://www.bradtec.com'));

/**
 * Read a value from the environment, falling back to a .env file.
 * .env is looked for OUTSIDE the webroot first (one level above bradtec/),
 * then inside data/ (which is blocked from the web by data/.htaccess).
 * Keep .env out of version control.
 */
function env_value(string $key, ?string $default = null): ?string {
    $v = getenv($key);
    if ($v !== false && $v !== '') {
        return $v;
    }
    foreach ([dirname(__DIR__) . '/../.env', dirname(__DIR__) . '/data/.env'] as $envFile) {
        if (!is_file($envFile)) {
            continue;
        }
        foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [] as $line) {
            $line = trim($line);
            if ($line === '' || $line[0] === '#') {
                continue;
            }
            $pos = strpos($line, '=');
            if ($pos === false) {
                continue;
            }
            if (trim(substr($line, 0, $pos)) === $key) {
                return trim(substr($line, $pos + 1));
            }
        }
    }
    return $default;
}

/*
 * Admin password — set BRADTEC_ADMIN_PASSWORD in .env or as a server
 * environment variable. An empty value fails closed (login always rejected)
 * until it is configured, so the secret never lives in source code.
 */
define('ADMIN_PASSWORD', env_value('BRADTEC_ADMIN_PASSWORD', ''));

define('DATA_DIR', dirname(__DIR__) . '/data');

/* ------------------------------------------------------------------ */
/* JSON helpers                                                       */
/* ------------------------------------------------------------------ */

if (!isset($GLOBALS['bradtec_json_cache'])) {
    $GLOBALS['bradtec_json_cache'] = [];
}

/**
 * Read + decode a JSON data file. Results are memoized per request so files
 * read in several partials (e.g. company.json in head/header/footer) are
 * decoded only once. json_write() invalidates the cache.
 */
function json_read(string $file, $default = null) {
    if (array_key_exists($file, $GLOBALS['bradtec_json_cache'])) {
        return $GLOBALS['bradtec_json_cache'][$file];
    }
    $path = DATA_DIR . '/' . $file;
    if (!is_file($path)) {
        return $GLOBALS['bradtec_json_cache'][$file] = $default;
    }
    $raw = file_get_contents($path);
    if ($raw === false) {
        return $GLOBALS['bradtec_json_cache'][$file] = $default;
    }
    $data = json_decode($raw, true);
    return $GLOBALS['bradtec_json_cache'][$file] = ($data === null ? $default : $data);
}

/**
 * Atomically write JSON (tmp file + rename) so a crash can't corrupt data.
 * Returns true on success, or an error message string on failure.
 */
function json_write(string $file, $data) {
    $path = DATA_DIR . '/' . $file;
    $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    if ($json === false) {
        return 'Could not encode data.';
    }
    $tmp = $path . '.tmp';
    if (file_put_contents($tmp, $json, LOCK_EX) === false) {
        return 'Could not write file. Check that the data/ folder is writable.';
    }
    if (!@rename($tmp, $path)) {
        @unlink($tmp);
        return 'Could not save file. Check that the data/ folder is writable.';
    }
    @chmod($path, 0664);
    unset($GLOBALS['bradtec_json_cache'][$file]); // drop any stale cached copy
    return true;
}

/**
 * Hold a lock for the duration of the request so concurrent read-modify-write
 * cycles on the same file can't lose updates. Pass $exclusive=false (shared
 * lock) for pure reads. The lock is released automatically when the request
 * ends. Returns a handle, or null if locking is unavailable (callers proceed
 * without it rather than failing).
 */
function json_lock(string $file, bool $exclusive = true) {
    /* Handles are kept referenced for the whole request so PHP doesn't close
       the file (and release the lock) as soon as this function returns. */
    static $handles = [];
    static $shutdownRegistered = false;
    if (!$shutdownRegistered) {
        $shutdownRegistered = true;
        register_shutdown_function(function () use (&$handles) {
            foreach ($handles as $h) {
                @flock($h, LOCK_UN);
                @fclose($h);
            }
            $handles = [];
        });
    }
    $h = @fopen(DATA_DIR . '/' . $file . '.lock', 'c');
    if ($h === false) {
        return null;
    }
    if (!@flock($h, $exclusive ? LOCK_EX : LOCK_SH)) {
        @fclose($h);
        return null;
    }
    $handles[] = $h;
    return $h;
}

function e($value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

/**
 * json_encode() that is safe to embed directly inside an HTML <script> block.
 * JSON_HEX_TAG escapes < and > (\u003C / \u003E) so admin-entered values can
 * never terminate the script element with a stray </script>.
 */
function json_script_encode($data): string {
    return json_encode($data, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}

function json_response(array $payload, int $code = 200): void {
    http_response_code($code);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function read_json_body(): array {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw, true);
    return is_array($data) ? $data : [];
}

/* ------------------------------------------------------------------ */
/* Auth helpers                                                       */
/* ------------------------------------------------------------------ */
function is_logged_in(): bool {
    return !empty($_SESSION['bradtec_admin']);
}

function require_admin_api(): void {
    if (!is_logged_in()) {
        json_response(['ok' => false, 'error' => 'Not authorized'], 401);
    }
}

function load_company(): array {
    $c = json_read('company.json', []);
    $defaults = [
        'name' => SITE_NAME,
        'short_name' => 'BRADTEC',
        'tagline' => SITE_TAGLINE,
        'description' => '',
        'phone' => '',
        'email' => '',
        'whatsapp' => '',
        'address' => '',
        'hours' => '',
        'social' => ['facebook' => '#', 'instagram' => '#', 'linkedin' => '#', 'youtube' => '#', 'x' => '#'],
        'stats' => [],
    ];
    return array_merge($defaults, $c);
}

/* ------------------------------------------------------------------ */
/* Rate limiting (per-IP, sliding window, stored in data/)             */
/* ------------------------------------------------------------------ */

/**
 * Record + check a per-IP request against a sliding window. Returns true if
 * the request is allowed (the attempt is recorded), false if it should be
 * rejected with a 429. Records live in data/<bucket>.json.
 */
function rate_limit_allow(string $bucket, int $max, int $windowSeconds): bool {
    $attempts = json_read($bucket . '.json', []);
    if (!is_array($attempts)) {
        $attempts = [];
    }
    $ip  = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $now = time();
    $rec = (isset($attempts[$ip]) && is_array($attempts[$ip])) ? $attempts[$ip] : null;

    if ($rec === null || $now - (int)$rec['since'] > $windowSeconds) {
        /* First request, or the window has elapsed — start a fresh window */
        $rec = ['count' => 0, 'since' => $now];
    }

    if ($rec['count'] >= $max) {
        return false; // blocked — caller responds with 429
    }

    $rec['count']++;
    $attempts[$ip] = $rec;

    /* Drop stale entries so the file never grows without bound */
    foreach ($attempts as $k => $v) {
        if ($now - (int)($v['since'] ?? 0) > $windowSeconds) {
            unset($attempts[$k]);
        }
    }
    json_write($bucket . '.json', $attempts);
    return true;
}

/** Seconds remaining in this IP's current window (0 if none recorded). */
function rate_limit_retry_after(string $bucket, int $windowSeconds): int {
    $attempts = json_read($bucket . '.json', []);
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $rec = (isset($attempts[$ip]) && is_array($attempts[$ip])) ? $attempts[$ip] : null;
    if ($rec === null) {
        return 0;
    }
    return max(0, $windowSeconds - (time() - (int)$rec['since']));
}

/** Clear this IP's record for a bucket (e.g. after a successful login).
    Removes the file entirely when no records remain. */
function rate_limit_clear(string $bucket): void {
    $attempts = json_read($bucket . '.json', []);
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    if (isset($attempts[$ip]) && is_array($attempts[$ip])) {
        unset($attempts[$ip]);
        if ($attempts === []) {
            @unlink(DATA_DIR . '/' . $bucket . '.json');
        } else {
            json_write($bucket . '.json', $attempts);
        }
    }
}
