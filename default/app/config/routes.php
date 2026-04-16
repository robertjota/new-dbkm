<?php

return [
    'routes' => [
        '/' => 'principal',
        '/admin' => '/dashboard/index',
        '/dashboard' => '/dashboard/index',
        '/login' => '/sistema/login/entrar',
        '/logout' => '/sistema/login/salir',
        '/status' => 'pages/kumbia/status',
    ],
];
