<?php
// Copyright (C) <2015-present>  <it-novum GmbH>
//
// This file is licensed under the terms of the openITCOCKPIT Enterprise Edition license agreement.
// The license agreement and license key were sent with the order confirmation.

namespace ChangecalendarModule\Lib;

use App\itnovum\openITCOCKPIT\Core\Permissions\DefaultRolePermissionsInterface;

class DefaultRolePermissions implements DefaultRolePermissionsInterface {

    /**
     * @inheritDoc
     */
    public function getDefaultRolePermissions() {
        return [
            'Viewer' => [
                'Changecalendars' => ['index', 'view', 'edit'],
            ]
        ];
    }
}
