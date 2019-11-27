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
use App\Form\CurrentstatereportForm;
use App\Lib\Exceptions\MissingDbBackendException;
use App\Model\Table\HostsTable;
use App\Model\Table\ServicesTable;
use Cake\ORM\TableRegistry;
use itnovum\openITCOCKPIT\Core\DbBackend;
use itnovum\openITCOCKPIT\Core\HoststatusFields;
use itnovum\openITCOCKPIT\Core\ServiceConditions;
use itnovum\openITCOCKPIT\Core\ServiceControllerRequest;
use itnovum\openITCOCKPIT\Core\ServicestatusConditions;
use itnovum\openITCOCKPIT\Core\Views\ContainerPermissions;
use itnovum\openITCOCKPIT\Filter\ServiceFilter;
use Statusengine\PerfdataParser;


/**
 * @property CurrentstatereportForm $CurrentstatereportForm
 * @property DbBackend $DbBackend
 * @property AppPaginatorComponent $Paginator
 */
class CurrentstatereportsController extends AppController {
    public $layout = 'blank';

    public function index() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template
            return;
        }
        $currentstatereportForm = new CurrentstatereportForm();

        $currentstatereportForm->execute($this->request->data);

        if (!empty($currentstatereportForm->getErrors())) {
            $this->response->statusCode(400);
            $this->set('error', $currentstatereportForm->getErrors());
            $this->set('_serialize', ['error']);
            return;
        } else {
            $ServiceFilter = new ServiceFilter($this->request);

            $ServiceControllerRequest = new ServiceControllerRequest($this->request, $ServiceFilter);
            $ServiceConditions = new ServiceConditions(
                $ServiceFilter->indexFilter()
            );


            $ServiceConditions->setServiceIds($this->request->data('services'));
            $ServiceConditions->setContainerIds($this->MY_RIGHTS);
            $ServiceConditions->setOrder($ServiceControllerRequest->getOrder([
                'Hosts.name'  => 'asc',
                'servicename' => 'asc'
            ]));

            $ServicestatusConditions = new ServicestatusConditions($this->DbBackend);
            $ServicestatusConditions->currentState($this->request->data('Servicestatus.current_state'));

            if ($this->request->data('Servicestatus.hasBeenAcknowledged') !== null) {
                $ServicestatusConditions->setProblemHasBeenAcknowledged($this->request->data('Servicestatus.hasBeenAcknowledged'));
            }
            if ($this->request->data('Servicestatus.inDowntime') !== null) {
                $ServicestatusConditions->setScheduledDowntimeDepth($this->request->data('Servicestatus.inDowntime'));
            }
            if ($this->request->data('Servicestatus.passive') !== null) {
                $ServicestatusConditions->setActiveChecksEnabled($this->request->data('Servicestatus.passive'));
            }

            $all_services = $this->createReport(
                $ServiceConditions,
                $ServicestatusConditions
            );
            $this->set('all_services', $all_services);
            $this->set('_serialize', ['all_services']);
        }
    }


    public function createPdfReport() {
        //Rewrite GET to "POST"
        $this->request->data = $this->request->query('data');
        $this->layout = 'Admin.default';


        $currentstatereportForm = new CurrentstatereportForm();
        $currentstatereportForm->execute($this->request->data);


        if (!empty($currentstatereportForm->getErrors())) {

            $this->set('input', $this->request->data);

            $this->response->statusCode(400);
            $this->set('error', $currentstatereportForm->getErrors());
            $this->set('_serialize', ['error', 'input']);
            return;
        }

        if ($this->isJsonRequest()) {
            //Only validate parameters
            $this->set('success', true);
            $this->set('_serialize', ['success']);
            return;
        }

        $ServiceFilter = new ServiceFilter($this->request);

        $ServiceControllerRequest = new ServiceControllerRequest($this->request, $ServiceFilter);
        $ServiceConditions = new ServiceConditions(
            $ServiceFilter->indexFilter()
        );


        $ServiceConditions->setServiceIds($this->request->data('services'));
        $ServiceConditions->setContainerIds($this->MY_RIGHTS);
        $ServiceConditions->setOrder($ServiceControllerRequest->getOrder([
            'Hosts.name'  => 'asc',
            'servicename' => 'asc'
        ]));

        $ServicestatusConditions = new ServicestatusConditions($this->DbBackend);
        $ServicestatusConditions->currentState($this->request->data('Servicestatus.current_state'));

        if ($this->request->data('Servicestatus.hasBeenAcknowledged') !== null) {
            $ServicestatusConditions->setProblemHasBeenAcknowledged($this->request->data('Servicestatus.hasBeenAcknowledged'));
        }
        if ($this->request->data('Servicestatus.inDowntime') !== null) {
            $ServicestatusConditions->setScheduledDowntimeDepth($this->request->data('Servicestatus.inDowntime'));
        }
        if ($this->request->data('Servicestatus.passive') !== null) {
            $ServicestatusConditions->setActiveChecksEnabled($this->request->data('Servicestatus.passive'));
        }

        $all_services = $this->createReport(
            $ServiceConditions,
            $ServicestatusConditions,
            true
        );

        $this->set('all_services', $all_services);


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
            'filename'           => 'Currentstatereport_' . date('dmY_his') . '.pdf',
            'no-pdf-compression' => '*',
            'image-dpi'          => '900',
            'background'         => true,
            'no-background'      => false,
        ];
    }

    /**
     * @param ServiceConditions $ServiceConditions
     * @param ServicestatusConditions $ServicestatusConditions
     * @param bool $pdf
     * @return array
     * @throws MissingDbBackendException
     */
    protected function createReport(ServiceConditions $ServiceConditions, ServicestatusConditions $ServicestatusConditions, $pdf = false) {

        /** @var $HostsTable HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');
        /** @var $ServicesTable ServicesTable */
        $ServicesTable = TableRegistry::getTableLocator()->get('Services');
        $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->Auth);
        $services = [];

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
            ->lastCheck()
            ->nextCheck()
            ->output();
        $HoststatusTable = $this->DbBackend->getHoststatusTable();
        $hoststatusCache = $HoststatusTable->byUuid(
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
                    'Hoststatus' => (!$pdf) ? $Hoststatus->toArrayForBrowser() : $Hoststatus->toArray()
                ];
            }
            $Service = new \itnovum\openITCOCKPIT\Core\Views\Service($service, null, $allowEdit);
            $Servicestatus = new \itnovum\openITCOCKPIT\Core\Servicestatus($service['Servicestatus'], $UserTime);
            $currentServiceId = $Service->getId();
            $tmpRecord = [
                'Service'       => $Service->toArray(),
                'Servicestatus' => (!$pdf) ? $Servicestatus->toArrayForBrowser() : $Servicestatus->toArray()
            ];
            $PerfdataParser = new PerfdataParser($Servicestatus->getPerfdata());
            $parsedPerfdata = $PerfdataParser->parse();
            $tmpRecord['Servicestatus']['perfdataArray'] = $parsedPerfdata;
            $tmpRecord['Servicestatus']['perfdataArrayCounter'] = sizeof($parsedPerfdata);
            $all_services[$currentHostId]['Services'][$currentServiceId] = $tmpRecord;
        }
        return $all_services;
    }
}
