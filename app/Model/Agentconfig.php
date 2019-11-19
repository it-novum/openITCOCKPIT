<?php
// Copyright (C) <2015>  <it-novum GmbH>
//
// This file is licensed under the terms of the openITCOCKPIT Enterprise Edition license agreement.
// The license agreement and license key were sent with the order confirmation.
class Agentconfig extends AppModel {
    public $belongsTo = [
        'Host' => [
            'className'  => 'Host',
            'foreignKey' => 'host_id',
        ],
    ];

    public $validate = [
        'host_id' => [
            'notBlank' => [
                'rule'     => 'notBlank',
                'message'  => 'This field cannot be left blank.',
                'required' => true,
            ],
            'numeric'  => [
                'rule'    => 'numeric',
                'message' => 'Please enter a number.',
            ],
        ],
    ];
}