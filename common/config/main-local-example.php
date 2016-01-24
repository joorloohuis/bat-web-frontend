<?php
return [
    'components' => [
        'db' => [ // MySQL example
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=<dbname>',
            'username' => '<user>',
            'password' => '<password>',
            'charset' => 'utf8',
        ],
        // 'db' => [ // PostgreSQL example
        //     'class' => 'yii\db\Connection',
        //     'dsn' => 'pgsql:host=localhost;dbname=bat-web-frontend',
        //     'username' => 'bat-ui',
        //     'password' => 'bat-ui',
        //     'charset' => 'utf8',
        //     'schemaMap' => [
        //         'pgsql'=> [
        //             'class'=>'yii\db\pgsql\Schema',
        //             'defaultSchema' => 'public',
        //         ]
        //     ],
        // ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => '<smtp-host>',
                'username' => '<smtp-user>',
                'password' => '<smtp-password>',
                'port' => '587',
                'encryption' => 'tls',
            ],
            'viewPath' => '@common/mail',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
    ],
];
