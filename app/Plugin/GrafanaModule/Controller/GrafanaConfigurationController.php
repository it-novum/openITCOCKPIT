<?php
// Copyright (C) <2015>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//	This program is free software: you can redistribute it and/or modify
//	it under the terms of the GNU General Public License as published by
//	the Free Software Foundation, version 3 of the License.
//
//	This program is distributed in the hope that it will be useful,
//	but WITHOUT ANY WARRANTY; without even the implied warranty of
//	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//	GNU General Public License for more details.
//
//	You should have received a copy of the GNU General Public License
//	along with this program.  If not, see <http://www.gnu.org/licenses/>.
//

// 2.
//	If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//	under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//	License agreement and license key will be shipped with the order
//	confirmation.


class GrafanaConfigurationController extends GrafanaModuleAppController {

    public $layout = 'Admin.default';

    public $uses = [
        'Hostgroup',
        'Container',
        'GrafanaModule.GrafanaConfiguration',
        'GrafanaModule.GrafanaConfigurationHostgroupMembership'
    ];

    public $components = [
        'CustomValidationErrors'
    ];

    public function index() {
        $hostgroups = $this->Hostgroup->findList([
            'recursive' => -1,
            'contain' => [
                'Container'
            ],
            'order' => [
                'Container.name' => 'asc',
            ],
            'conditions' => [
                'Container.parent_id' => $this->MY_RIGHTS,
            ],
        ]);
        $customFieldsToRefill = [
            'GrafanaConfiguration' => [
                'use_https',
                'ignore_ssl_certificate'
            ]
        ];
        $this->CustomValidationErrors->checkForRefill($customFieldsToRefill);

        if ($this->request->is('post') || $this->request->is('put')) {
            $hostgroups = (is_array($this->request->data('GrafanaConfiguration.Hostgroup'))) ? $this->request->data('GrafanaConfiguration.Hostgroup') : [];
            $hostgroups_excluded = (is_array($this->request->data('GrafanaConfiguration.Hostgroup_excluded'))) ? $this->request->data('GrafanaConfiguration.Hostgroup_excluded') : [];

            $this->GrafanaConfiguration->set($this->request->data);
            if ($this->GrafanaConfiguration->validates()) {
                $this->request->data['GrafanaConfiguration']['id'] = 1;
                $this->request->data['GrafanaConfigurationHostgroupMembership'] = $this->GrafanaConfiguration->parseHostgroupMembershipData(
                    $hostgroups,
                    $hostgroups_excluded
                );
                /* Delete old hostgroup associations */
                $this->GrafanaConfigurationHostgroupMembership->deleteAll(true);
                if ($this->GrafanaConfiguration->saveAll($this->request->data)) {
                    $this->setFlash(__('Configuration saved successfully'));
                } else {
                    $this->setFlash(__('Data could not be saved'), false);
                }
                $this->redirect([
                    'controller' => 'grafana_configuration',
                    'action' => 'index',
                    'plugin' => 'grafana_module'
                ]);
            }
        }
        $grafanaConfiguration = $this->GrafanaConfiguration->find('first', [
            'recursive' => -1,
            'contain' => [
                'GrafanaConfigurationHostgroupMembership'
            ]
        ]);

        if (!empty($grafanaConfiguration['GrafanaConfigurationHostgroupMembership'])) {
            $grafanaConfiguration['GrafanaConfiguration']['Hostgroup'] = Hash::combine($grafanaConfiguration['GrafanaConfigurationHostgroupMembership'], '{n}[excluded=0].hostgroup_id', '{n}[excluded=0].hostgroup_id');
            $grafanaConfiguration['GrafanaConfiguration']['Hostgroup_excluded'] = Hash::combine($grafanaConfiguration['GrafanaConfigurationHostgroupMembership'], '{n}[excluded=1].hostgroup_id', '{n}[excluded=1].hostgroup_id');
        }
        $this->request->data = Hash::merge($grafanaConfiguration, $this->request->data);

        $this->set(compact(['grafanaConfiguration', 'hostgroups']));
    }
}
