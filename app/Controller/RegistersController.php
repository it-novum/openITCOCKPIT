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

use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\TableRegistry;
use itnovum\openITCOCKPIT\Core\Http;
use itnovum\openITCOCKPIT\Core\PackagemanagerRequestBuilder;
use itnovum\openITCOCKPIT\Core\ValueObjects\License;
use itnovum\openITCOCKPIT\Core\Views\UserTime;

class RegistersController extends AppController {
    use LocatorAwareTrait;

    public $layout = 'angularjs';
    public $components = ['GearmanClient'];

    public function index() {
        $TableLocator = $this->getTableLocator();
        $Registers = $TableLocator->get('Registers');

        if ($this->request->is('post')) {
            //$this->request->data['Register']['id'] = 1;
            $Registers->get(1);
            $licenseValid = $this->checkLicense($this->request->data['Registers']['license']);

            $license = $Registers->getLicense();
            $apt = false;
            if (!empty($license)) {
                if ($this->request->data['Registers']['license'] == $license['license']) {
                    $this->GearmanClient->sendBackground('create_apt_config', ['key' => $license['license']]);
                    $apt = true;
                } else {
                    $apt = $license['apt'];
                }
            }

            $data = [
                'id'      => 1,
                'license' => $this->request->data['Registers']['license'],
                'apt'     => $apt
            ];
            $registersTable = TableRegistry::get('Registers');

            $register = $registersTable->newEntity($data);

            if (!empty($licenseValid)) {
                if ($Registers->save($register)) {
                    if ($this->isAngularJsRequest()) {
                        $id = $register->id;
                        $this->set('_serialize', ['id']);

                        $this->serializeId();

                        return;
                    } else {
                        $this->setFlash(__('License successfully added'));
                        $this->redirect(['action' => 'index']);
                    }
                }
                if ($this->isAngularJsRequest()) {
                    $this->serializeErrorMessage();

                    return;
                } else {
                    $this->setFlash(__('Could not add license'), false);
                }
            }

        }
    }

    public function loadLicense() {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        $TableLocator = $this->getTableLocator();
        $Registers = $TableLocator->get('Registers');
        $license = $Registers->getLicense();
        //get also the state env for License input autocompletion
        $env = ['productionEnv' => ENVIRONMENT === Environments::PRODUCTION];
        $license = Hash::merge($license, $env);
        $this->set(compact('license'));
        $this->set('_serialize', ['license']);
    }


    /**
     * @param $license License key
     * @return bool
     */
    public function checkLicense($license) {
        $TableLocator = $this->getTableLocator();
        $Proxies = $TableLocator->get('Proxies');

        if (empty($license) || !is_string($license)) {
            return false;
        }

        $prb = new PackagemanagerRequestBuilder(ENVIRONMENT, $license);
        $url = $prb->getUrlForLicenseCheck();
        //$url = 'https://172.16.2.243/licences/check/F7C6761F-263B-46FD-8879-92F7CDFB7BFB.json';
        $http = new Http(
            $url,
            $prb->getOptions(),
            $Proxies->getSettings()
        );

        $http->sendRequest();
        $error = $http->getLastError();

        if ($error) {
            if ($this->isAngularJsRequest()) {
                $this->set(compact('error'));
                $this->set('_serialize', ['error']);
                return;
            }
            return false;
        }

        $response = json_decode($http->data);
        $license = null;
        if (is_object($response)) {
            //wrong spelled "licence" comes from license server
            if (property_exists($response, 'licence')) {
                if (!empty($response->licence) && property_exists($response->licence, 'Licence')) {
                    if (!empty($response->licence->Licence) && strtotime($response->licence->Licence->expire) > time()) {
                        //license is valid
                        if ($this->isAngularJsRequest()) {
                            $license = $response->licence->Licence;
                            $UserTime = new UserTime($this->Auth->user('timezone'), $this->Auth->user('dateformat'));
                            $license->expire = $UserTime->format($license->expire);
                            $this->set(compact('license'));
                            $this->set('_serialize', ['license']);
                            return;
                        }
                        return true;
                    }
                }
            }
        }
        if ($this->isAngularJsRequest()) {
            $this->set(compact('license'));
            $this->set('_serialize', ['license']);
            return;
        }
        return false;
    }
/*
    public function check() {
        $TableLocator = $this->getTableLocator();
        $Registers = $TableLocator->get('Registers');
        $Proxies = $TableLocator->get('Proxies');
        $license = $Registers->getLicense();

        if (empty($license)) {
            $this->setFlash('Please enter a license key', false);
            $this->redirect(['action' => 'index']);
        }

        $License = new License($license);
        $packagemanagerRequestBuilder = new PackagemanagerRequestBuilder(ENVIRONMENT, $License->getLicense());
        $http = new Http(
            $packagemanagerRequestBuilder->getUrlForLicenseCheck(),
            $packagemanagerRequestBuilder->getOptions(),
            $Proxies->getSettings()
        );

        $http->sendRequest();
        $error = $http->getLastError();
        $response = json_decode($http->data);

        $isValide = false;
        $licence = null;

        debug($response);

        if (is_object($response)) {
            if (property_exists($response, 'licence')) {
                if (property_exists($response, 'licence')) {
                    if (!empty($response->licence) && property_exists($response->licence, 'Licence')) {
                        if (strtotime($response->licence->Licence->expire) > time()) {
                            $isValide = true;
                            $licence = $response->licence->Licence;
                            if ($license['apt'] == 0) {
                                $this->GearmanClient->sendBackground('create_apt_config', ['key' => $license['license']]);
                                $license['apt'] = 1;
                                $this->Register->save($license);
                            }
                        }
                    }
                }
            }
        }
        if ($isValide == false) {
            //The license is invalid, so we delete it again out of the database
            if (isset($license['id'])) {
                $this->Register->delete($license['id']);
            }
        }

        $this->set(compact('isValide', 'licence', 'error'));
    } */
}