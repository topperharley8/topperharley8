<?php

/**
 * Egyszerű backup adat, ha nincs cache vagy parser hiba történik.
 */
function sheetcms_backup_data($tab)
{
    $fallback = [
        'service_list' => [],
    ];

    return $fallback[$tab] ?? [];
}
