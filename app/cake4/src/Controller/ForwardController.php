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

use Cake\Http\Exception\NotFoundException;
use Cake\Utility\Hash;

class ForwardController extends AppController {
    public $layout = 'Admin.default';

    public $uses = [
        'Host',
        'Hosttemplate',
        'Timeperiod',
        'Command',
        'Contact',
        'Contactgroup',
        'Container',
        'Customvariable',
        'Hostescalation',
        'Hostgroup',
        'Service',
        'Servicetemplate',
        'Serviceescalations',
        'Servicegroup',
        'Hostdependency',
        'Servicedependency',
    ];

    public $components = ['Uuid'];

    public function index() {

        $_options = [
            'uuid'   => null,
            'action' => 'edit',
            'model'  => 'unknown',
        ];
        $options = Hash::merge($_options, $this->request->params['named']);


        if ($options['model'] == 'unknown') {
            $this->Uuid->buildCache();
            $this->uuidCache = $this->Uuid->getCache();
            if (isset($this->uuidCache[$options['uuid']])) {
                //Redirect to to object
                $this->redirect('/' . strtolower(Inflector::pluralize($this->uuidCache[$options['uuid']]['ModelName'])) . '/' . $options['action'] . '/' . $this->uuidCache[$options['uuid']]['id']);
            } else {
                if (isset($this->request->params['named']['exception']) && $this->request->params['named']['exception'] == 'false') {
                    $this->setFlash(__('No entry found'), false);
                    $this->redirect($this->referer());
                }

                throw new NotFoundException(__('Object not found'));
            }
        } else {
            $object = $this->{$options['model']}->findByUuid($options['uuid']);
            if (!empty($object)) {
                if (isset($this->request->params['named']['exception']) && $this->request->params['named']['exception'] == 'false') {
                    $this->setFlash(__('No entry found'), false);
                    $this->redirect($this->referer());
                }

                $this->redirect('/' . strtolower(Inflector::pluralize($options['model'])) . '/' . $options['action'] . '/' . $object[$options['model']]['id']);
            } else {
                throw new NotFoundException(__('Object not found'));
            }
        }
    }

}
