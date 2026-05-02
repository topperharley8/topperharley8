<?php

require_once __DIR__ . '/sheet-cms-config.php';
require_once __DIR__ . '/sheet-cms-parsers.php';
require_once __DIR__ . '/sheet-cms-backup.php';

/**
 * CSV fájl beolvasása sorokra bontva.
 */
function sheetcms_read_csv_rows($filePath)
{
    if (!is_file($filePath) || !is_readable($filePath)) {
        return false;
    }

    $rows = [];
    if (($handle = fopen($filePath, 'r')) === false) {
        return false;
    }

    while (($data = fgetcsv($handle)) !== false) {
        $rows[] = $data;
    }

    fclose($handle);
    return $rows;
}

/**
 * Tab alapú adatlekérő.
 */
function sheetcms_get($tab)
{
    global $sheetcms_config;

    if (empty($sheetcms_config['tabs'][$tab])) {
        return sheetcms_backup_data($tab);
    }

    $tabConfig = $sheetcms_config['tabs'][$tab];
    $csvRows = sheetcms_read_csv_rows($tabConfig['cache']);

    if ($csvRows === false) {
        return sheetcms_backup_data($tab);
    }

    $parserName = (string)($tabConfig['parser'] ?? '');
    $parserFunction = 'sheetcms_parse_' . $parserName;

    if (!function_exists($parserFunction)) {
        return sheetcms_backup_data($tab);
    }

    $parsed = $parserFunction($csvRows);

    if (!is_array($parsed)) {
        return sheetcms_backup_data($tab);
    }

    return $parsed;
}
