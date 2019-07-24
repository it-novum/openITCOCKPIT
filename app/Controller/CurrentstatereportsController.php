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
use App\Form\CurrentreportForm;
use App\Lib\Exceptions\MissingDbBackendException;
use App\Model\Table\HostsTable;
use App\Model\Table\ServicesTable;
use Cake\ORM\TableRegistry;
use itnovum\openITCOCKPIT\Core\FileDebugger;
use itnovum\openITCOCKPIT\Core\HoststatusFields;
use itnovum\openITCOCKPIT\Core\ServiceConditions;
use itnovum\openITCOCKPIT\Core\ServiceControllerRequest;
use itnovum\openITCOCKPIT\Core\ServicestatusConditions;
use itnovum\openITCOCKPIT\Core\ServicestatusFields;
use itnovum\openITCOCKPIT\Core\Views\ContainerPermissions;
use itnovum\openITCOCKPIT\Filter\ServiceFilter;
use Statusengine\PerfdataParser;


/**
 * @property Currentstatereport $Currentstatereport
 * @property Host $Host
 * @property Service $Service
 * @property Hoststatus $Hoststatus
 * @property AppPaginatorComponent $Paginator
 */
class CurrentstatereportsController extends AppController {
    public $layout = 'Admin.default';
    public $uses = [
        MONITORING_HOSTSTATUS,
        MONITORING_SERVICESTATUS,
        'Currentstatereport',
        'Host',
        'Service',
    ];

    public function index() {
        $this->layout = 'blank';
        if (!$this->isApiRequest()) {
            //Only ship HTML template
            return;
        }

        $currentstatereportForm = new CurrentreportForm();

        $currentstatereportForm->execute($this->request->data);

        if (!empty($currentstatereportForm->getErrors())) {
            $this->response->statusCode(400);
            $this->set('error', $currentstatereportForm->getErrors());
            $this->set('_serialize', ['error']);
            return;
        } else {

            /** @var $HostsTable HostsTable */
            $HostsTable = TableRegistry::getTableLocator()->get('Hosts');
            /** @var $ServicesTable ServicesTable */
            $ServicesTable = TableRegistry::getTableLocator()->get('Services');
            $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->Auth);

            $ServiceFilter = new ServiceFilter($this->request);

            $ServiceControllerRequest = new ServiceControllerRequest($this->request, $ServiceFilter);
            $ServiceConditions = new ServiceConditions(
                $ServiceFilter->indexFilter()
            );

            $ServiceConditions->setServiceIds($this->request->data('services'));
            $ServiceConditions->setContainerIds($this->MY_RIGHTS);

            $ServicestatusConditions = new ServicestatusConditions($this->DbBackend);
            $ServicestatusConditions->currentState($this->request->data('Servicestatus.current_state'));
            if($this->request->data('Servicestatus.hasBeenAcknowledged') !== null){
                $ServicestatusConditions->setProblemHasBeenAcknowledged($this->request->data('Servicestatus.hasBeenAcknowledged'));
            }
            if($this->request->data('Servicestatus.inDowntime') !== null){
                $ServicestatusConditions->setScheduledDowntimeDepth($this->request->data('Servicestatus.inDowntime'));
            }
            if($this->request->data('Servicestatus.passive') !== null){
                $ServicestatusConditions->setActiveChecksEnabled($this->request->data('Servicestatus.passive'));
            }

            $ServiceConditions->setOrder($ServiceControllerRequest->getOrder([
                'Hosts.name'  => 'asc',
                'servicename' => 'asc'
            ]));


            if ($this->DbBackend->isNdoUtils()) {
                $services = $ServicesTable->getServiceForCurrentReport($ServiceConditions, $ServicestatusConditions);
            }

            if ($this->DbBackend->isCrateDb()) {
                throw new MissingDbBackendException('MissingDbBackendException');
            }

            if ($this->DbBackend->isStatusengine3()) {
                throw new MissingDbBackendException('MissingDbBackendException');
            }

            $hostContainers = [];
            if ($this->hasRootPrivileges === false) {
                if ($this->hasPermission('edit', 'hosts') && $this->hasPermission('edit', 'services')) {
                    foreach ($services as $index => $service) {
                        $hostId = $service['_matchingData']['Hosts']['id'];
                        if (!isset($hostContainers[$hostId])) {
                            $hostContainers[$hostId] = $HostsTable->getHostContainerIdsByHostId($hostId);
                        }

                        $ContainerPermissions = new ContainerPermissions($this->MY_RIGHTS_LEVEL, $hostContainers[$hostId]);
                        $services[$index]['allow_edit'] = $ContainerPermissions->hasPermission();
                    }
                }
            } else {
                //Root user
                foreach ($services as $index => $service) {
                    $services[$index]['allow_edit'] = $this->hasRootPrivileges;
                }
            }

            $HoststatusFields = new HoststatusFields($this->DbBackend);
            $HoststatusFields
                ->currentState()
                ->isFlapping()
                ->isHardstate()
                ->scheduledDowntimeDepth()
                ->problemHasBeenAcknowledged()
                ->activeChecksEnabled()
                ->acknowledgementType()
                ->lastHardStateChange()
                ->currentCheckAttempt()
                ->maxCheckAttempts()
                ->output();
            $hoststatusCache = $this->Hoststatus->byUuid(
                array_unique(\Cake\Utility\Hash::extract($services, '{n}._matchingData.Hosts.uuid')),
                $HoststatusFields
            );


            $all_services = [];
            $UserTime = $User->getUserTime();
            foreach ($services as $service) {
                $allowEdit = $service['allow_edit'];
                $Host = new \itnovum\openITCOCKPIT\Core\Views\Host($service['_matchingData']['Hosts'], $allowEdit);
                if (isset($hoststatusCache[$Host->getUuid()]['Hoststatus'])) {
                    $Hoststatus = new \itnovum\openITCOCKPIT\Core\Hoststatus($hoststatusCache[$Host->getUuid()]['Hoststatus'], $UserTime);
                } else {
                    $Hoststatus = new \itnovum\openITCOCKPIT\Core\Hoststatus([], $UserTime);
                }
                $currentHostId = $Host->getId();
                if (!isset($all_services[$currentHostId])) {
                    $all_services[$currentHostId] = [
                        'Host'       => $Host->toArray(),
                        'Hoststatus' => $Hoststatus->toArrayForBrowser()
                    ];
                }
                $Service = new \itnovum\openITCOCKPIT\Core\Views\Service($service, null, $allowEdit);
                $Servicestatus = new \itnovum\openITCOCKPIT\Core\Servicestatus($service['Servicestatus'], $UserTime);
                $currentServiceId = $Service->getId();
                $tmpRecord = [
                    'Service'       => $Service->toArray(),
                    'Servicestatus' => $Servicestatus->toArrayForBrowser()
                ];
                $PerfdataParser = new PerfdataParser($Servicestatus->getPerfdata());
                $parsedPerfdata = $PerfdataParser->parse();
                $tmpRecord['Servicestatus']['perfdataArray'] = $parsedPerfdata;
                $tmpRecord['Servicestatus']['perfdataArrayCounter'] = sizeof($parsedPerfdata);
                $all_services[$currentHostId]['Services'][$currentServiceId] = $tmpRecord;
            }
            // $all_services = Hash::sort($all_services, '{n}.Host.hostname', 'asc');
            $this->set('all_services', $all_services);
            $toJson = ['all_services', 'paging'];
            if ($this->isScrollRequest()) {
                $toJson = ['all_services', 'scroll'];
            }
            $this->set('_serialize', $toJson);
            //No errors
        }
    }

    public function createHtmlReport() {
        $this->set('currentStateData', $this->Session->read('currentStateData'));
        if ($this->Session->check('currentStateData')) {
            $this->Session->delete('currentStateData');
            $this->render('/Elements/load_current_state_report_data');
        } else {
            $this->redirect(['action' => 'index']);
        }
    }

    public function createPdfReport() {
        $this->set('currentStateData', $this->Session->read('currentStateData'));
        if ($this->Session->check('currentStateData')) {
            $this->Session->delete('currentStateData');
        }

        $binary_path = '/usr/bin/wkhtmltopdf';
        if (file_exists('/usr/local/bin/wkhtmltopdf')) {
            $binary_path = '/usr/local/bin/wkhtmltopdf';
        }
        $this->pdfConfig = [
            'engine'             => 'CakePdf.WkHtmlToPdf',
            'margin'             => [
                'bottom' => 15,
                'left'   => 0,
                'right'  => 0,
                'top'    => 15,
            ],
            'encoding'           => 'UTF-8',
            'download'           => true,
            'binary'             => $binary_path,
            'orientation'        => 'portrait',
            'filename'           => 'Currentstatereport.pdf',
            'no-pdf-compression' => '*',
            'image-dpi'          => '900',
            'background'         => true,
            'no-background'      => false,
        ];
    }
}
