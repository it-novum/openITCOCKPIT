<?php
// Copyright (C) <2015-present>  <it-novum GmbH>
//
// This file is licensed under the terms of the openITCOCKPIT Enterprise Edition license agreement.
// The license agreement and license key were sent with the order confirmation.

namespace ChangecalendarModule\Lib;


use itnovum\openITCOCKPIT\Core\Dashboards\ModuleWidgetsInterface;

class Widgets implements ModuleWidgetsInterface {

    /**
     * @var array
     */
    private $ACL_PERMISSIONS = [];

    /**
     * @inheritDoc
     */
    public function __construct($ACL_PERMISSIONS) {
        $this->ACL_PERMISSIONS = $ACL_PERMISSIONS;
    }

    /**
     * @return array
     */
    public function getAvailableWidgets() {
        $widgets = [];
        $widgets[] = [
            'type_id'   => 90700,
            'title'     => __('Changecalendar'),
            'icon'      => 'fas fa-sitemap fa-fw fa-rotate-90',
            'directive' => 'changecalendar-widget',
            'width'     => 12,
            'height'    => 25
        ];

        return $widgets;
    }

}
