<?php
// Copyright (C) <2015-present>  <it-novum GmbH>
//
// This file is licensed under the terms of the openITCOCKPIT Enterprise Edition license agreement.
// The license agreement and license key were sent with the order confirmation.


namespace itnovum\openITCOCKPIT\MapModule\AngularAssets;

use itnovum\openITCOCKPIT\Core\AngularJS\AngularAssetsInterface;
use itnovum\openITCOCKPIT\Core\AngularJS\PluginAngularAssets;

class AngularAssets extends PluginAngularAssets implements AngularAssetsInterface {

    /**
     * @var array
     */
    protected $jsFiles = [

    ];

    protected $cssFiles = [
        '/css/MapModule.css'
    ];

    /**
     * @return array
     */
    public function getJsFiles() {
        return $this->_getJsFiles('map_module');
    }

    /**
     * @return array
     */
    public function getCssFiles() {
        return $this->_getCssFiles('map_module');
    }

    /**
     * @return array
     */
    public function getJsFilesOnDisk() {
        return $this->_getJsFilesOnDisk('MapModule');
    }

    /**
     * @return array
     */
    public function getCssFilesOnDisk() {
        return $this->_getCssFilesOnDisk('MapModule');
    }
}
