<?php
// Copyright (C) <2015>  <it-novum GmbH>
//
// This file is licensed under the terms of the openITCOCKPIT Enterprise Edition license agreement.
// The license agreement and license key were sent with the order confirmation.
class Agentcheck extends AppModel {
    public $belongsTo = [
        'Servicetemplate' => [
            'className'  => 'Servicetemplate',
            'foreignKey' => 'servicetemplate_id',
        ],
    ];

    public $validate = [
        'name'               => [
            'notBlank' => [
                'rule'     => 'notBlank',
                'message'  => 'This field cannot be left blank.',
                'required' => true,
            ],
            'isUnique' => [
                'rule'    => 'isUnique',
                'message' => 'This check name has already been taken.',
            ],
        ],
        'servicetemplate_id' => [
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