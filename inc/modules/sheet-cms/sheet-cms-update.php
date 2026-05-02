<?php

require_once __DIR__ . '/sheet-cms-config.php';

header('Content-Type: application/json; charset=utf-8');

global $sheetcms_config;

$key = isset($_GET['key']) ? (string)$_GET['key'] : '';
$tab = isset($_GET['tab']) ? (string)$_GET['tab'] : '';

if ($key === '' || $key !== (string)$sheetcms_config['secret']) {
    http_response_code(403);
    echo json_encode(['ok' => false, 'error' => 'invalid_key']);
    exit;
}

if ($tab === '' || empty($sheetcms_config['tabs'][$tab])) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'invalid_tab']);
    exit;
}

$tabConfig = $sheetcms_config['tabs'][$tab];
$sheetId = (string)$sheetcms_config['sheet_id'];
$gid = (string)$tabConfig['gid'];
$cacheFile = (string)$tabConfig['cache'];

$url = 'https://docs.google.com/spreadsheets/d/' . rawurlencode($sheetId) . '/export?format=csv&gid=' . rawurlencode($gid);
$csv = @file_get_contents($url);

if ($csv === false || trim($csv) === '') {
    http_response_code(502);
    echo json_encode(['ok' => false, 'error' => 'download_failed']);
    exit;
}

$dir = dirname($cacheFile);
if (!is_dir($dir)) {
    mkdir($dir, 0775, true);
}

if (@file_put_contents($cacheFile, $csv) === false) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'cache_write_failed']);
    exit;
}

echo json_encode([
    'ok' => true,
    'tab' => $tab,
    'cache' => $cacheFile,
    'bytes' => strlen($csv),
]);
