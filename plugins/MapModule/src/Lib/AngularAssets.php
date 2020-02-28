<?php
// Copyright (C) <2015-present>  <it-novum GmbH>
//
// This file is licensed under the terms of the openITCOCKPIT Enterprise Edition license agreement.
// The license agreement and license key were sent with the order confirmation.


namespace itnovum\openITCOCKPIT\MapModule\AngularAssets;

use itnovum\openITCOCKPIT\Core\AngularJS\AngularAssetsInterface;

class AngularAssets implements AngularAssetsInterface {

    /**
     * @var array
     */
    private $jsFiles = [

    ];

    private $cssFiles = [
        'map_module/css/MapModule.css'
    ];

    /**
     * @return array
     */
    public function getJsFiles() {
        return $this->jsFiles;
    }

    /**
     * @return array
     */
    public function getCssFiles() {
        return $this->cssFiles;
    }
}
