<?php
// Copyright (C) <2015-present>  <it-novum GmbH>
//
// This file is licensed under the terms of the openITCOCKPIT Enterprise Edition license agreement.
// The license agreement and license key were sent with the order confirmation.

namespace itnovum\openITCOCKPIT\Core;


class LogentryConditions extends ListSettingsConditions {

    /**
     * @var array
     */
    protected $order = [
        'entry_time' => 'DESC'
    ];

    /**
     * @var string
     */
    protected $id;

    /**
     * @var bool
     */
    private $useLimit = true;


    /**
     * @param string $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param bool $value
     */
    public function setUseLimit($value) {
        $this->useLimit = (bool)$value;
    }

}
