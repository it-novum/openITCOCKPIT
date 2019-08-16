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
use itnovum\openITCOCKPIT\Core\DbBackend;
use itnovum\openITCOCKPIT\Core\NodeJS\ChartRenderClient;
use itnovum\openITCOCKPIT\Core\PerfdataBackend;
use itnovum\openITCOCKPIT\Core\ServicestatusFields;
use itnovum\openITCOCKPIT\Core\Views\Logo;
use itnovum\openITCOCKPIT\Perfdata\PerfdataLoader;

/**
 * Class NagiosNotificationTask
 * @property Systemsetting $Systemsetting
 * @property Servicestatus $Servicestatus
 */
class NagiosNotificationTask extends AppShell {

    public $uses = ['Systemsetting', MONITORING_SERVICESTATUS];

    public function construct() {
        /** @var $Systemsettings App\Model\Table\SystemsettingsTable */
        $Systemsettings = TableRegistry::getTableLocator()->get('Systemsettings');
        $this->_systemsettings = $Systemsettings->findAsArray();

        //Loading Cake libs
        App::uses('CakeEmail', 'Network/Email');
        App::uses('Folder', 'Utility');
    }

    /*
     *host notifications
     */

    public function hostNotification($parameters = []) {
        $Email = new CakeEmail();
        $Email->config('default');
        $Email->from([$this->_systemsettings['MONITORING']['MONITORING.FROM_ADDRESS'] => $this->_systemsettings['MONITORING']['MONITORING.FROM_NAME']]);
        $Email->to($parameters['contactmail']);
        $replyToVal = $this->_systemsettings['MONITORING']['MONITORING.ACK_RECEIVER_ADDRESS'];
        if (!empty($replyToVal)) {
            $Email->replyTo([$replyToVal => $replyToVal]);
        }

        switch ($parameters['notificationtype']) {
            case 'CUSTOM':
                $prefix = 'C';
                break;
            case 'RECOVERY':
                $prefix = '+';
                break;
            case 'PROBLEM':
                $prefix = '-';
                break;
            case 'ACKNOWLEDGEMENT':
                $prefix = 'ACK';
                break;
            case 'DOWNTIMESTART':
                $prefix = 'D+';
                break;
            case 'DOWNTIMEEND':
            case 'DOWNTIMECANCELLED':
                $prefix = 'D-';
                break;
            default:
                $prefix = '?';
                break;
        }

        $Email->subject($prefix . ' | Host: ' . $parameters['hostname'] . ' is ' . $parameters['hoststate']);

        $Email->emailFormat('both');
        if ($parameters['format'] == 'text') {
            $Email->emailFormat('text');
        }
        if ($parameters['format'] == 'html') {
            $Email->emailFormat('html');
        }

        $Email->template('template-itn-std-host', 'template-itn-std-host')->viewVars(['parameters' => $parameters, '_systemsettings' => $this->_systemsettings]);


        if (!$parameters['no-attachments']) {
            $Logo = new Logo();
            $Email->attachments([
                'logo.png' => [
                    'file'      => $Logo->getSmallLogoDiskPath(),
                    'mimetype'  => 'image/png',
                    'contentId' => '100',
                ],
            ]);
        }

        $Email->send();
    }

    /*
     *service notifications
     */

    public function serviceNotification($parameters = []) {
        $Logo = new Logo();
        $Email = new CakeEmail();
        $Email->config('default');
        //$Email->from([$this->_systemsettings['MONITORING']['MONITORING.FROM_ADDRESS'] => ['MONITORING']['MONITORING.FROM_NAME']]);
        $Email->from([$this->_systemsettings['MONITORING']['MONITORING.FROM_ADDRESS'] => $this->_systemsettings['MONITORING']['MONITORING.FROM_NAME']]);
        $Email->to($parameters['contactmail']);
        $replyToVal = $this->_systemsettings['MONITORING']['MONITORING.ACK_RECEIVER_ADDRESS'];
        if (!empty($replyToVal)) {
            $Email->replyTo([$replyToVal => $replyToVal]);
        }

        switch ($parameters['notificationtype']) {
            case 'CUSTOM':
                $prefix = 'C';
                break;
            case 'RECOVERY':
                $prefix = '+';
                break;
            case 'PROBLEM':
                $prefix = '-';
                break;
            case 'ACKNOWLEDGEMENT':
                $prefix = 'ACK';
                break;
            case 'DOWNTIMESTART':
                $prefix = 'D+';
                break;
            case 'DOWNTIMEEND':
            case 'DOWNTIMECANCELLED':
                $prefix = 'D-';
                break;
            default:
                $prefix = '?';
                break;
        }

        if ($parameters['serviceType'] === EVK_SERVICE && CakePlugin::loaded('EventcorrelationModule')) {
            $this->loadModel('EventcorrelationModule.Eventcorrelation');
            $serviceStateArray = [
                'OK'       => 0,
                'WARNING'  => 1,
                'CRITICAL' => 2,
                'UNKNOWN'  => 3
            ];

            $DbBackend = new DbBackend();
            $ServicestatusFields = new ServicestatusFields($DbBackend);
            $ServicestatusFields->currentState()->longOutput();

            $evcTree = $this->Eventcorrelation->getEvcTreeData($parameters['hostId'], []);
            $servicestatus = $this->Servicestatus->byUuid(Hash::extract($evcTree, '{n}.{*}.{n}.Service.uuid'), $ServicestatusFields);
            $servicestatus[$parameters['serviceUuid']]['current_state'] = $parameters['servicestate'];
            $parameters['evcTree'] = $evcTree;
            $parameters['servicestatus'] = $servicestatus;
        }

        $Email->subject($prefix . ' | Service: ' . $parameters['servicedesc'] . ' (' . $parameters['hostname'] . ') is ' . $parameters['servicestate']);

        $Email->emailFormat('both');
        if ($parameters['format'] == 'text') {
            $Email->emailFormat('text');
        }
        if ($parameters['format'] == 'html') {
            $Email->emailFormat('html');
        }

        if (!$parameters['no-attachments']) {
            $Email->attachments([
                'logo.png' => [
                    'file'      => $Logo->getSmallLogoDiskPath(),
                    'mimetype'  => 'image/png',
                    'contentId' => '100',
                ],
            ]);
        }

        /*
         *create graph/s
         */

        $contentIDs = [];
        $GraphImageBlobs = [];
        $attachments['logo.png'] = [
            'file'      => $Logo->getSmallLogoDiskPath(),
            'mimetype'  => 'image/png',
            'contentId' => '100'
        ];

        if (!$parameters['no-attachments']) {

            $DbBackend = new DbBackend();
            $PerfdataBackend = new PerfdataBackend();
            $PerfdataLoader = new PerfdataLoader($DbBackend, $PerfdataBackend);

            try {
                $graphStart = (time() - (4 * 3600));
                $graphData = $PerfdataLoader->getPerfdataByUuid(
                    $parameters['hostUuid'],
                    $parameters['serviceUuid'],
                    $graphStart,
                    time(),
                    false,
                    'avg'
                );

                if (!empty($graphData)) {
                    //Render graph data to png image blobs for pdf
                    $NodeJsChartRenderClient = new ChartRenderClient();
                    $NodeJsChartRenderClient->setGraphStartTimestamp($graphStart);
                    $NodeJsChartRenderClient->setGraphEndTimestamp(time());
                    $NodeJsChartRenderClient->setHeight(180);
                    $NodeJsChartRenderClient->setWidth(560);
                    $NodeJsChartRenderClient->setTitle(
                        sprintf(
                            '%s - %s',
                            $parameters['hostname'],
                            $parameters['servicedesc']
                        ));

                    //render two gauges per chart
                    $id = 200;
                    foreach (array_chunk($graphData, 2) as $graphDataChunk) {
                        $fileName = sprintf('Chart_%s.png', $id);
                        $attachments[$fileName] = [
                            'data'      => $NodeJsChartRenderClient->getAreaChartAsPngStream($graphDataChunk),
                            'mimetype'  => 'image/png',
                            'contentId' => $id
                        ];
                        $contentIDs[] = $id;
                        $id++;
                    }
                }
            } catch (\Exception $e) {
                $this->out('Error while creating graph');
                $this->out($e->getMessage());
            }


            // debug($attachments);
            // send attachments
            try {
                $Email->attachments($attachments);
            } catch (Exception $e) {
                $this->out($e->getMessage());
            }

        }

        $Email->template('template-itn-std-service', 'template-itn-std-service')->viewVars([
            'parameters'      => $parameters,
            '_systemsettings' => $this->_systemsettings,
            'contentIDs'      => $contentIDs
        ]);

        //send template to mail address
        $Email->send();
    }


    private function replaceChars($str, $replacement = ' ') {
        return preg_replace('/[^a-zA-Z^0-9\-\.]/', $replacement, $str);
    }

}
