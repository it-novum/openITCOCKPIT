<?php

{{STATIC_FILE_HEADER}}

$config = [
    'graphite' => [
        'graphite_web_host' => '{{graphite_web_host}}',
        'graphite_web_port' => {{graphite_web_port}},
        'graphite_prefix'   => '{{graphite_web_prefix}}',
        'use_https'         => {{use_https}},
        'use_proxy'         => {{use_proxy}}
    ]
];
