<?php
// Copyright (C) <2015>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//  This program is free software: you can redistribute it and/or modify
//  it under the terms of the GNU General Public License as published by
//  the Free Software Foundation, version 3 of the License.
//
//  This program is distributed in the hope that it will be useful,
//  but WITHOUT ANY WARRANTY; without even the implied warranty of
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//  GNU General Public License for more details.
//
//  You should have received a copy of the GNU General Public License
//  along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
// 2.
//  If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//  under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//  License agreement and license key will be shipped with the order
//  confirmation.

namespace itnovum\openITCOCKPIT\Core\System\Health;


class CpuLoad {

    private $load1 = null;

    private $load5 = null;

    private $load15 = null;

    private $cores = 0;

    private $model = 'Unknown';

    public function __construct() {
    }

    /**
     * @return array
     */
    public function getLoad() {
        if (file_exists('/proc/loadavg')) {
            $load = file('/proc/loadavg');
            $load = explode(' ', $load[0]);

            $this->load1 = $load[0];
            $this->load5 = $load[1];
            $this->load15 = $load[2];
        }

        return [
            'load1'  => (float)$this->load1,
            'load5'  => (float)$this->load5,
            'load15' => (float)$this->load15
        ];
    }

    /**
     * @return int|null
     */
    public function getNumberOfCores() {
        if($this->cores > 0){
            return $this->cores;
        }

        if (file_exists('/proc/cpuinfo')) {
            foreach(file('/proc/cpuinfo') as $line){
                if(preg_match('/^processor/m', $line)){
                    $this->cores++;
                }

                if(preg_match('/^model name/m', $line)){
                    $model = explode("model name\t: ", $line);
                    if(isset($model[1])){
                        $this->model = trim($model[1]);
                    }
                }
            }
        }

        return $this->cores;
    }

    public function getModel(){
        if($this->model === 'Unknown'){
            $this->getNumberOfCores();
        }
        return $this->model;
    }

    /**
     * @return array
     */
    public function getLoadForSystemHealth() {
        $load = $this->getLoad();
        $load['cores'] = $this->getNumberOfCores();
        $load['state'] = 'ok';

        if ($load['load15'] > $load['cores'] - 2 && $load['load15'] > 1) {
            $load['state'] = 'warning';
        }
        if ($load['load15'] >= $load['cores'] && $load['load15'] > 1) {
            $load['state'] = 'critical';
        }
        return $load;
    }

}
