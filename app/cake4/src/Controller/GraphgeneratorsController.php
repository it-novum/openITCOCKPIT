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

use itnovum\openITCOCKPIT\Core\DbBackend;
use itnovum\openITCOCKPIT\Core\PerfdataBackend;
use itnovum\openITCOCKPIT\Perfdata\PerfdataLoader;


/**
 * Class GraphgeneratorsController
 * @property Servicestatus $Servicestatus
 * @property PerfdataBackend $PerfdataBackend
 * @property DbBackend $DbBackend
 */
class GraphgeneratorsController extends AppController {

    public $layout = 'blank';

    /**
     * New method for AngularJS
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getPerfdataByUuid() {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        $hostUuid = $this->request->query('host_uuid');
        $serviceUuid = $this->request->query('service_uuid');
        $hours = $this->request->query('hours');
        $start = (int)$this->request->query('start');
        $end = (int)$this->request->query('end');
        $jsTimestamp = (bool)$this->request->query('jsTimestamp');
        $gauge = $this->request->query('gauge');

        $PerfdataLoader = new PerfdataLoader($this->DbBackend, $this->PerfdataBackend);
        if (is_numeric($hours)) {
            $hours = (int)$hours;
            $start = time() - ($hours * 3600);
            $end = time();
        }

        if ($start === null || $end === null) {
            $start = time() - (3 * 3600);
            $end = time();
        }

        try {
            $performance_data = $PerfdataLoader->getPerfdataByUuid($hostUuid, $serviceUuid, $start, $end, $jsTimestamp, 'avg', $gauge);
            $this->set('performance_data', $performance_data);
            $this->viewBuilder()->setOption('serialize', ['performance_data']);

        } catch (\Exception $e) {
            error_log($e->getMessage());
            $performance_data[] = [
                'datasource' => [],
                'data'       => []
            ];
        }


        $this->set('performance_data', $performance_data);
        $this->viewBuilder()->setOption('serialize', ['performance_data']);

    }
}
