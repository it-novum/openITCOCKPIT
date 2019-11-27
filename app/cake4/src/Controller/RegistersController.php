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

declare(strict_types=1);

namespace App\Controller;.

use App\Model\Table\RegistersTable;
use Cake\ORM\Locator\LocatorAwareTrait;
use itnovum\openITCOCKPIT\Core\System\Gearman;

class RegistersController extends AppController {
    use LocatorAwareTrait;

    public function index() {
        $this->layout = 'blank';
        if (!$this->isApiRequest()) {
            //get also the state env for License input autocompletion
            $disableAutocomplete = ENVIRONMENT === Environments::PRODUCTION;
            $this->set('disableAutocomplete', $disableAutocomplete);

            //Ship HTML template
            return;
        }

        $TableLocator = $this->getTableLocator();

        /** @var RegistersTable $RegistersTable */
        $RegistersTable = $TableLocator->get('Registers');

        if ($this->request->is('get')) {
            $license = $RegistersTable->getLicense();
            $hasLicense = false;
            if ($license !== null) {
                $hasLicense = true;
            }

            $licenseResponse = null;
            if ($hasLicense) {
                $licenseResponse = $RegistersTable->checkLicenseKey($license['license']);
            }

            $this->set('hasLicense', $hasLicense);
            $this->set('license', $license);
            $this->set('licenseResponse', $licenseResponse);
            $this->set('_serialize', ['hasLicense', 'license', 'licenseResponse']);
            return;
        }

        if ($this->request->is('post')) {
            $license = $this->request->data('Registers.license');


            $licenseResponse = $RegistersTable->checkLicenseKey($license);
            if ($licenseResponse['success'] === true) {
                if (is_object($licenseResponse['license']) && property_exists($licenseResponse['license'], 'licence')) {
                    //license is valid

                    $licenseEntity = $RegistersTable->getLicenseEntity();
                    if (is_null($licenseEntity)) {
                        //no license yet
                        $licenseEntity = $RegistersTable->newEmptyEntity();
                    }

                    $licenseEntity = $RegistersTable->patchEntity($licenseEntity, ['license' => $license]);

                    $GearmanClient = new Gearman();
                    $GearmanClient->sendBackground('create_apt_config', ['key' => $license]);

                    $licenseEntity->set('apt', 1);
                    $licenseEntity->set('accepted', 1);
                    $RegistersTable->save($licenseEntity);

                    if ($licenseEntity->hasErrors()) {
                        $this->response->statusCode(400);
                        $this->set('error', $licenseEntity->getErrors());
                        $this->set('_serialize', ['error']);
                        return;
                    }

                    $this->set('licenseResponse', $licenseResponse);
                    $this->set('_serialize', ['licenseResponse']);
                    return;
                }
            }

            $this->set('licenseResponse', $licenseResponse);
            $this->set('_serialize', ['licenseResponse']);
        }
    }

}
