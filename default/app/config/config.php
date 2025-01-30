<?php

/**
 * KumbiaPHP Web Framework
 * Parámetros de configuracion de la aplicacion
 */
return [
    'application' => [
        /**
         * name: es el nombre de la aplicacion
         */
        'name' => 'KUMBIAPHP',
        /**
         * base_url: es la url base de la aplicacion
         */
        'base_url' => 'https://kumbiaphp.test/',
        /**
         * production: Aplicación en modo de producción (On/Off)
         */
        'production' => 'Off',
        /**
         * database: base de datos a utilizar
         */
        'database' => 'development',
        /**
         * dbdate: formato de fecha por defecto de la aplicacion
         */
        'dbdate' => 'YYYY-MM-DD',
        /**
         * debug: muestra los errores en pantalla (On/Off)
         */
        'debug' => 'On',
        /**
         * log_exceptions: muestra las excepciones en pantalla (On/Off)
         */
        'log_exceptions' => 'On',
        /**
         * cache_template: descomentar para habilitar cache de template
         */
        //'cache_template' => 'On',
        /**
         * cache_driver: driver para la cache (file, sqlite, memsqlite)
         */
        'cache_driver' => 'file',
        /**
         * metadata_lifetime: tiempo de vida de la metadata en cache
         */
        'metadata_lifetime' => '+1 year',
        /**
         * namespace_auth: espacio de nombres por defecto para Auth
         */
        'namespace_auth' => 'default',
        /**
         * routes: descomentar para activar routes en routes.php
         */
        'routes' => '1',

        /**
         * template: habilitar template "nombre del template"
         * default: default
         * si no se habilita o no existe, se usara el template por defecto
         */
        'template' => 'coreui',
    ],

    'custom' => [
        'app_mayus' => 'On',
        'app_update' => 'Off',
        'app_update_time' => '5 min',
        'app_version' => '2.0',
        'app_logger' => 'On',
        'app_local' => 'On',
        'app_ajax' => 'On',
        'datagrid' => '30',
        'login_exclusion' => 'root, admin',
        'type_reports' => 'html.printer',
        'timezone' => 'America/Caracas',
    ]
];
