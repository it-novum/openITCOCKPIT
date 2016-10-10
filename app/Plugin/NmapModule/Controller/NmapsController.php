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


App::uses('AppController', 'Controller');

class NmapsController extends AppController {
    public $layout = 'Admin.default';
    public $uses = [
        'Host',
        'NmapModule.Nmap',
        'Container'
    ];

    private $options = array('nmap_binary' => '/usr/bin/nmap');

    public function index(){
        //Define the target and options
        //$target = array('172.16.14.167');
        $target = array('172.16.92.100-105');


        try {
            //$nmap = new Net_Nmap($options);
            $this->Nmap->setOptions($this->options);
            $nmap_options = array(
                'os_detection' => false,
                //'fast_scan' => true,
                'service_info' => true,
                'all_options' => true,
                'port_ranges' => '1-100', // Only specified ports
            );
            $this->Nmap->enableOptions($nmap_options);

            // Scan
            $res = $this->Nmap->scan($target);

            // Get failed hosts
            $failed_to_resolve = $this->Nmap->getFailedToResolveHosts();
            if (count($failed_to_resolve) > 0) {
                echo 'Failed to resolve given hostname/IP: ' .
                    implode (', ', $failed_to_resolve) .
                    "\n";
            }

            //Parse XML Output to retrieve Hosts Object
            $hosts = $this->Nmap->parseXMLOutput();
            $this->set('hosts', $hosts);

        } catch (NmapException $ne) {
            echo $ne->getMessage();
        }
    }

    public function scanHost($hostId) {
        $this->set('MY_RIGHTS', $this->MY_RIGHTS);

        $userId = $this->Auth->user('id');

        if(!$this->Host->exists($hostId)){
            throw new NotFoundException(__('Invalid host'));
        }
        $_host = $this->Host->find('first', [
            'conditions' => [
                'Host.id' => $hostId,
            ],
            'contain' => [
                'Container'
            ],
            'fields' => [
                'Host.container_id',
                'Host.address',
                'Container.*'
            ]
        ]);

        $containerIdsToCheck = Hash::extract($_host, 'Container.{n}.HostsToContainer.container_id');
        $containerIdsToCheck[] = $_host['Host']['container_id'];
        if(!$this->allowedByContainerId($containerIdsToCheck)){
            $this->render403();
            return;
        }

        try {
            $this->Nmap->setOptions($this->options);
            $nmap_options = array(
                'os_detection' => false,
                'fast_scan' => true,
                'service_info' => true,
                'all_options' => true,
                'port_ranges' => '80', // Only specified ports
            );
            $this->Nmap->enableOptions($nmap_options);

            // Scan
            $target = array('127.0.0.1');
            $res = $this->Nmap->scan($target);
            //exit;
            // Get failed hosts
            /**$failed_to_resolve = $this->Nmap->getFailedToResolveHosts();
            if (count($failed_to_resolve) > 0) {
                echo 'Failed to resolve given hostname/IP: ' .
                    implode (', ', $failed_to_resolve) .
                    "\n";
            }**/

            //Parse XML Output to retrieve Hosts Object
            $hosts = $this->Nmap->parseXMLOutput();
            $this->set('result', $hosts);
            //Check if result is not empty, if empty go back to index page and show alert
            
            //============
            //Print results
            /*foreach ($hosts as $key => $host) {
                echo '<br>';
                echo '<b>Hostname: ' . $host->getHostname() . "</b><br>\n";
                echo 'Address: ' . $host->getAddress() . "<br>\n";
                echo 'OS: ' . $host->getOS() . "<br>\n";
                echo 'Status: ' . $host->getStatus() . "<br>\n";
                $services = $host->getServices();
                echo 'Number of discovered services: ' . count($services) . "<br>\n";
                foreach ($services as $key => $service) {
                    echo "<br>";
                    echo 'Service Name: ' . $service->name . "<br>\n";
                    echo 'Port: ' . $service->port . "<br>\n";
                    echo 'Protocol: ' . $service->protocol . "<br>\n";
                    echo 'Product information: ' . $service->product . "<br>\n";
                    echo 'Product version: ' . $service->version . "<br>\n";
                    echo 'Product additional info: ' . $service->extrainfo . "<br>\n";
                }
            }*/
        } catch (NmapException $ne) {
            echo $ne->getMessage();
        }

    }
}