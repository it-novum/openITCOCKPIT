<?php
// Copyright (C) <2015>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//     This program is free software: you can redistribute it and/or modify
//     it under the terms of the GNU General Public License as published by
//     the Free Software Foundation, version 3 of the License.
//
//     This program is distributed in the hope that it will be useful,
//     but WITHOUT ANY WARRANTY; without even the implied warranty of
//     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//     GNU General Public License for more details.
//
//     You should have received a copy of the GNU General Public License
//     along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
// 2.
//     If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//     under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//     License agreement and license key will be shipped with the order
//     confirmation.

namespace itnovum\openITCOCKPIT\Core\AngularJS\Request;

use itnovum\openITCOCKPIT\Filter\BaseFilter;

class NotificationsLogRequest extends AngularRequest {
    protected $ServiceStateField = 'NotificationServices.state';

    protected $HostStateField = 'NotificationHosts.state';

    protected $filters = [
        'host'    => [
            'like' => [
                'NotificationHostsLog.output',
                'Hosts.name',
            ]
        ],
        'service' => [
            'like' => [
                'NotificationServicesLog.output',
                'Hosts.name',
                'servicename'
            ]
        ]
    ];


    public function getPeriod() {
        if ($this->queryHasField('not_older_than')) {
            //period in minutes tlook in the past for notifications
            $value = $this->getQueryFieldValue('not_older_than');
            if ($value) {
                return time() - ((int)$value * 60);
            }
        }
        if ($this->queryHasField('from')) {
            $value = strtotime($this->getQueryFieldValue('from'));
            if ($value) {
                return $value;
            }
        }
        return time() - (3600 * 24 * 30);
    }


    public function getServiceFilters() {
        $Filter = new BaseFilter($this->getRequest());
        return $Filter->getConditionsByFilters($this->filters['service']);
    }

    public function getHostFilters() {
        $Filter = new BaseFilter($this->getRequest());
        return $Filter->getConditionsByFilters($this->filters['host']);
    }

}