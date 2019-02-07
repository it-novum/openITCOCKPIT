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

use Cake\ORM\TableRegistry;
use itnovum\openITCOCKPIT\Core\Http;
use itnovum\openITCOCKPIT\Core\Interfaces\CronjobInterface;
use itnovum\openITCOCKPIT\Core\PackagemanagerRequestBuilder;
use itnovum\openITCOCKPIT\Core\ValueObjects\License;

class VersionCheckTask extends AppShell implements CronjobInterface {

    public function execute($quiet = false) {
        $this->params['quiet'] = $quiet;
        $this->stdout->styles('green', ['text' => 'green']);
        $this->stdout->styles('red', ['text' => 'red']);
        $this->out('Checking for new openITCOCKPIT Version', false);


        $availableVersion = $this->getNewVersion();
        $this->saveNewVersion($availableVersion);

        $this->out('<green>   Ok</green>');
        $this->hr();
    }

    /**
     * @return string new Version as string or null
     */
    public function getNewVersion() {
        $this->loadModel('Register');

        /** @var $Proxy App\Model\Table\ProxiesTable */
        $Proxy = TableRegistry::getTableLocator()->get('Proxies');
        $Registers = TableRegistry::getTableLocator()->get('Registers');
        $License = $Registers->getLicense();
        $License = new License($License);
        $packagemanagerRequestBuilder = new PackagemanagerRequestBuilder(ENVIRONMENT, $License->getLicense());
        $http = new Http(
            $packagemanagerRequestBuilder->getUrl(),
            $packagemanagerRequestBuilder->getOptions(),
            $Proxy->getSettings()
        );

        //Send https request
        $http->sendRequest();

        $availableVersion = '???';

        if (!$http->error) {
            $data = json_decode($http->data);
            if (property_exists($data, 'version')) {
                $availableVersion = $data->version;
            }
        } else {
            //Force new line
            $this->out();
            $this->out('<red>' . $http->getLastError()['error'] . '</red>');
        }

        return $availableVersion;
    }

    /**
     * @param string $availableVersion
     */
    public function saveNewVersion($availableVersion) {
        $newConfig = sprintf($this->getConfigTemplate(), $availableVersion);
        $fileName = OLD_APP . 'Lib' . DS . 'AvailableVersion.php';
        file_put_contents($fileName, $newConfig);
    }

    /**
     * @return string
     */
    public function getConfigTemplate() {
        $fileName = OLD_APP . 'src' . DS . 'itnovum' . DS . 'openITCOCKPIT' . DS . 'Core' . DS . 'AvailableVersionTemplate.txt';

        return file_get_contents($fileName);
    }
}