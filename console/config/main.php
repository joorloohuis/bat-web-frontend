<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'enableCoreCommands' => false,
    'controllerNamespace' => 'console\controllers',
    'controllerMap' => [
        'migrate' => 'yii\console\controllers\MigrateController',
    ],
    'modules' => [
        'gii' => 'yii\gii\Module',
        'rbac' => [
            'class' => 'common\modules\rbac\RBAC',
            'adminRole' => 'admin', // NB: admin will inherit all permissions from other roles
            'rbacModel' => include(__DIR__ . '/rbacmodel.php'),
        ],
    ],
    'components' => [
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
    ],
    'params' => $params,
];
