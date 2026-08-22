<?php
require __DIR__ . '/config.php';

require_admin_api();

$RESOURCES = ['services', 'products', 'properties', 'projects', 'team', 'company', 'inquiries'];

$resource = isset($_GET['resource']) ? (string)$_GET['resource'] : '';
$action   = isset($_GET['action'])   ? (string)$_GET['action']   : 'list';

if (!in_array($resource, $RESOURCES, true)) {
    json_response(['ok' => false, 'error' => 'Unknown resource'], 400);
}

$file = $resource . '.json';

/* Serialize concurrent read-modify-write cycles on this resource so two
   simultaneous saves can't lose updates. Shared lock for pure reads;
   the lock is released automatically when the request ends. */
json_lock($file, $_SERVER['REQUEST_METHOD'] !== 'GET');

/* ---------------- company is a single object ---------------- */
if ($resource === 'company') {
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && $action === 'get') {
        json_response(['ok' => true, 'data' => load_company()]);
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'save') {
        $b = read_json_body();
        $b = array_intersect_key($b, array_flip([
            'name', 'short_name', 'tagline', 'description', 'who_we_are',
            'mission', 'vision', 'values', 'stats', 'phone', 'phone2',
            'email', 'whatsapp', 'address', 'hours', 'social', 'map_embed',
        ]));
        // Merge with existing data so partial updates never wipe fields
        $b = array_merge(load_company(), $b);
        $write = json_write($file, $b);
        if ($write !== true) {
            json_response(['ok' => false, 'error' => $write], 500);
        }
        json_response(['ok' => true, 'data' => $b]);
    }
    json_response(['ok' => false, 'error' => 'Unknown action'], 400);
}

/* ---------------- list resources ---------------- */
$data = json_read($file, []);
if (!is_array($data)) {
    $data = [];
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET' && $action === 'list') {
    json_response(['ok' => true, 'data' => $data]);
}

if ($method === 'GET' && $action === 'get') {
    $id = isset($_GET['id']) ? (string)$_GET['id'] : '';
    foreach ($data as $item) {
        if (isset($item['id']) && $item['id'] === $id) {
            json_response(['ok' => true, 'data' => $item]);
        }
    }
    json_response(['ok' => false, 'error' => 'Not found'], 404);
}

if ($method === 'POST' && $action === 'create') {
    $b = read_json_body();
    $b['id'] = $resource === 'services'
        ? slugify(isset($b['name']) ? $b['name'] : 'service') . '-' . substr(bin2hex(random_bytes(3)), 0, 4)
        : ($resource === 'projects' ? 'proj-' : ($resource === 'properties' ? 'prop-' : 'pr-')) . substr(bin2hex(random_bytes(4)), 0, 8);
    $data[] = $b;
    $write = json_write($file, $data);
    if ($write !== true) {
        json_response(['ok' => false, 'error' => $write], 500);
    }
    json_response(['ok' => true, 'data' => $b]);
}

if ($method === 'POST' && $action === 'update') {
    $b = read_json_body();
    $id = isset($b['id']) ? (string)$b['id'] : '';
    if ($id === '') {
        json_response(['ok' => false, 'error' => 'Missing id'], 400);
    }
    foreach ($data as $i => $item) {
        if (isset($item['id']) && $item['id'] === $id) {
            $data[$i] = array_merge($item, $b);
            $write = json_write($file, $data);
            if ($write !== true) {
                json_response(['ok' => false, 'error' => $write], 500);
            }
            json_response(['ok' => true, 'data' => $data[$i]]);
        }
    }
    json_response(['ok' => false, 'error' => 'Not found'], 404);
}

/* Resources that support the trash (soft delete) flow */
$TRASHABLE = ['services', 'products', 'properties', 'projects'];

if ($method === 'POST' && $action === 'delete') {
    $b = read_json_body();
    $id = isset($b['id']) ? (string)$b['id'] : '';
    if ($id === '') {
        json_response(['ok' => false, 'error' => 'Missing id'], 400);
    }
    foreach ($data as $i => $item) {
        if (isset($item['id']) && $item['id'] === $id) {
            /* Soft delete: move a copy to trash_<resource>.json so it can be restored */
            if (in_array($resource, $TRASHABLE, true)) {
                $trash = json_read('trash_' . $file, []);
                if (!is_array($trash)) {
                    $trash = [];
                }
                $item['deleted_at'] = date('Y-m-d H:i:s');
                $trash[] = $item;
                if (json_write('trash_' . $file, $trash) !== true) {
                    json_response(['ok' => false, 'error' => 'Could not write to trash'], 500);
                }
            }
            array_splice($data, $i, 1);
            $write = json_write($file, $data);
            if ($write !== true) {
                json_response(['ok' => false, 'error' => $write], 500);
            }
            json_response(['ok' => true]);
        }
    }
    json_response(['ok' => false, 'error' => 'Not found'], 404);
}

if ($method === 'GET' && $action === 'trash_list') {
    $trash = json_read('trash_' . $file, []);
    json_response(['ok' => true, 'data' => is_array($trash) ? $trash : []]);
}

if ($method === 'POST' && $action === 'restore') {
    $b = read_json_body();
    $id = isset($b['id']) ? (string)$b['id'] : '';
    if ($id === '') {
        json_response(['ok' => false, 'error' => 'Missing id'], 400);
    }
    $trash = json_read('trash_' . $file, []);
    if (!is_array($trash)) {
        $trash = [];
    }
    foreach ($trash as $i => $item) {
        if (isset($item['id']) && $item['id'] === $id) {
            array_splice($trash, $i, 1);
            unset($item['deleted_at']);
            $data[] = $item;
            if (json_write('trash_' . $file, $trash) !== true || json_write($file, $data) !== true) {
                json_response(['ok' => false, 'error' => 'Could not restore item'], 500);
            }
            json_response(['ok' => true, 'data' => $item]);
        }
    }
    json_response(['ok' => false, 'error' => 'Not found in trash'], 404);
}

if ($method === 'POST' && $action === 'purge') {
    $b = read_json_body();
    $id = isset($b['id']) ? (string)$b['id'] : '';
    if ($id === '') {
        json_response(['ok' => false, 'error' => 'Missing id'], 400);
    }
    $trash = json_read('trash_' . $file, []);
    if (!is_array($trash)) {
        $trash = [];
    }
    foreach ($trash as $i => $item) {
        if (isset($item['id']) && $item['id'] === $id) {
            array_splice($trash, $i, 1);
            if (json_write('trash_' . $file, $trash) !== true) {
                json_response(['ok' => false, 'error' => 'Could not delete item'], 500);
            }
            json_response(['ok' => true]);
        }
    }
    json_response(['ok' => false, 'error' => 'Not found in trash'], 404);
}

if ($method === 'POST' && $action === 'empty_trash') {
    if (json_write('trash_' . $file, []) !== true) {
        json_response(['ok' => false, 'error' => 'Could not empty trash'], 500);
    }
    json_response(['ok' => true]);
}

/* ------------ inquiries: extra actions ------------ */
if ($resource === 'inquiries' && $method === 'POST' && $action === 'mark_read') {
    $b = read_json_body();
    $id = isset($b['id']) ? (string)$b['id'] : '';
    foreach ($data as $i => $item) {
        if (isset($item['id']) && $item['id'] === $id) {
            $data[$i]['read'] = true;
            $write = json_write($file, $data);
            if ($write !== true) {
                json_response(['ok' => false, 'error' => $write], 500);
            }
            json_response(['ok' => true]);
        }
    }
    json_response(['ok' => false, 'error' => 'Not found'], 404);
}

json_response(['ok' => false, 'error' => 'Unknown action'], 400);

function slugify(string $s): string {
    $s = strtolower(trim($s));
    $s = preg_replace('/[^a-z0-9]+/', '-', $s);
    $s = trim($s, '-');
    return $s !== '' ? $s : 'item';
}
