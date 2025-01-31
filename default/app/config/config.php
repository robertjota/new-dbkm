<?php

return [
    'application' => [
        'name' => 'KUMBIAPHP',
        'base_url' => 'https://kumbiaphp.test/',
        'production' => 'Off',
        'database' => 'development',
        'dbdate' => 'YYYY-MM-DD',
        'debug' => 'On',
        'log_exceptions' => 'On',
        'cache_driver' => 'file',
        'metadata_lifetime' => '+1 year',
        'namespace_auth' => 'default',
        'routes' => 'On',
        'template' => 'coreui',
    ],
    'custom' => [
        'app_mayus' => 'On',
        'app_update' => 'Off',
        'app_update_time' => '5 min',
        'app_version' => 2,
        'app_logger' => 'On',
        'app_local' => 'On',
        'app_ajax' => 'On',
        'datagrid' => 30,
        'login_exclusion' => 'root, admin',
        'type_reports' => 'html.printer',
        'timezone' => 'America/Caracas',
    ],
];
