<?php
/**
 * BRADTEC — image upload endpoint (admin only)
 * -------------------------------------------
 * Receives a multipart POST with a `file` field, validates it is an image,
 * saves it to assets/uploads/ with a unique name and returns the public URL.
 *
 * Example response: { "ok": true, "url": "/assets/uploads/img_20260101_120000_ab12cd34.jpg" }
 */

require __DIR__ . '/config.php';

require_admin_api();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(['ok' => false, 'error' => 'Method not allowed'], 405);
}

if (empty($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
    json_response(['ok' => false, 'error' => 'No file received'], 400);
}

$file = $_FILES['file'];
$tmp  = $file['tmp_name'];

/* Allowed formats (raster only — no SVG to avoid script/XML payloads) */
$ALLOWED = [
    'jpg'  => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'png'  => 'image/png',
    'gif'  => 'image/gif',
    'webp' => 'image/webp',
];

$ext = strtolower(pathinfo((string)$file['name'], PATHINFO_EXTENSION));
if (!isset($ALLOWED[$ext])) {
    json_response(['ok' => false, 'error' => 'Only JPG, PNG, GIF or WEBP images are allowed.'], 400);
}

/* Verify the actual file content is an image (best effort) */
$mime = function_exists('mime_content_type') ? mime_content_type($tmp) : (isset($file['type']) ? $file['type'] : '');
if ($mime && strpos($mime, 'image/') !== 0) {
    json_response(['ok' => false, 'error' => 'The uploaded file is not a valid image.'], 400);
}

/* Verify image dimensions and integrity with getimagesize (blocks exploits) */
$dims = @getimagesize($tmp);
if ($dims === false) {
    json_response(['ok' => false, 'error' => 'The uploaded file is not a valid image.'], 400);
}
/* Reject images larger than 8000x8000 to prevent memory exhaustion */
if ($dims[0] > 8000 || $dims[1] > 8000) {
    json_response(['ok' => false, 'error' => 'Image dimensions too large. Maximum is 8000x8000 pixels.'], 400);
}

/* Size limit: 5 MB */
$MAX_SIZE = 5 * 1024 * 1024;
if ((int)$file['size'] > $MAX_SIZE) {
    json_response(['ok' => false, 'error' => 'Image is too large. Maximum size is 5 MB.'], 400);
}

$dir = dirname(__DIR__) . '/assets/uploads';
if (!is_dir($dir) && !@mkdir($dir, 0755, true) && !is_dir($dir)) {
    json_response(['ok' => false, 'error' => 'Could not create the uploads directory. Check folder permissions.'], 500);
}

$name = 'img_' . date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
$dest = $dir . '/' . $name;

if (!move_uploaded_file($tmp, $dest)) {
    json_response(['ok' => false, 'error' => 'Could not save the uploaded file. Check that assets/uploads is writable.'], 500);
}

@chmod($dest, 0644);

json_response(['ok' => true, 'url' => '/assets/uploads/' . $name]);
