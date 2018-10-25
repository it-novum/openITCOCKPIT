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

use itnovum\openITCOCKPIT\Core\Http;
use itnovum\openITCOCKPIT\Core\PackagemanagerRequestBuilder;
use itnovum\openITCOCKPIT\Core\ValueObjects\License;

class RegistersController extends AppController {
    public $layout = 'Admin.register';
    public $components = ['GearmanClient'];
    public $uses = ['Register', 'Proxy'];

    public function index() {
        if ($this->request->is('post')) {
            $this->request->data['Register']['id'] = 1;
            if ($this->Register->save($this->request->data)) {
                //$this->setFlash('License added successfully');
                $this->redirect(['action' => 'check']);
            } else {
                $this->setFlash('Could not add license', false);
            }
        }

        $license = $this->Register->find('first');

        if (!empty($license)) {
            //$this->redirect(array('action' => 'check'));
        }
        $this->set('licence', $license);
    }

    public function check() {
        $license = $this->Register->find('first');
        if (empty($license)) {
            $this->setFlash('Please enter a license key', false);
            $this->redirect(['action' => 'index']);
        }

        $License = new License($this->Register->find('first'));
        $packagemanagerRequestBuilder = new PackagemanagerRequestBuilder(ENVIRONMENT, $License->getLicense());
        $http = new Http(
            $packagemanagerRequestBuilder->getUrlForLicenseCheck(),
            $packagemanagerRequestBuilder->getOptions(),
            $this->Proxy->getSettings()
        );

        $http->sendRequest();
        $error = $http->getLastError();
        $response = json_decode($http->data);

        $isValide = false;
        $licence = null;

        if (is_object($response)) {
            if (property_exists($response, 'licence')) {
                if (property_exists($response, 'licence')) {
                    if (!empty($response->licence) && property_exists($response->licence, 'Licence')) {
                        if (strtotime($response->licence->Licence->expire) > time()) {
                            $isValide = true;
                            $licence = $response->licence->Licence;
                            if ($license['Register']['apt'] == 0) {
                                $this->GearmanClient->sendBackground('create_apt_config', ['key' => $license['Register']['license']]);
                                $license['Register']['apt'] = 1;
                                $this->Register->save($license);
                            }
                        }
                    }
                }
            }
        }
        if ($isValide == false) {
            //The lincense is invalide, so we delete it again out of the database
            if (isset($license['Register']['id'])) {
                $this->Register->delete($license['Register']['id']);
            }
        }

        $this->set(compact('isValide', 'licence', 'error'));
    }
}