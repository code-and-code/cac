<?php

return [
    'public'   => false,
    'url'      => 'localhost',
    'timezone' => 'UTC',

    'database' => [

        'mysql' => [ 'host'    => '127.0.0.1',
            'dbname'   => '',
            'username' => '',
            'password' =>  ''
        ]
    ],

    'layout' =>  [ 'folder'     => '../App/views/',
        'tag'       => ['{','}'],
        'extension' => '.html.twig',
        'cache'     => '../../../storage/compilation_cache'
    ],

    'file'  => [
        'folder' => 'images'
    ],

    'cache'  => [ 'active' => true,
        'folder' => '../../../storage/cache',

    ],
    'log'  => [ 'file' => 'main.log',
                'folder' => '../storage/log',
    ],
    
     'providers'=> [
                   
    ]
];

