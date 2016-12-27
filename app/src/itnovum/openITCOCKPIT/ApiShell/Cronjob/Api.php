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

namespace itnovum\openITCOCKPIT\ApiShell\Cronjob;

use itnovum\openITCOCKPIT\ApiShell\CoreApi;
use itnovum\openITCOCKPIT\ApiShell\Exceptions\RecordExistsExceptions;
use itnovum\openITCOCKPIT\ApiShell\Interfaces\ApiInterface;
use itnovum\openITCOCKPIT\ApiShell\OptionParser;

class Api extends CoreApi implements ApiInterface
{

    /**
     * @var OptionParser
     */
    private $optionParser;

    /**
     * @var array
     */
    private $data;

    public function setOptionsFromOptionParser(OptionParser $optionParser)
    {
        $this->optionParser = $optionParser;
        $this->data = $optionParser->getData();
    }

    public function dispatchRequest()
    {
        switch ($this->optionParser->getAction()) {
            case 'create_missing_cronjobs':
                $this->create_missing_cronjobs();
                break;
        }
    }

    /**
     * @throws \Exception
     */
    public function create_missing_cronjobs()
    {
        //Check if load cronjob exists
        if (!$this->Database->checkForCronjob('CpuLoad', 'Core')) {
            //Cron does not exists, so we create it
            $this->Database->add('CpuLoad', 'Core', 15);
        }

        //Check if version check cronjob exists
        if (!$this->Database->checkForCronjob('VersionCheck', 'Core')) {
            //Cron does not exists, so we create it
            $this->Database->add('VersionCheck', 'Core', 1440);
        }
    }

}