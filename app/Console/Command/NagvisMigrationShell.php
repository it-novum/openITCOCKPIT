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

use itnovum\openITCOCKPIT\Core\Http;

App::uses('Folder', 'Utility');
App::uses('HttpSocket', 'Network/Http');
App::import('Controller', 'MapModule.Maps');
App::import('Model', 'Host');
App::import('Model', 'Service');
App::import('Model', 'Hostgroup');
App::import('Model', 'Servicegroup');
App::import('Model', 'MapModule.Map');
App::import('Model', 'MapModule.MapUpload');

class NagvisMigrationShell extends AppShell {

    /* @var object Host Instance */
    private $host = null;
    /* @var object Service Instance */
    private $service = null;
    /* @var object Hostgroup Instance */
    private $hostgroup = null;
    /* @var object Servicegroup Instance */
    private $servicegroup = null;
    /* @var object Map Instance */
    private $map = null;
    /* @var string last error message from object resolving */
    private $lastError = null;
    /* @var array mapping for the iconsets */
    private $iconsetMap = null;
    /* @var array iconsets which are obsolete */
    private $obsoleteIconsets = null;
    /* @var maps which are existing in the v3 database */
    private $availableMaps = null;
    /* @var array holds the remote server login data */
    private $hostData = null;

    public function main() {
        if (!$this->checkForSSH2Installed()) {
            $msg = 'On Ubuntu you will get the extension by installing the libssh2-php package';
            $this->error('SSH2 not found!', 'Please install the SSH2 PHP package!', $msg);
        }

        //map old iconsets which should be replaced to new iconsets
        //newIconset => [obsoleteIconset_1, obsoleteIconset_2, ... obsoleteIconset_n]
        $this->iconsetMap = [
            'std_mini_32px' => ['std_medium', 'std_small', 'std_small_sack', 'std_medium_sack', 'std_big_sack'],
            'std_mid_64px'  => ['std_big'],
        ];
        //iconsets which are not needed anymore
        $this->obsoleteIconsets = [
            'configerror',
            'back',
        ];

        $this->stdout->styles('success', ['text' => 'green']);
        $this->out('Welcome to the Nagvis Migration!');

        $hostData = $this->getHostData();
        $this->hostData = $hostData;
        $path = $this->configFilesPath();
        $this->hr(1);


        $session = $this->connectRemoteServer($hostData);

        /*
          get config files
         */
        $this->out('Getting config files');
        $cfgPath = $path . 'etc' . DS . 'maps' . DS;
        //receive a file list
        $configFileList = $this->getFileList('getConfigfiles.php');

        //check download directory
        $pluginPath = OLD_APP . 'Plugin' . DS . 'MapModule' . DS . 'webroot' . DS;
        $dirName = 'NagvisMaps';
        $cfgDownloadDir = $pluginPath . $dirName;
        //create download dir if there is no one
        ($this->checkConfigFilesDir($cfgDownloadDir)) ?: $this->createDownloadDirectory($cfgDownloadDir);
        //download the files
        $configFilesReceived = $this->getFiles($session, $cfgPath, $configFileList, $cfgDownloadDir);

        /*
          get background images
         */
        $this->out('Getting Background images');
        $bgPath = $path . DS . 'share' . DS . 'userfiles' . DS . 'images' . DS . 'maps' . DS;
        //receive a file list
        $bgImgList = $this->getFileList('getBackgrounds.php');

        //remove the default change_me background image from the list
        $foundKey = array_search('change_me.png', $bgImgList);
        if (isset($foundKey)) {
            unset($bgImgList[$foundKey]);
        }
        //download the files
        $this->getFiles($session, $bgPath, $bgImgList, $cfgDownloadDir);

        $destination = $pluginPath . 'img' . DS . 'backgrounds' . DS;

        if ($this->checkDir($destination)) {
            $this->moveToDestination($bgImgList, $cfgDownloadDir, $destination);
            $this->triggerThumbnailCreation($destination, $bgImgList);
        }

        /*
          get iconsets
         */
        $this->out('Getting Iconsets');
        $iconsetPath = $path . DS . 'share' . DS . 'userfiles' . DS . 'images' . DS . 'iconsets' . DS;
        //receive a file list
        $iconsetList = $this->getFileList('getIconsets.php');
        $iconsetDir = $cfgDownloadDir . DS . 'iconsets';
        ($this->checkConfigFilesDir($iconsetDir)) ?: $this->createDownloadDirectory($iconsetDir);
        //download the files
        $this->getFiles($session, $iconsetPath, $iconsetList, $iconsetDir);
        //sort every icon into its folder
        $this->sortList($iconsetList, $iconsetDir);
        //convert the images to png
        $this->convert($iconsetDir);
        //build list of iconsets which will be replaced by a new one
        $toSkip = [];
        foreach ($this->iconsetMap as $iconsets) {
            foreach ($iconsets as $iconset) {
                $toSkip[] = $iconset;
            }
        }
        //merge array of obsolete iconsets with iconsets that will be replaced through a new from the module
        $toSkip = Hash::merge($toSkip, $this->obsoleteIconsets);
        //move all iconsets to the new module
        $this->moveDirRecursively($iconsetDir, $pluginPath . 'img' . DS . 'items' . DS, $toSkip);

        /*
          get stateless icons (shapes)
         */
        $this->out('Getting Shapes');
        $shapesPath = $path . DS . 'share' . DS . 'userfiles' . DS . 'images' . DS . 'shapes' . DS;
        //receive a file list
        $shapesList = $this->getFileList('getIcons.php');
        $shapesDir = $cfgDownloadDir . DS . 'shapes';
        ($this->checkConfigFilesDir($shapesDir)) ?: $this->createDownloadDirectory($shapesDir);
        //download the files
        $this->getFiles($session, $shapesPath, $shapesList, $shapesDir);
        $destination = $pluginPath . 'img' . DS . 'icons' . DS;
        //check if the target directory is existing
        if ($this->checkDir($destination)) {
            $this->moveToDestination($shapesList, $shapesDir, $destination);
        }

        //create Object instances
        $this->host = new Host();
        $this->service = new Service();
        $this->hostgroup = new Hostgroup();
        $this->servicegroup = new Servicegroup();
        $this->MapUpload = new MapUpload();
        $this->map = new Map();

        $this->availableMaps = $this->getMapNames();

        //transform the config files
        if ($configFilesReceived) {
            $this->startFiletransform($configFileList, $cfgDownloadDir);
        }

        //cleanup the obsolete data
        $this->cleanupData($session, $cfgDownloadDir);

    }

    /**
     * get the Host information of the remote server where the nagvis maps are located
     * @author Maximilian Pappert <maximilian.pappert@it-novum.com>
     * @return Array    hostname, user and password of the server
     */
    protected function getHostData() {
        $host = $this->in('Please Enter the Hostname OR the IP Adress of the openITCOCKPIT v2 Server');
        $user = $this->in('Please Enter a valid SSH user on ' . $host);
        $pass = $this->in('Please Enter the Password for user ' . $user . ' on ' . $host);
        $frontendUser = $this->in('Please Enter a valid Frontend user on ' . $host);
        $frontendPass = $this->in('Please Enter the Password for user ' . $frontendUser . ' on ' . $host);
        $https = $this->in('Using SSL for Frontend Login ?', ['y', 'n'], 'n');
        $sslVerification = '';
        if ($https == 'y') {
            $sslVerification = $this->in('Disable SSL verification? (mostly needed with self-signed certificates)', ['y', 'n'], 'n');
        }


        return [
            'host'            => $host,
            'user'            => $user,
            'pass'            => $pass,
            'frontendUser'    => $frontendUser,
            'frontendPass'    => $frontendPass,
            'https'           => $https,
            'sslVerification' => $sslVerification,
        ];
    }

    /**
     * get the filepath where the config files are located
     * @author Maximilian Pappert <maximilian.pappert@it-novum.com>
     * @return String    the path
     */
    protected function configFilesPath() {
        $path = 'opt/openitc/nagios/share/3rd/nagvis/';
        $correctPath = $this->in('Is this path to the nagvis module correct? ' . $path, ['y', 'n'], 'y');
        if ($correctPath == 'n') {
            return $path = $this->in('Please Enter the correct path');
        }

        return $path;
    }

    /**
     * Connect to a remote server
     * @author Maximilian Pappert <maximilian.pappert@it-novum.com>
     *
     * @param  Array $hostData the Hostname or IP, username and password
     *
     * @return Resource            the session resource
     */
    protected function connectRemoteServer($hostData) {
        $this->out('<info>Trying to connect to Remote Host using ' . $hostData['user'] . '@' . $hostData['host'] . '</info>');
        try {
            if (@$session = ssh2_connect($hostData['host'], 22)) {
                $this->out('<info>Connection established!</info>');
                $session = $this->remoteAuth($session, $hostData['user'], $hostData['pass']);

                return $session;
            } else {
                throw new Exception('Connection failed! cannot connect to the server');
            }
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }
    }

    /**
     * Authenticate on the remote server
     * @author Maximilian Pappert <maximilian.pappert@it-novum.com>
     *
     * @param  Resource $session the Session resource from the ssh2_connect
     * @param  String $user username
     * @param  String $pass password
     *
     * @return Resource            the session resource
     */
    protected function remoteAuth($session, $user, $pass) {
        try {
            if (@ssh2_auth_password($session, $user, $pass)) {
                $this->out('<info>Authentication succeeded!</info>');

                return $session;
            } else {
                throw new Exception('Authentication failed! it seems that these credentials are wrong');
            }
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }
    }

    /**
     * get a List of existing files in the given path on the remote Host
     * @author Maximilian Pappert <maximilian.pappert@it-novum.com>
     *
     * @param  String $filename the filename of the php script which should be triggered at the v2 side
     *
     * @return Array                the list of existing files
     */
    protected function getFileList($filename) {
        try {
            $user = $this->hostData['frontendUser'];
            $pass = $this->hostData['frontendPass'];
            $host = $this->hostData['host'];
            $https = $this->hostData['https'];
            $noSSLVerify = $this->hostData['sslVerification'];
            $protocol = 'http';
            $options = [];
            if ($https == 'y') {
                $protocol = 'https';
                if ($noSSLVerify == 'y') {
                    $options = [
                        'CURLOPT_SSL_VERIFYPEER' => false,
                        'CURLOPT_SSL_VERIFYHOST' => 0
                    ];
                }
            }

            $url = $protocol . '://' . $user . ':' . $pass . '@' . $host . '/openitc/main/' . $filename;
            $http = new Http($url, $options);
            $http->sendRequest();
            if (!empty($http->data)) {
                return json_decode($http->data);
            } else {
                throw new Exception($http->getLastError()['error']);
            }

        } catch (Exception $e) {
            $this->error($e->getMessage());
        }
    }

    /**
     * get the config files from the remote Host
     * @author Maximilian Pappert <maximilian.pappert@it-novum.com>
     *
     * @param  Resource $session the session resource
     * @param  String $path the path string
     * @param  Array $fileList the config file list
     *
     * @return Bool               true if the transfer is complete
     */
    protected function getFiles($session, $path, $fileList, $downloadDir) {
        $a = [
            "Measuring the cable length to fetch your data... ",
            "Warming up Large Hadron Collider...",
            "Elf down! We're cloning the elf that was supposed to get you the data. Please wait.",
            "Do you suffer from ADHD? Me neith- oh look a bunny... What was I doing again? Oh, right. Here we go.",
        ];
        $this->out('<info>' . $a[rand(0, 3)] . '</info>');
        usleep(1000000);

        $this->out('<info>Starting Filetransfer</info>');
        $count = 1;
        $fileAmount = sizeof($fileList);
        foreach ($fileList as $key => $file) {
            try {
                if (@ssh2_scp_recv($session, '/' . $path . '/' . $file, $downloadDir . '/' . $file)) {
                    $this->show_download_status($count, $fileAmount, 60);
                    usleep(100000);
                    $count++;
                } else {
                    echo PHP_EOL;
                    throw new Exception('Error getting File ' . $file);
                }
            } catch (Exception $e) {
                $this->out('<warning>' . $e->getMessage() . '</warning>');
            }
        }
        echo PHP_EOL;
        $this->out('<info>Filetransfer complete!</info>');

        return true;
    }

    /**
     * Iterates though the file list and triggers the transformation
     * @author Maximilian Pappert <maximilian.pappert@it-novum.com>
     *
     * @param  Array $fileList Array of every file to transform
     * @param  String $folder folder where the files are located
     *
     * @return void
     */
    protected function startFiletransform($fileList, $folder) {
        $this->out('<info>Starting File Transformation</info>');
        foreach ($fileList as $key => $file) {
            $this->out('Processing file ' . $file);
            $fileData = $this->transformFileContentToArray($folder . '/' . $file);
            if ($fileData == false) {
                $this->out('<error> ...Transform Failed!</error>');
            } else {
                $mapname = preg_replace('/(\..*)/', '', $file);
                if (in_array($mapname, $this->availableMaps)) {
                    if ($data = $this->transformDataForV3($mapname, $fileData)) {
                        $this->saveNewData($data);
                        $this->out('<success> ...Complete!</success>');
                    } else {
                        $this->out('<error> ...Save to Database Failed!</error>');
                    }
                } else {
                    $this->out('<info>Skipping file ' . $file . ' cause there is no such map in the Databse</info>');
                }
            }
            $this->hr(1);
        }
        $this->out('<info>File Transformation Complete!</info>');
    }

    /**
     * get all available maps from the v3 system
     * @author Maximilian Pappert <maximilian.pappert@it-novum.com>
     * @return Array with all maps
     */
    protected function getMapNames() {
        $mapnames = $this->map->find('all', [
            'recursive' => -1,
            'fields'    => [
                'Map.name',
            ],
        ]);
        $mapnames = Hash::extract($mapnames, '{n}.Map.name');

        return $mapnames;
    }

    /**
     * save the map data from the config files into the v3 Database
     * @author Maximilian Pappert <maximilian.pappert@it-novum.com>
     *
     * @param  Array $data the Data to send
     *
     * @return void
     */
    protected function saveNewData($data) {


        try {
            if ($this->map->saveAll($data)) {
                $this->out('<success>Data successfully Saved!</success>');
            } else {
                $this->out('<error>Could not save data!</error>');
                throw new Exception($this->Map->validationErrors);
            }
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }
    }

    /**
     * transform the config file content into the new form for v3
     * @author Maximilian Pappert <maximilian.pappert@it-novum.com>
     *
     * @param  String $mapname name of the Map
     * @param  Array $data config file array content
     *
     * @return the new datastructure for v3
     */
    protected function transformDataForV3($mapname, $data) {
        $mapData = [];
        if (!isset($data['global'][0]['iconset'])) {
            $data['global'][0]['iconset'] = 'missing';
        }
        foreach ($data as $key => $items) {
            foreach ($items as $item) {
                $currentData = [];
                switch ($key) {
                    case 'global':
                        //contains background and grid(but this option is not be implemented in the map Module)
                        $mapId = $this->map->find('first', [
                            'recursive'  => -1,
                            'conditions' => [
                                'Map.name' => $mapname,
                            ],
                            'fields'     => [
                                'Map.id',
                                'Map.name',
                                'Map.title',
                            ],
                            'contain'    => [
                                'Container' => [
                                    'fields' => [
                                        'Container.id',
                                    ],
                                ],
                            ],
                        ]);
                        $currentData = [
                            'id'         => $mapId['Map']['id'],
                            'name'       => $mapname, // neccesary for assigning the background image correctly
                            'title'      => $mapId['Map']['title'],
                            'background' => empty($item['map_image']) ? '' : $item['map_image'],
                        ];
                        $mapData['Map'] = $currentData;
                        $mapData['Map']['container_id'] = Hash::extract($mapId, 'Container.{n}.id');
                        break;
                    case 'host':
                        $hostId = $this->resolveHostname($item['host_name']);
                        //host not found
                        if (empty($hostId)) {
                            continue;
                        }
                        $viewType = $this->getViewType((!isset($item['view_type'])) ? 'icon' : $item['view_type']); //icon or line
                        $currentData = [
                            'object_id' => $hostId, // must be resolved
                            'x'         => $item['x'],
                            'y'         => $item['y'],
                            'type'      => 'host',
                            'iconset'   => $this->getNewIconset((!isset($item['iconset'])) ? $data['global'][0]['iconset'] : $item['iconset']),
                        ];

                        if ($viewType == 'Mapline') {
                            $x = explode(',', $item['x']);
                            $y = explode(',', $item['y']);
                            $currentData = [
                                'object_id' => $hostId, //must be resolved from hostId
                                'startX'    => $x[0],
                                'endX'      => $x[1],
                                'startY'    => $y[0],
                                'endY'      => $y[1],
                                'type'      => 'host',
                                'iconset'   => 'std_line',
                                'gadget'    => (isset($gadget)) ? $gadget : 'null',
                            ];
                        }

                        $mapData[$viewType][] = $currentData;

                        break;
                    case 'service':
                        //skip commented definitions
                        if (isset($item['#host_name']) || isset($item['#service_description'])) {
                            continue;
                        }

                        $ids = $this->resolveServicename($item['host_name'], $item['service_description']);
                        //host or service not found
                        if (empty($ids)) {
                            continue;
                        }

                        $viewType = $this->getViewType((!isset($item['view_type'])) ? 'icon' : $item['view_type']);// gadget, icon or line
                        //if gadget add gadget => gadgettype
                        $gadget = false;
                        if ($viewType == 'Mapgadget') {
                            $gadget = $this->getGadget($item['gadget_url']);
                        }

                        $currentData = [
                            'object_id' => $ids['Service']['id'], //must be resolved from hostId
                            'x'         => $item['x'],
                            'y'         => $item['y'],
                            'type'      => 'service',
                            'iconset'   => $this->getNewIconset(((!isset($item['iconset'])) ? $data['global'][0]['iconset'] : $item['iconset'])),
                            'gadget'    => ($gadget) ? $gadget : null,
                        ];

                        if ($viewType == 'Mapline') {
                            $x = explode(',', $item['x']);
                            $y = explode(',', $item['y']);
                            $currentData = [
                                'object_id' => $ids['Service']['id'], //must be resolved from hostId
                                'startX'    => $x[0],
                                'endX'      => $x[1],
                                'startY'    => $y[0],
                                'endY'      => $y[1],
                                'type'      => 'service',
                                'iconset'   => 'std_line',
                                'gadget'    => (isset($gadget)) ? $gadget : 'null',
                            ];
                        }

                        $mapData[$viewType][] = $currentData;
                        break;
                    case 'hostgroup':
                        $hostgroupId = $this->resolveGroupname($item['hostgroup_name'], 'hostgroup');
                        //hostgroup not found
                        if (empty($hostgroupId)) {
                            continue;
                        }
                        $viewType = $this->getViewType((!isset($item['view_type'])) ? 'icon' : $item['view_type']);//icon or line
                        $currentData = [
                            'object_id' => $hostgroupId,
                            'type'      => 'hostgroup',
                            'x'         => $item['x'],
                            'y'         => $item['y'],
                            'iconset'   => $this->getNewIconset(((!isset($item['iconset'])) ? $data['global'][0]['iconset'] : $item['iconset'])),
                        ];
                        $mapData[$viewType][] = $currentData;
                        break;
                    case 'servicegroup':
                        $servicegroupId = $this->resolveGroupname($item['servicegroup_name'], 'servicegroup');
                        //servicegroup not found
                        if (empty($servicegroupId)) {
                            continue;
                        }
                        $viewType = $this->getViewType((!isset($item['view_type'])) ? 'icon' : $item['view_type']);//icon or line
                        $currentData = [
                            'object_id' => $servicegroupId,
                            'type'      => 'servicegroup',
                            'x'         => $item['x'],
                            'y'         => $item['y'],
                            'iconset'   => $this->getNewIconset(((!isset($item['iconset'])) ? $data['global'][0]['iconset'] : $item['iconset'])),
                        ];
                        $mapData[$viewType][] = $currentData;
                        break;
                    case 'textbox':
                        //stateless text
                        $currentData = [
                            'text' => $item['text'],
                            'x'    => $item['x'],
                            'y'    => $item['y'],
                            'size' => 12, // standard font size
                        ];
                        $mapData['Maptext'][] = $currentData;
                        break;
                    case 'line':
                        $x = explode(',', $item['x']);
                        $y = explode(',', $item['y']);
                        $currentData = [
                            'object_id' => 0, //must be resolved from hostId
                            'startX'    => $x[0],
                            'endX'      => $x[1],
                            'startY'    => $y[0],
                            'endY'      => $y[1],
                            'type'      => 'stateless',
                            'iconset'   => 'std_line',
                        ];

                        $mapData['Mapline'][] = $currentData;
                        break;
                    case 'shape':
                        //icon
                        $currentData = [
                            'icon' => $item['icon'],
                            'x'    => $item['x'],
                            'y'    => $item['y'],
                        ];
                        $mapData['Mapicon'][] = $currentData;
                        break;
                    case 'map':
                        $mapId = $this->resolveMapname($item['map_name']);
                        if (empty($mapId)) {
                            continue;
                        }

                        //map item
                        $currentData = [
                            'x'         => $item['x'],
                            'y'         => $item['y'],
                            'object_id' => $mapId,
                            'type'      => 'map',
                            'iconset'   => $this->getNewIconset(((!isset($item['iconset'])) ? $data['global'][0]['iconset'] : $item['iconset'])),
                        ];
                        $mapData['Mapitem'][] = $currentData;
                        break;
                    default:
                        $this->out('<error>the type ' . $key . ' is not specified!</error>');
                        break;
                }
            }
        }

        return $mapData;
    }

    /**
     * returns the new iconset
     * @author Maximilian Pappert <maximilian.pappert@it-novum.com>
     * @return String the new iconset
     */
    protected function getNewIconset($oldIconset) {
        foreach ($this->iconsetMap as $newIconsetKey => $iconsets) {
            if (in_array($oldIconset, $iconsets)) {
                return $newIconsetKey;
            }
        }

        return $oldIconset;
    }

    /**
     * @author Maximilian Pappert <maximilian.pappert@it-novum.com>
     *
     * @param  String $str the gadget url string
     *
     * @return the new gadget
     */
    protected function getGadget($str) {
        $gadgetUrl = preg_replace('/(\..*)/', '', $str);
        switch ($gadgetUrl) {
            case 'tacho':
            case 'tacho1':
            case 'tacho2':
            case 'tacho3':
            case 'std_speedometer':
            case 'std_speedometer2':
                return 'Tacho';
                break;
            case 'text':
            case 'text2':
            case 'text_wbg':
            case 'text-value-only':
            case 'rawOut':
            case 'time_text':
                return 'Text';
                break;
            case 'graph':
            case 'graph_all_ds':
            case 'graph_itn':
            case 'pChartPieChart_1':
            case 'graph_trend':
            case 'graph_traffic_in-out':
            case 'graph_apps30days':
            case 'graph_appsCweek':
            case 'graph_appsQuarter':
            case 'graph_appsYear':
                return 'RRDGraph';
                break;
            case 'temperatur':
            case 'zylinder':
            case 'zylinder3d':
                return 'Cylinder';
                break;
            default:
                return false;
                break;
        }
    }

    /**
     * returns the item type
     * @author Maximilian Pappert <maximilian.pappert@it-novum.com>
     *
     * @param  String $type the type returned from the config file
     *
     * @return String       the new type
     */
    protected function getViewType($type) {
        switch ($type) {
            case 'gadget':
                return 'Mapgadget';
                break;
            case 'line':
                return 'Mapline';
                break;
            default:
                return 'Mapitem';
                break;
        }
    }

    protected function resolveMapname($mapname) {
        $mapId = $this->map->find('first', [
            'recursive'  => -1,
            'conditions' => [
                'name' => $mapname,
            ],
            'fields'     => [
                'id',
            ],
        ]);
        if (!empty($mapId)) {
            $mapId = $mapId['Map']['id'];
            $this->out('<success>Map ' . $mapname . ' resolved! ID -> ' . $mapId . '</success>');

            return $mapId;
        }
        $errorMsg = '<warning>Could not resolve Map ' . $mapname . '</warning>';
        if ($this->lastError != $errorMsg) {
            $this->out($errorMsg);
            $this->lastError = $errorMsg;
        }

        return false;
    }

    /**
     * Resolves the name of a Host or Servicegroup
     * @author Maximilian Pappert <maximilian.pappert@it-novum.com>
     *
     * @param  String $groupname the name of the group
     * @param  String $groupType the group type. can be 'hostgroup' or 'servicegroup'
     *
     * @return mixed             the groupID or false on failure
     */
    protected function resolveGroupname($groupname, $groupType) {
        $type = ucfirst($groupType);

        $groupId = $this->$groupType->find('first', [
            'recursive' => -1,
            'fields'    => [
                $type . '.id',
                'Container.id',
            ],
            'joins'     => [
                [
                    'table'      => 'containers',
                    'alias'      => 'Container',
                    'type'       => 'INNER',
                    'conditions' => [
                        $type . '.container_id = Container.id',
                        'Container.name' => $groupname,
                    ],
                ],
            ],
        ]);
        if (!empty($groupId[$type]['id'])) {
            $groupId = $groupId[$type]['id'];
            $this->out('<success>' . $type . ' ' . $groupname . ' resolved! ID -> ' . $groupId . '</success>');

            return $groupId;
        }
        $this->out('<warning>Could not resolve ' . $type . ' ' . $groupname . '</warning>');

        return false;
    }

    /**
     * @author Maximilian Pappert <maximilian.pappert@it-novum.com>
     *
     * @param  String $hostname
     *
     * @return host id
     */
    protected function resolveHostname($hostname) {
        $hostId = $this->host->find('first', [
            'recursive'  => -1,
            'conditions' => [
                'Host.name' => $hostname,
            ],
            'fields'     => [
                'Host.id',
            ],
        ]);
        if (!empty($hostId)) {
            $hostId = $hostId['Host']['id'];
            $this->out('<success>Host ' . $hostname . ' resolved! ID -> ' . $hostId . '</success>');

            return $hostId;
        }


        $errorMsg = '<warning>Could not resolve Host ' . $hostname . '</warning>';
        if ($this->lastError != $errorMsg) {
            $this->out($errorMsg);
            $this->lastError = $errorMsg;
        }

        //in some cases the host could not be resolved due to the addition of a domain name or the "__evk_" prefix
        //if the first try fails then check again without the domain name or the "__evk_" prefix
        if (empty($hostId)) {
            $newName = '';
            if ($this->hasPrefix($hostname)) {
                //hostname contains prefix
                $newName = $this->stripPrefix($hostname);
                $this->out('<info>retry without prefix</info>');
            }

            if ($this->hasDomain($hostname)) {
                //the host has an Domain extension which could be cut off
                $newName = $this->stripDomain($hostname);
                $this->out('<info>retry without domain name ' . $newName . '</info>');
            }

            if (!empty($newName)) {
                $hostId = $this->host->find('first', [
                    'recursive'  => -1,
                    'conditions' => [
                        'Host.name' => $newName,
                    ],
                    'fields'     => [
                        'Host.id',
                    ],
                ]);
                if (!empty($hostId)) {
                    $hostId = $hostId['Host']['id'];
                    $this->out('<success>Host ' . $hostname . ' resolved! ID -> ' . $hostId . '</success>');

                    return $hostId;
                }
            }
        }

        return false;
    }

    /**
     * Check if the given name contains a prefix like '__evk_'
     *
     * @param $name the name to check
     *
     * @return bool
     */
    protected function hasPrefix($name) {
        if (preg_match('/__evk_/', $name)) {
            return true;
        }

        return false;
    }

    /**
     * strip the prefix like '__evk_' from the hostname
     *
     * @param $name the name to be stripped
     *
     * @return bool|mixed
     */
    protected function stripPrefix($name) {
        $pattern = '/__evk_/';
        if ($result = preg_replace($pattern, '', $name)) {
            return $result;
        }

        return false;
    }


    /**
     * check if the Host contains a domain suffix
     *
     * @param $name
     *
     * @return bool
     */
    protected function hasDomain($name) {
        if (preg_match('/([0-9a-z-]{2,}\.[0-9a-z-]{2,3}\.[0-9a-z-]{2,3}|[0-9a-z-]{2,}\.[0-9a-z-]{2,3})$/i', $name)) {
            return true;
        }

        return false;
    }

    /**
     * strip the domain suffix from the name
     *
     * @param $name
     *
     * @return bool|mixed
     */
    protected function stripDomain($name) {
        $pattern = '/.([0-9a-z-]{2,}\.[0-9a-z-]{2,3}\.[0-9a-z-]{2,3}|[0-9a-z-]{2,}\.[0-9a-z-]{2,3})$/i';
        if ($result = preg_replace($pattern, '', $name)) {
            return $result;
        }

        return false;
    }

    /**
     * @author Maximilian Pappert <maximilian.pappert@it-novum.com>
     *
     * @param  String $hostname
     * @param  String $servicename
     *
     * @return host and service id
     */
    protected function resolveServicename($hostname, $servicename) {
        $hostId = $this->resolveHostname($hostname);
        if (!empty($hostId)) {

            //strip name prefixes like '__evk_' from the name cause we dont have them anymore in v3
            if ($this->hasPrefix($servicename)) {
                //servicename contains prefix
                $servicename = $this->stripPrefix($servicename);
                $this->out('<info>stripped prefix from servicename</info>');
            }

            $serviceId = $this->service->find('first', [
                'conditions' => [
                    'Service.host_id' => $hostId,
                    'OR'              => [
                        'Service.name'         => $servicename,
                        'Servicetemplate.name' => $servicename,
                    ],
                ],
                'fields'     => [
                    'Service.id',
                ],
                'contain'    => [
                    'Host',
                    'Servicetemplate',
                ],
            ]);
            if (!empty($serviceId)) {
                $this->out('<success>Service ' . $servicename . ' resolved! ID -> ' . $serviceId['Service']['id'] . '</success>');

                return $serviceId;
            }
            $errorMsg = '<warning>Could not resolve Service ' . $servicename . '</warning>';
            if ($this->lastError != $errorMsg) {
                $this->out($errorMsg);
                $this->lastError = $errorMsg;
            }

            return false; // thers no service id
        }
        $errorMsg = '<warning>Could not resolve Host ' . $hostname . '</warning>';
        if ($this->lastError != $errorMsg) {
            $this->out($errorMsg);
            $this->lastError = $errorMsg;
        }

        return false; // thers no host id
    }

    /**
     * Transform the content of the config files into an array
     * @author Maximilian Pappert <maximilian.pappert@it-novum.com>
     *
     * @param  String $filenames filename of the file to transform
     *
     * @return Array                The Transformed filecontent
     */
    protected function transformFileContentToArray($filenames) {
        $data_key = null;
        $data_array = [];
        $file_definition_end = false;
        $intern_key = 0;
        if (file_exists($filenames)) {
            foreach (file($filenames) as $key => $row) {
                $row = trim($row);
                if (empty($row)) {
                    continue;
                }
                if ($row == '}') {
                    $file_definition_end = true;
                    continue;
                }
                //build the key
                if (preg_match_all('/(?<=\bdefine\s)(\w+)/', $row, $matches)) {
                    $data_key = $matches[0][0];
                    $file_definition_end = false;
                    $intern_key = (!array_key_exists($data_key, $data_array)) ? 0 : ((sizeof($data_array[$data_key])) + 1);
                    continue;
                }
                //fill the data
                if ($data_key && !$file_definition_end) {
                    $data_value_array = explode('=', $row);
                    $data_array[$data_key][$intern_key][$data_value_array[0]] = $data_value_array[1];
                }
            }

            //var_dump($data_array);
            return $data_array;
        } else {
            $this->out('<warning>Warning! The Specified File does not exist!</warning>');

            return false;
        }
    }

    /**
     * triggers the thumbnail creation which is within the BackgroundUploadsController
     * @author Maximilian Pappert <maximilian.pappert@it-novum.com>
     *
     * @param  String $dir the directory where the Backgrounds are located
     * @param  Array $images the list of the Backgrounds from the old system
     *
     * @return void
     */
    protected function triggerThumbnailCreation($dir, $images) {
        $folderInstance = new Folder($dir);
        $this->out('<info>Creating Thumbnails from the imported Backgrounds</info>');
        foreach ($images as $image) {
            $fullFilePath = $dir . DS . $image;
            $fileExtension = pathinfo($fullFilePath, PATHINFO_EXTENSION);
            $filename = preg_replace('/(\..*)/', '', $image);
            $data = [
                'fullPath'      => $fullFilePath,
                'uuidFilename'  => $filename,
                'fileExtension' => $fileExtension
            ];
            $this->MapUpload->createThumbnailsFromBackgrounds($data, $folderInstance);
        }
    }


    /**
     * check if the download directory for the config files exist
     * @author Maximilian Pappert <maximilian.pappert@it-novum.com>
     * @return Bool    true if download dir exist false if not
     */
    protected function checkConfigFilesDir($dir) {
        if (file_exists($dir)) {
            $this->out('<info>Download folder already exist!</info>');

            return true;
        }

        return false;
    }

    /**
     * create the folder where the config files will be downloaded
     * @author Maximilian Pappert <maximilian.pappert@it-novum.com>
     * @return void
     */
    protected function createDownloadDirectory($dir) {
        $this->out('<info>Creating Download Folder</info>');
        //take an example folder to get the rights
        $exampleFolder = OLD_APP . 'Plugin' . DS . 'MapModule' . DS . 'webroot' . DS . 'js' . DS;
        $owner = posix_getpwuid(fileowner($exampleFolder));
        mkdir($dir, fileperms($exampleFolder));
        chown($dir, $owner['name']);
    }

    /**
     * extract the name of iconset files, create the directory with the name
     * and move the files into the directory
     * @author Maximilian Pappert <maximilian.pappert@it-novum.com>
     *
     * @param  Array $list the list of iconset files
     * @param  String $dir the Directory where the new folders should be create
     *
     * @return void
     */
    protected function sortList($list, $dir) {
        $pattern = '/[^\_]+$/';
        foreach ($list as $listItem) {
            $item = preg_split($pattern, $listItem);
            if (!empty($item[0])) {
                //remove underscore from the item
                $item = preg_replace('/_$/', '', $item[0]);
                //get the new filename
                preg_match_all('/[^\_]+$/', $listItem, $newFilename);
                $newFilename = $newFilename[0][0];
                $folderName = $item;
                $to = $dir . DS . $folderName;
                //check/create iconset folder
                if ($this->createIconsetDirectories($dir, $folderName)) {
                    if (!$this->moveIconFiles($dir, $to, $listItem, $newFilename)) {
                        $this->out('<error>error moving ' . $listItem . ' to ' . $to . '</error>');
                    }
                    if ($folderName == 'back' || $folderName == 'demo_state' || $folderName == 'demo_landscapes') {
                    }
                }
            }
        }
    }

    /**
     * Create image Directories for the Iconsets
     * @author Maximilian Pappert <maximilian.pappert@it-novum.com>
     *
     * @param  String $path the Path where the directory shall be created
     * @param  String $name the name of the new folder
     *
     * @return Bool          true if the directory has benn created or if its already existing
     *                       false if everything fails
     */
    protected function createIconsetDirectories($path, $name) {
        $folder = $path . DS . $name;
        if (is_dir($folder)) {
            return true;
        } else {
            $this->out('<info>creating Folder ' . $name . '</info>');
            mkdir($folder);

            return true;
        }

        return false;
    }

    /**
     * iterates through the iconsets and convert every image to PNG
     * @author Maximilian Pappert <maximilian.pappert@it-novum.com>
     *
     * @param  $string $baseDir  the base directory of the iconsets
     *
     * @return void
     */
    protected function convert($baseDir) {
        $dir = new DirectoryIterator($baseDir);
        //iterate through base dir
        foreach ($dir as $fileInfo) {
            if ($fileInfo->isDir() && !$fileInfo->isDot()) {
                $this->out('<info>Convert icons in ' . $fileInfo->getFilename() . '</info>');
                $subDir = new DirectoryIterator($fileInfo->getPathname());
                //iterate through sub directory
                foreach ($subDir as $files) {
                    if ($files->isFile() && !$files->isDot()) {
                        $file = $files->getPathname();
                        $path = $files->getPath();
                        $filename = $files->getFilename();
                        $filename = preg_replace('/(\..*)/', '', $filename);
                        $fullPath = $path . DS . $filename . '.png';
                        if (pathinfo($path . DS . $file, PATHINFO_EXTENSION) != 'png') {
                            if ($this->convertToPNG($file, $fullPath)) {
                                $this->deleteFile($file);
                            }
                        }

                    }
                }
            } else {
                continue;
            }
        }
    }

    /**
     * mass move of files
     * @author Maximilian Pappert <maximilian.pappert@it-novum.com>
     *
     * @param  Array $files An Array of files to move
     * @param  String $from current destination of the files
     * @param  String $to destination where to files shall be moved
     *
     * @return void
     */
    protected function moveToDestination($files, $from, $to) {
        foreach ($files as $file) {
            $this->moveIconFiles($from, $to, $file);
        }
    }

    /**
     * moves recursively all folders with content in the $from Directory into the $to Directory
     * @author Maximilian Pappert <maximilian.pappert@it-novum.com>
     *
     * @param  String $from the path to move from
     * @param  String $to the path where the folders should be moved to
     *
     * @return true on success false on failure
     */
    protected function moveDirRecursively($from, $to, $skip = []) {
        $folderInstance = new Folder($from);
        $options = [
            'to'   => $to,
            'from' => $from,
            'skip' => $skip,
        ];

        return $folderInstance->move($options);
    }

    /**
     * check if the given Directory is existing. If its not then create it
     * @author Maximilian Pappert <maximilian.pappert@it-novum.com>
     *
     * @param  String $dir the string to check
     *
     * @return return true if the directory is already existing or if its successfully created. false if it cant be
     *                created
     */
    protected function checkDir($dir) {
        if (!file_exists($dir)) {
            return mkdir($dir);
        }

        return true;
    }

    /**
     * deletes the given file
     * @author Maximilian Pappert <maximilian.pappert@it-novum.com>
     *
     * @param  String $file the file to delete
     *
     * @return Bool          true on success false on error
     */
    protected function deleteFile($file) {
        return unlink($file);
    }

    /**
     * checks if the SSH2 Package is installed
     * @author Maximilian Pappert <maximilian.pappert@it-novum.com>
     * @return Bool    true if installed false if not
     */
    public function checkForSSH2Installed() {
        return function_exists('ssh2_connect');
    }

    /**
     * move the specified file to the given directory
     * you can also rename the file
     * @author Maximilian Pappert <maximilian.pappert@it-novum.com>
     *
     * @param  String $from The Path where the file is located
     * @param  String $to The Path where the file shall be moved
     * @param  String $filename The File to be moved
     * @param  String $newFilename optional if set rename the file
     *
     * @return Bool             True on success, false on failure
     */
    protected function moveIconFiles($from, $to, $filename, $newFilename = null) {
        $newFilename = ($newFilename == null) ? $filename : $newFilename;

        return rename($from . DS . $filename, $to . DS . $newFilename);
    }

    /**
     * convert an image to PNG
     * @author Maximilian Pappert <maximilian.pappert@it-novum.com>
     *
     * @param  String $file the file
     * @param  String $name path and filename for the created image
     *
     * @return Bool          true on success false on error
     */
    protected function convertToPNG($file, $name) {
        $im = imagecreatefromstring(file_get_contents($file));
        $white = imagecolorallocate($im, 255, 255, 255);
        imagecolortransparent($im, $white);

        return imagepng($im, $name);
    }

    // shall cleanup the ssh2 connection and delete the downloaded config files
    protected function cleanupData($session, $downloadDir) {
        $this->out('<info>Cleaning up Directory</info>');
        if (!ssh2_exec($session, 'exit')) {
            $this->out('<error>Could not exit ssh2 session</error>');
        } else {
            $this->out('<info>Exit ssh2 session</info>');
        }
        unset($session);
        //delete download folder with everything in it
        if ($this->deleteDownloadData($downloadDir)) {
            $this->out('<info>Deleting download directory</info>');
        } else {
            $this->out('<warning>Could not delete the download directory</warning>');
        }
    }

    //delete the download data
    protected function deleteDownloadData($dir) {
        $folderToRemove = new Folder($dir);

        return $folderToRemove->delete();
    }

    /*
    Copyright (c) 2010, dealnews.com, Inc.
    All rights reserved.

    Redistribution and use in source and binary forms, with or without
    modification, are permitted provided that the following conditions are met:

     * Redistributions of source code must retain the above copyright notice,
      this list of conditions and the following disclaimer.
     * Redistributions in binary form must reproduce the above copyright
      notice, this list of conditions and the following disclaimer in the
      documentation and/or other materials provided with the distribution.
     * Neither the name of dealnews.com, Inc. nor the names of its contributors
      may be used to endorse or promote products derived from this software
      without specific prior written permission.

    THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
    AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
    IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
    ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE
    LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
    CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
    SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
    INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
    CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
    ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
    POSSIBILITY OF SUCH DAMAGE.

     */

    /**
     * show a status bar in the console
     * <code>
     * for($x=1;$x<=100;$x++){
     * show_status($x, 100);
     * usleep(100000);
     * }
     * </code>
     *
     * @param int $done how many items are completed
     * @param int $total how many items are to be done total
     * @param int $size optional size of the status bar
     *
     * @return void
     */

    public function show_download_status($done, $total, $size = 30) {

        static $start_time;

        // if we go over our bound, just ignore it
        if ($done > $total) return;

        if (empty($start_time)) $start_time = time();
        $now = time();

        $perc = (double)($done / $total);

        $bar = floor($perc * $size);

        $status_bar = "\r[";
        $status_bar .= str_repeat("=", $bar);
        if ($bar < $size) {
            $status_bar .= ">";
            $status_bar .= str_repeat(" ", $size - $bar);
        } else {
            $status_bar .= "=";
        }

        $disp = number_format($perc * 100, 0);

        $status_bar .= "] $disp% $done/$total";

        $rate = ($now - $start_time) / $done;
        $left = $total - $done;
        $eta = round($rate * $left, 2);

        $elapsed = $now - $start_time;

        $status_bar .= " remaining: " . number_format($eta) . " sec. elapsed: " . number_format($elapsed) . " sec.";

        echo "$status_bar ";

        flush();

        // when done, send a newline
        if ($done == $total) {
            echo "\n";
        }
    }
}