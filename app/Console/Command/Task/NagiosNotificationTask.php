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

class NagiosNotificationTask extends AppShell
{

    public $uses = ['Rrd', 'Systemsetting'];

    public function construct()
    {
        $this->_systemsettings = $this->Systemsetting->findAsArray();

        //Loading Cake libs
        App::uses('CakeEmail', 'Network/Email');
        App::uses('Folder', 'Utility');
    }

    /*
     *host notifications
     */

    public function hostNotification($parameters = [])
    {
        $Email = new CakeEmail();
        $Email->config('default');
        $Email->from([$this->_systemsettings['MONITORING']['MONITORING.FROM_ADDRESS'] => $this->_systemsettings['MONITORING']['MONITORING.FROM_NAME']]);
        $Email->to($parameters['contactmail']);

        //$Email->from('localhost@testv3.com');
        $prefix = '';
        if ($parameters['notificationtype'] == 'RECOVERY') {
            $prefix = '+';
        }

        if ($parameters['notificationtype'] == 'PROBLEM') {
            $prefix = '-';
        }

        //$Email->to('christian.michel@it-novum.com');
        //$Email->to('daniel.ziegler@it-novum.com');
        $Email->subject($prefix.' | Host: '.$parameters['hostname'].' is '.$parameters['hoststate']);

        $Email->emailFormat('both');
        $Email->template('template-itn-std-host', 'template-itn-std-host')->viewVars(['parameters' => $parameters, '_systemsettings' => $this->_systemsettings]);

        $Email->attachments([
            'logo.png' => [
                'file'      => APP.'webroot/img/oitc_small.png',
                'mimetype'  => 'image/png',
                'contentId' => '100',
            ],
        ]);

        $Email->send();
    }

    /*
     *service notifications
     */

    public function serviceNotification($parameters = [])
    {
        $Email = new CakeEmail();
        $Email->config('default');
        //$Email->from([$this->_systemsettings['MONITORING']['MONITORING.FROM_ADDRESS'] => ['MONITORING']['MONITORING.FROM_NAME']]);
        $Email->from([$this->_systemsettings['MONITORING']['MONITORING.FROM_ADDRESS'] => $this->_systemsettings['MONITORING']['MONITORING.FROM_NAME']]);
        $Email->to($parameters['contactmail']);

        //$Email->from('localhost@testv3.com');
        $prefix = '';
        if ($parameters['notificationtype'] == 'RECOVERY') {
            $prefix = '+';
        }

        if ($parameters['notificationtype'] == 'PROBLEM') {
            $prefix = '-';
        }

        //$Email->to('daniel.ziegler@it-novum.com');
        //$Email->to('christian.michel@it-novum.com');
        $Email->subject($prefix.' | Service: '.$parameters['servicedesc'].' ('.$parameters['hostname'].') is '.$parameters['servicestate']);

        $Email->emailFormat('both');
        //$Email->template('template-itn-std-service', 'template-itn-std-service')->viewVars(['parameters' => $parameters, '_systemsettings' => $this->_systemsettings]);

        $Email->attachments([
            'logo.png' => [
                'file'      => APP.'webroot/img/oitc_small.png',
                'mimetype'  => 'image/png',
                'contentId' => '100',
            ],
        ]);

        /*
         *create graph/s
         */

        $contentIDs = [];

        if (file_exists(Configure::read('rrd.path').$parameters['hostUuid'].'/'.$parameters['serviceUuid'].'.rrd') && (Configure::read('rrd.path').$parameters['hostUuid'].'/'.$parameters['serviceUuid'].'.xml')) {

            // declare rrd ds graph files array
            $parameters['graph_path'] = [];

            // get datasource count
            $rrdds = $this->Rrd->getPerfDataStructure(Configure::read('rrd.path').$parameters['hostUuid'].'/'.$parameters['serviceUuid'].'.xml');

            // create temp path with hostUuid if not exists
            $hosttmpdir_path = '/tmp/'.$parameters['hostUuid'];
            $hosttmpdir = new Folder($hosttmpdir_path, true, 0777);

            $attachments['logo.png'] = ['file' => APP.'webroot/img/oitc_small.png', 'mimetype' => 'image/png', 'contentId' => '100'];

            // draw graph for every datasource of the service 
            foreach ($rrdds as $ds) {
                //print_r('DS: '.$ds['ds'].' - Label: '.$ds['label'].' - Unit: '.$ds['unit'].' # ');

                // generate and save filename to array
                $dscount = $ds['ds'];
                $parameters['graph_path'][$dscount] = ''.$hosttmpdir_path.'/mailgraph_'.$ds['ds'].'_'.rand(1, 999999).'.png';

                // create graph
                $this->create_graph($parameters['graph_path'][$dscount], "-8h", $parameters['hostname']." / ".$parameters['servicedesc']." - ".$ds['label'], $parameters, $ds);

                $attachments['mailgraph_'.$dscount.'.png'] = ['file' => $parameters['graph_path'][$dscount], 'mimetype' => 'image/png', 'contentId' => '10'.$dscount];

                $contentIDs[] = '10'.$dscount;

            }

            // read dir with graph files
            $mailgraph_files = $hosttmpdir->find('.*\.png', true);
            //print_r($mailgraph_files);

            // debug($attachments);
            // send attachments
            $Email->attachments($attachments);
        } else {
            $attachments['logo.png'] = ['file' => APP.'webroot/img/oitc_small.png', 'mimetype' => 'image/png', 'contentId' => '100'];
            $Email->attachments($attachments);
        }


        $Email->template('template-itn-std-service', 'template-itn-std-service')->viewVars(['parameters' => $parameters, '_systemsettings' => $this->_systemsettings, 'contentIDs' => $contentIDs]);

        //send template to mail address
        $Email->send();


        //delete graph-files ! not the folder! 
        //in case of parallel notifications it will delete png files of other notifications
        foreach ($mailgraph_files as $graphfile) {
            unlink($hosttmpdir_path.'/'.$graphfile);
        }
    }


    /*
     *create graph function
     */

    public function create_graph($output, $start, $title, $parameters, $ds)
    {
        $parameters['hostname'] = $this->replaceChars($parameters['hostname']);
        $parameters['servicedesc'] = $this->replaceChars($parameters['servicedesc']);
        $ds['label'] = $this->replaceChars($ds['label']);

        $options = [
            "--slope-mode",
            "-l0",
            "-u1",
            "--start=".$start,
            "--title=".$title,
            "--lower=0",
            "--vertical-label=".$ds['unit'],
            "DEF:var0=".Configure::read('rrd.path').$parameters['hostUuid']."/".$parameters['serviceUuid'].".rrd:".$ds['ds'].":AVERAGE",
            "AREA:var0#00FF00:",
            "LINE1:var0#1aa8e4:".$parameters['hostname']."/".$parameters['servicedesc']." - ".$ds['label'],
        ];

        $ret = rrd_graph($output, $options);
        if (!$ret) {
            echo "<b>Graph error: </b>".rrd_error()."\n";
        }
    }


    private function replaceChars($str, $replacement = ' ')
    {
        return preg_replace('/[^a-zA-Z^0-9\-\.]/', $replacement, $str);
    }

}
