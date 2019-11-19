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

use Cake\ORM\TableRegistry;
use itnovum\openITCOCKPIT\Core\Http;
use itnovum\openITCOCKPIT\Core\PackagemanagerRequestBuilder;
use itnovum\openITCOCKPIT\Core\RepositoryChecker;
use itnovum\openITCOCKPIT\Core\System\Health\LsbRelease;
use itnovum\openITCOCKPIT\Core\ValueObjects\License;

class PacketmanagerController extends AppController {
    public $layout = 'Admin.default';
    public $components = ['Session'];
    public $uses = ['Register'];

    public function index() {
        $this->Frontend->setJson('username', $this->Auth->user('full_name'));

        Configure::load('version');

        $openITCVersion = Configure::read('version');
        $this->set('openITCVersion', $openITCVersion);

        $installedModules = glob(OLD_APP . 'Plugin/*', GLOB_ONLYDIR);
        $installedModules = array_map(function ($file) {
            return basename($file);
        }, $installedModules);
        $this->set('installedModules', $installedModules);

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

        $this->set('RepositoryChecker', new RepositoryChecker());
        $this->set('LsbRelease', new LsbRelease());


        $http->sendRequest();
        if (!$http->error) {
            if (strlen($http->data) > 0) {
                //Es wurden Daten empfangen. Hoffen wir das es unser Array ist
                $data = json_decode($http->data);
                $this->set('data', $data);
            }
        } else {
            $this->setFlash('Error: ' . $http->getLastError()['error'], false);
        }
    }

    /**
     * Fake repository for the packages. The purpose of this action is development and/or testing.
     */
    public function getPackets() {
        $this->layout = 'json';
        $this->response->type('json');
        $fixedContent =
            '{
			   "current_version":"1.2",
			   "changelog":"<h1>Version: 1.2<\/h1><br \/><ul><li><span class=\"label label-danger\">New:<\/span> Graphtool implemented in JavaScript<\/li><li><span class=\"label label-danger\">New:<\/span> Host- and Servicebrowser<\/li><li>Bugfix: Problem while creating commands<\/li><\/ul><br \/><h1>Version: 0.1<\/h1><br \/><ul><li>openITCOCKPIT V3 first alpha version<\/li><\/ul>",
			   "modules":[
				  {
					 "name":"Autoreports",
					 "description":"Mit Hilfe dieses Modules k\u00f6nnen Sie automatische Reports erstellen und diese per E-Mail als PDF oder XLS versenden",
					 "author":"it-novum GmbH",
					 "licence":"IT-Novum Licence",
					 "version":"1.6",
					 "requires":"1.0",
					 "path":"autoreports\/download.php",
					 "tags":"autoreports, reports, reporting, pdf",
					 "url": "https://127.0.0.1/files/autoreports_v1.0.0_example.zip",
					 "check_for_update": false
				  },
				  {
					 "name":"Check_MK",
					 "description":"Erweitert openITCOCKPIT um Check_MK",
					 "author":"it-novum GmbH",
					 "licence":"IT-Novum Licence",
					 "version":"1.5",
					 "requires":"1.2",
					 "path":"check_mk\/download.php",
					 "tags":"check_mk, passive, mk, nagios",
					 "url": "https://127.0.0.1/files/autoreports_v0.0.1_example.zip",
					 "check_for_update": false
				  },
				  {
					 "name":"NagVis",
					 "description":"Ein Tool zur visuellen Darstellung der IT Landschaft in openITCOCKPIT",
					 "author":"it-novum GmbH",
					 "licence":"GPLv3 + Exception",
					 "version":"1.5",
					 "requires":"1.0",
					 "path":"nagvis\/download.php",
					 "tags":"nagvis, maps, visual, dashboard",
					 "url": "https://127.0.0.1/files/NagVis_v0.1.9_example.zip",
					 "check_for_update": false
				  },
				  {
					 "name":"Grapher",
					 "description":"Erlaubt die Darstellung von Graphen mit Hilfe von JavaScript",
					 "author":"it-novum GmbH",
					 "licence":"MIT",
					 "version":"1.5",
					 "requires":"1.0",
					 "path":"grapher\/download.php",
					 "tags":"grapher, graph",
					 "url": "https://127.0.0.1/files/grapher_example.zip",
					 "check_for_update": false
				  },
				  {
					 "name":"check_nrpe",
					 "description":"Nagios Remote Plugin Executor Plugin",
					 "author":"Debian Nagios Maintainer Group <pkg-nagios-devel@lists.alioth.debian.org>",
					 "licence":"GPLv2",
					 "version":"2.15-0ubuntu1",
					 "requires":"1.0",
					 "path":"grapher\/download.php",
					 "tags":"nrpe, check_nrpe",
					 "url": "https://127.0.0.1/files/check_nrpe.zip",
					 "check_for_update": false
				  }
			   ]
			}';
        $this->set('json', $fixedContent);
    }
}
