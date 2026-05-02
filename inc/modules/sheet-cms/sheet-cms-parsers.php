<?php

/**
 * CSV sorokból service_list struktúra.
 */
function sheetcms_parse_service_list(array $rows)
{
    $items = [];

    foreach ($rows as $row) {
        if (empty($row) || !is_array($row)) {
            continue;
        }

        $category = trim((string)($row[0] ?? ''));
        $text     = trim((string)($row[1] ?? ''));
        $price    = trim((string)($row[2] ?? ''));
        $filtersRaw = trim((string)($row[3] ?? ''));

        // Üres sorokat kihagyjuk.
        if ($category === '' && $text === '' && $price === '' && $filtersRaw === '') {
            continue;
        }

        $filters = [];
        if ($filtersRaw !== '') {
            $parts = array_map('trim', explode(',', $filtersRaw));
            $filters = array_values(array_filter($parts, static function ($value) {
                return $value !== '';
            }));
        }

        $items[] = [
            'category' => $category,
            'text'     => $text,
            'price'    => $price,
            'filters'  => $filters,
        ];
    }

    return $items;
}
