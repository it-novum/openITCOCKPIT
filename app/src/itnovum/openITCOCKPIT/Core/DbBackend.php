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

namespace itnovum\openITCOCKPIT\Core;


use App\Lib\Exceptions\MissingDbBackendException;
use App\Lib\Interfaces\HostchecksTableInterface;
use App\Lib\Interfaces\HoststatusTableInterface;
use App\Lib\Interfaces\ServicestatusTableInterface;
use Cake\ORM\TableRegistry;

class DbBackend {

    /**
     * DbBackend constructor.
     */
    public function __construct() {
        $configFile = OLD_APP . 'Config' . DS . 'dbbackend.php';
        if (file_exists($configFile)) {
            \Configure::load('dbbackend');
            $this->backend = \Configure::read('dbbackend');
        } else {
            //Use default backend as fallback
            $this->backend = 'Statusengine3';
        }
    }

    /**
     * @return bool
     */
    public function isNdoUtils() {
        return $this->backend === 'Nagios';
    }

    /**
     * @return bool
     */
    public function isCrateDb() {
        return $this->backend === 'Crate';
    }

    /**
     * @return bool
     */
    public function isStatusengine3() {
        return $this->backend === 'Statusengine3';
    }

    /**
     * @return string
     */
    public function getBackendAsString() {
        return $this->backend;
    }

    /**
     * @return HoststatusTableInterface
     * @throws MissingDbBackendException
     */
    public function getHoststatusTable() {
        if ($this->isNdoUtils()) {
            /** @var $HoststatusTable HoststatusTableInterface */
            $HoststatusTable = TableRegistry::getTableLocator()->get('Statusengine2Module.Hoststatus');
            return $HoststatusTable;
        }

        if ($this->isCrateDb()) {
            throw new MissingDbBackendException('MissingDbBackendException');
        }

        if ($this->isStatusengine3()) {
            throw new MissingDbBackendException('MissingDbBackendException');
        }
    }

    /**
     * @return ServicestatusTableInterface
     * @throws MissingDbBackendException
     */
    public function getServicestatusTable() {
        if ($this->isNdoUtils()) {
            /** @var $ServicestatusTable ServicestatusTableInterface */
            $ServicestatusTable = TableRegistry::getTableLocator()->get('Statusengine2Module.Servicestatus');
            return $ServicestatusTable;
        }

        if ($this->isCrateDb()) {
            throw new MissingDbBackendException('MissingDbBackendException');
        }

        if ($this->isStatusengine3()) {
            throw new MissingDbBackendException('MissingDbBackendException');
        }
    }

    /**
     * @return HostchecksTableInterface
     * @throws MissingDbBackendException
     */
    public function getHostchecksTable() {
        if ($this->isNdoUtils()) {
            /** @var $HostchecksTable HostchecksTableInterface */
            $HostchecksTable = TableRegistry::getTableLocator()->get('Statusengine2Module.Hostchecks');
            return $HostchecksTable;
        }

        if ($this->isCrateDb()) {
            throw new MissingDbBackendException('MissingDbBackendException');
        }

        if ($this->isStatusengine3()) {
            throw new MissingDbBackendException('MissingDbBackendException');
        }
    }

}
