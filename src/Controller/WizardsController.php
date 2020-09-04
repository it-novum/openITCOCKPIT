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


use Cake\Http\Exception\MethodNotAllowedException;
use Cake\ORM\TableRegistry;

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

        $WizardsTable = TableRegistry::getTableLocator()->get('Wizards');
        $wizards = $WizardsTable->getAvailableWizards($this->PERMISSIONS);
        $this->set('wizards', $wizards);
        $this->viewBuilder()->setOption('serialize', ['wizards']);
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
}
