<?php
/**
 * Local development router — mirrors the production .htaccess clean URLs.
 * Run with:  php -S localhost:8080 router.php
 *
 * Security: requests containing ".." path segments (raw or percent-encoded)
 * are rejected with a 404 so the router can never serve files outside the
 * webroot (e.g. a .env placed one level above the site).
 */
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

/* Refuse path traversal: ".." segments after decoding, or NUL bytes. */
$decoded = rawurldecode((string)$path);
if ($path === false
    || strpos($decoded, "\0") !== false
    || preg_match('#(^|/)\.\.(/|$)#', $decoded)) {
    http_response_code(404);
    require __DIR__ . '/404.php';
    return true;
}

if ($path === '/' || $path === '') {
    require __DIR__ . '/index.php';
    return true;
}

$file = __DIR__ . $path;

if ($path !== '/' && is_file($file)) {
    return false; // serve real files (css, js, images) as-is
}

if (is_file($file . '.php')) {
    require $file . '.php';
    return true;
}

// Extension-less dir index
if (is_dir($file) && is_file($file . '/index.php')) {
    require $file . '/index.php';
    return true;
}

http_response_code(404);
require __DIR__ . '/404.php';
return true;
