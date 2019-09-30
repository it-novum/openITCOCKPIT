<?php
$config = [
    'general'     => [
        'version'   => '0.1',
        'site_name' => 'open source system monitoring',
    ],
    'attachments' => [
        'allowedExtensions' => ['doc', 'docx', 'xls', 'xlsx', 'png', 'jpg', 'jpeg', 'pdf', 'ppt', 'pptx'],
        'path'              => OLD_APP . 'webroot/files/attachments/',
    ],
    'ckeditor'    => [
        'allowedExtensions' => ['png', 'jpg', 'jpeg'],
        'path'              => OLD_APP . 'webroot/files/images/',
    ],
    'languages'   => [
        'en_US' => 'english',
        'de_DE' => 'german',
    ],
    'paths'       => [
        'lessc' => [
            Environments::DEVELOPMENT => 'lessc',
            Environments::STAGING     => '/root/node_modules/less/bin/lessc',
            Environments::PRODUCTION  => '/root/node_modules/less/bin/lessc',
        ]
    ]
];
