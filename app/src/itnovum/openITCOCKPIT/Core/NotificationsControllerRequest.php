<?php
// Copyright (C) <2015>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//  This program is free software: you can redistribute it and/or modify
//  it under the terms of the GNU General Public License as published by
//  the Free Software Foundation, version 3 of the License.
//
//  This program is distributed in the hope that it will be useful,
//  but WITHOUT ANY WARRANTY; without even the implied warranty of
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//  GNU General Public License for more details.
//
//  You should have received a copy of the GNU General Public License
//  along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
// 2.
//  If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//  under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//  License agreement and license key will be shipped with the order
//  confirmation.

namespace itnovum\openITCOCKPIT\Core;


class NotificationsControllerRequest extends ControllerListSettingsRequest {

    protected $showServiceNotifications = null;

    /**
     * @return bool
     */
    public function showHostNotifications() {
        if (isset($this->requestParameters['Listsettings']['view'])) {
            return $this->requestParameters['Listsettings']['view'] == 'hostOnly';
        }
        return true;
    }

    /**
     * @return bool
     */
    public function showServiceNotifications() {
        if (isset($this->requestParameters['Listsettings']['view']) && $this->showServiceNotifications === null) {
            return $this->requestParameters['Listsettings']['view'] == 'serviceOnly';
        }

        if ($this->showServiceNotifications !== null) {
            return $this->showServiceNotifications;
        }

        return false;
    }

    /**
     * @param bool $value
     */
    public function setShowServiceNotifications($value) {
        $this->showServiceNotifications = $value;
    }

    /**
     * @return array
     */
    public function getRequestSettingsForListSettings() {
        $view = 'hostOnly';
        if ($this->showServiceNotifications()) {
            $view = 'serviceOnly';
        }

        return [
            'limit' => $this->getLimit(),
            'from'  => date('d.m.Y H:i', $this->getFrom()),
            'to'    => date('d.m.Y H:i', $this->getTo()),
            'view'  => $view
        ];
    }
}
