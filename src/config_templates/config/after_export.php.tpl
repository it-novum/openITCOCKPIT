<?php

{{STATIC_FILE_HEADER}}

return [
    'after_export' => [
        'SSH' => [

            //Username via the SSH connection is established
            'username'        => '{{username}}',

            //Path to your ssh private key file
            'private_key'     => '{{private_key}}',

            //Path to your ssh public key file
            'public_key'      => '{{public_key}}',

            //Command to restart remote monitoring engine
            'restart_command' => '{{restart_command}}',

            //Use rsync or PHP SSH lib to copy data
            'use_rsync'       => true,

            /**
             * A command that will be executed on the remote host
             * Be careful with this option
             * Example:
             * 'remote_command' => [
             *        'whoami',
             *        'echo 1 >> /tmp/after_export'
             * ]
             **/
            'remote_command'  => [],

            //Remote SSH port
            'port'            => {{remote_port}},
        ],

        'REMOTE' => [
            //Path on the remote system were config files will be copied to
            //With ending / !!!!!!
            'path' => '/opt/openitc/nagios/etc/',
        ]
    ]
];
