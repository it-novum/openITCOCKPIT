<?php
// Copyright (C) <2015-present>  <it-novum GmbH>
//
// This file is licensed under the terms of the openITCOCKPIT Enterprise Edition license agreement.
// The license agreement and license key were sent with the order confirmation.


namespace itnovum\openITCOCKPIT\ChangecalendarModule\AngularAssets;


use itnovum\openITCOCKPIT\Core\AngularJS\AngularAssetsInterface;
use itnovum\openITCOCKPIT\Core\AngularJS\PluginAngularAssets;

class AngularAssets extends PluginAngularAssets implements AngularAssetsInterface {

    /**
     * @var array
     */
    protected $jsFiles = [
    ];

    /**
     * @var array
     */
    protected $cssFiles = [
        '/css/app.css'
    ];

    /**
     * @inheritDoc
     */
    public function getCssFiles() {
        return $this->_getCssFiles('changecalendar_module');
    }

    /**
     * @inheritDoc
     */
    public function getJsFilesOnDisk() {
        return $this->_getJsFilesOnDisk('ChangecalendarModule');
    }

    /**
     * @inheritDoc
     */
    public function getCssFilesOnDisk() {
        return $this->_getCssFilesOnDisk('ChangecalendarModule');
    }

    /**
     * @inheritDoc
     */
    public function getJsFiles() {
        return [];
    }

}
