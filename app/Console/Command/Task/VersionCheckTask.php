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

use \itnovum\openITCOCKPIT\Core\Http;

class VersionCheckTask extends AppShell{

	public function execute($quiet = false){
		$this->params['quiet'] = $quiet;
		$this->stdout->styles('green', ['text' => 'green']);
		$this->stdout->styles('red',   ['text' => 'red']);
		$this->out('Checking for new openITCOCKPIT Version', false);


		$availableVersion = $this->getNewVersion();
		$this->saveNewVersion($availableVersion);

		$this->out('<green>   Ok</green>');
		$this->hr();
	}

	/**
	 * @return string new Version as string or null
	 */
	public function getNewVersion(){
		$this->loadModel('Register');
		$this->loadModel('Proxy');

		$license = $this->Register->find('first');
		if(ENVIRONMENT === Environments::DEVELOPMENT){
			if(!empty($license)){
				$url = 'http://172.16.2.87/modules/fetch/'.$license['Register']['license'].'.json';
			}else{
				$url = 'http://172.16.2.87/modules/fetch/.json';
			}

			$options = [
				'CURLOPT_SSL_VERIFYPEER' => false,
				'CURLOPT_SSL_VERIFYHOST' => false,
			];
			$http = new Http($url, $options, $this->Proxy->getSettings());

		}else{
			if(!empty($license)){
				$url = 'https://packagemanager.it-novum.com/modules/fetch/'.$license['Register']['license'].'.json';
			}else{
				$url = 'https://packagemanager.it-novum.com/modules/fetch/.json';
			}

			$http = new Http($url, [], $this->Proxy->getSettings());
		}

		//Send https request
		$http->sendRequest();

		$availableVersion = '???';

		if(!$http->error){
			$data = json_decode($http->data);
			if(property_exists($data, 'version')){
				$availableVersion = $data->version;
			}
		}else{
			//Force new line
			$this->out();
			$this->out('<red>'.$http->getLastError()['error'].'</red>');
		}
		return $availableVersion;
	}

	/**
	 * @param string $availableVersion
	 */
	public function saveNewVersion($availableVersion){
		$newConfig = sprintf($this->getConfigTemplate(), $availableVersion);
		$fileName = APP . 'Lib' . DS . 'AvailableVersion.php';
		file_put_contents($fileName, $newConfig);
	}

	/**
	 * @return string
	 */
	public function getConfigTemplate(){
		$fileName = APP . 'src' . DS . 'itnovum' . DS . 'openITCOCKPIT' . DS . 'Core' . DS . 'AvailableVersionTemplate.txt';
		return file_get_contents($fileName);
	}
}