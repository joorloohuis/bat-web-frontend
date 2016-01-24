<?php

$config = [
    'components' => [
        'request' => [
            // insert a secret key in the cookieValidationKey (if it is empty)
            'cookieValidationKey' => 'REPLACEME',
        ],
    ],
];

return $config;
