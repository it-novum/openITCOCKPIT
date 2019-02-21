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
use itnovum\openITCOCKPIT\Core\ValueObjects\License;
use itnovum\openITCOCKPIT\Core\Views\UserTime;

class RegistersController extends AppController {
    use LocatorAwareTrait;

    public $layout = 'angularjs';
    public $components = ['GearmanClient'];

    public function index() {
        $this->layout = 'blank';

        if (!$this->isApiRequest()) {
            return;
        }

        $TableLocator = $this->getTableLocator();
        $Registers = $TableLocator->get('Registers');

        if ($this->request->is('post')) {
            $licenseValid = false;
            $licenseResponse = $Registers->checkLicenseKey($this->request->data['Registers']['license']);
            if (is_object($licenseResponse) && property_exists($licenseResponse, 'licence')) {
                //license is valid
                $licenseValid = true;
            }
            $licenseEntity = $Registers->getLicenseEntity();

            if (is_null($licenseEntity)) {
                //no license yet
                $licenseEntity = $Registers->newEntity();
            }
            $license = $this->request->data['Registers']['license'];

            $licenseEntity = $Registers->patchEntity($licenseEntity, ['license' => $license]);

            if (!empty($licenseValid)) {
                $this->GearmanClient->sendBackground('create_apt_config', ['key' => $license]);
                $licenseEntity->apt = true;

                if ($Registers->save($licenseEntity)) {
                    if ($this->isAngularJsRequest()) {
                        $id = $licenseEntity->id;
                        $this->set('_serialize', ['id']);
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
        if ($license == null) {
            //no license available
            $license = ['license' => []];
        }
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
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }
        $TableLocator = $this->getTableLocator();
        $Registers = $TableLocator->get('Registers');
        $response = $Registers->checkLicenseKey($license);

        if (is_object($response) && property_exists($response, 'licence')) {
            //License found and valid

            //rearrange expire date to user date
            $UserTime = new UserTime($this->Auth->user('timezone'), $this->Auth->user('dateformat'));
            $response->expire = $UserTime->format($response->expire);

            $license = $response;
            $this->set(compact('license'));
            $this->set('_serialize', ['license']);
            return;
        }

        if (is_null($response)) {
            $license = $response;
            $this->set(compact('license'));
            $this->set('_serialize', ['license']);
            return;
        }

        if (isset($response['error'])) {
            $error = $response;
            $this->set(compact('error'));
            $this->set('_serialize', ['error']);
            return;
        }
    }
}