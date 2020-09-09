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

namespace App\Controller;


use App\Model\Table\WizardAssignmentsTable;
use Cake\Http\Exception\MethodNotAllowedException;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

/**
 * Class WizardsController
 * @package App\Controller
 */
class WizardsController extends AppController {

    public function index() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template
            return;
        }

        /** @var WizardAssignmentsTable $WizardAssignmentsTable */
        $WizardAssignmentsTable = TableRegistry::getTableLocator()->get('WizardAssignments');
        $wizards = $WizardAssignmentsTable->getAvailableWizards($this->PERMISSIONS);
        $this->set('wizards', $wizards);
        $this->viewBuilder()->setOption('serialize', ['wizards']);
    }

    public function assignments() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template
            return;
        }

        /** @var WizardAssignmentsTable $WizardAssignmentsTable */
        $WizardAssignmentsTable = TableRegistry::getTableLocator()->get('WizardAssignments');
        $wizards = $WizardAssignmentsTable->getAvailableWizards($this->PERMISSIONS);
        $wizardAssignments = [];

        foreach ($wizards as $key => $wizard) {
            if ($wizard['necessity_of_assignment'] === true) {
                if ($WizardAssignmentsTable->existsByUuidAndTypeId($wizard['uuid'], $wizard['type_id'])) {
                    $wizardAssignments[] = Hash::merge($wizards[$key], $WizardAssignmentsTable->getWizardByUuidForEdit($wizard['uuid']));
                }
            }
        }
        $this->set('wizardAssignments', $wizardAssignments);
        $this->viewBuilder()->setOption('serialize', ['wizardAssignments']);
    }


    public function hostConfiguration() {
        //Only ship HTML template
        return;
    }

    public function agent() {
        //Only ship HTML template
        return;
    }

    public function linuxserverssh() {
        //Only ship HTML template
        return;
    }

    public function wizardHostConfiguration() {
        //Only ship HTML template
        return;
    }

    public function validateInputFromAngular() {
        if (!$this->isAngularJsRequest() || !$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        $error = ['Host' => []];
        $data = $this->request->getData();
        if (!isset($data['Host']['id']) || is_null($data['Host']['id'])) {
            $error['Host']['id'] = __('This field cannot be left blank.');
        }

        if (!empty($error['Host'])) {
            $this->response = $this->response->withStatus(400);
            $this->set('success', false);
            $this->set('error', $error);
            $this->viewBuilder()->setOption('serialize', ['error', 'success']);
            return;
        }

        $this->set('success', true);
        $this->set('error', $error);
        $this->viewBuilder()->setOption('serialize', ['error', 'success']);
    }

    public function loadServicetemplatesByWizardType() {
        if (!$this->isApiRequest()) {
            throw new MethodNotAllowedException();
        }
        $type = $this->request->getQuery('type');
    }
}
