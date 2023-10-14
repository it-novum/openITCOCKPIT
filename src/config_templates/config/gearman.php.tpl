<?php

{{STATIC_FILE_HEADER}}

return [
    'gearman' => [
        'address'    => '{{address}}',
        'port'       => {{port}},
        'pidfile'    => '{{pidfile}}',
        'worker'     => {{worker}},
        'timeout'    => 1000,
    ]
];
