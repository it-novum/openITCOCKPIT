<?php
$config = [
    'general'     => [
        'version'   => '0.1',
        'site_name' => 'open source system monitoring',
    ],
    'attachments' => [
        'allowedExtensions' => ['doc', 'docx', 'xls', 'xlsx', 'png', 'jpg', 'jpeg', 'pdf', 'ppt', 'pptx'],
        'path'              => APP.'webroot/files/attachments/',
    ],
    'ckeditor'    => [
        'allowedExtensions' => ['png', 'jpg', 'jpeg'],
        'path'              => APP.'webroot/files/images/',
    ],
    'languages'   => [
        'en-us' => 'english',
    ],
    'paths'       => [
        'lessc' => [
            Environments::DEVELOPMENT => 'lessc',
            Environments::STAGING     => '/root/node_modules/less/bin/lessc',
            Environments::PRODUCTION  => '/root/node_modules/less/bin/lessc',
        ],
    ],
];