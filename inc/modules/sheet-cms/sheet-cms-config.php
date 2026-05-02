<?php

// Sheet CMS alap beállítások.
$sheetcms_config = [
    'sheet_id' => '11Dmn31aek8ldCFgMCQ42lToULuLjeanW7vmpzjxDmlM',
    'secret'   => 'CHANGE_THIS_SECRET',
    'tabs'     => [
        'service_list' => [
            'gid'    => '0',
            'cache'  => __DIR__ . '/cache/service-list.csv',
            'parser' => 'service_list',
        ],
    ],
];
