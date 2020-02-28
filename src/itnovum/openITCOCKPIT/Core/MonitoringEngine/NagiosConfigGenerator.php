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

namespace itnovum\openITCOCKPIT\Core\MonitoringEngine;

use App\Model\Entity\CalendarHoliday;
use App\Model\Entity\Host;
use App\Model\Entity\Hostdependency;
use App\Model\Entity\Hostgroup;
use App\Model\Entity\Service;
use App\Model\Entity\Servicegroup;
use App\Model\Table\CalendarsTable;
use App\Model\Table\CommandsTable;
use App\Model\Table\ContactgroupsTable;
use App\Model\Table\ContactsTable;
use App\Model\Table\DeletedHostsTable;
use App\Model\Table\DeletedServicesTable;
use App\Model\Table\HostdependenciesTable;
use App\Model\Table\HostescalationsTable;
use App\Model\Table\HostgroupsTable;
use App\Model\Table\HostsTable;
use App\Model\Table\HosttemplatesTable;
use App\Model\Table\MacrosTable;
use App\Model\Table\ProxiesTable;
use App\Model\Table\ServicedependenciesTable;
use App\Model\Table\ServiceescalationsTable;
use App\Model\Table\ServicegroupsTable;
use App\Model\Table\ServicesTable;
use App\Model\Table\ServicetemplatesTable;
use App\Model\Table\SystemsettingsTable;
use App\Model\Table\TimeperiodsTable;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Datasource\EntityInterface;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Filesystem\File;
use Cake\Filesystem\Folder;
use Cake\Log\Log;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use itnovum\openITCOCKPIT\Core\KeyValueStore;
use itnovum\openITCOCKPIT\Core\UUID;

class NagiosConfigGenerator {

    private $conf = [];

    private $_systemsettings = [];

    private $FRESHNESS_THRESHOLD_ADDITION = 0;

    /**
     * Is DistributeModule loaded
     * @var bool
     */
    private $dm = false;

    private $dmConfig = [];

    /**
     * @var \DistributeModule\Model\Entity\Satellite[]
     */
    private $Satellites;

    /**
     * @var KeyValueStore
     */
    private $TimeperiodUuidsCache;

    /**
     * @var KeyValueStore
     */
    private $CommandUuidsCache;

    /**
     * @var KeyValueStore
     */
    private $HosttemplateHostgroupsCache;

    /**
     * NagiosExportTask constructor.
     */
    public function __construct() {
        Configure::load('nagios');
        $this->conf = Configure::read('nagios.export');

        /** @var SystemsettingsTable $SystemsettingsTable */
        $SystemsettingsTable = TableRegistry::getTableLocator()->get('Systemsettings');
        $this->_systemsettings = $SystemsettingsTable->findAsArray();
        $this->FRESHNESS_THRESHOLD_ADDITION = (int)$this->_systemsettings['MONITORING']['MONITORING.FRESHNESS_THRESHOLD_ADDITION'];

        //Loading external tasks of plugins and 3rd party modules
        $this->loadExternTasks();

        //Loading distributed Monitoring support, if plugin is loaded
        $this->dm = Plugin::isLoaded('DistributeModule');

        /** @var TimeperiodsTable $TimeperiodsTable */
        $TimeperiodsTable = TableRegistry::getTableLocator()->get('Timeperiods');
        /** @var CommandsTable $CommandsTable */
        $CommandsTable = TableRegistry::getTableLocator()->get('Commands');

        $this->TimeperiodUuidsCache = new KeyValueStore();
        $this->TimeperiodUuidsCache->setArray($TimeperiodsTable->getAllTimeperiodsUuidsAsList());

        $this->CommandUuidsCache = new KeyValueStore();
        $this->CommandUuidsCache->setArray($CommandsTable->getAllCommandsUuidsAsList());

        $this->HosttemplateHostgroupsCache = new KeyValueStore();

        if ($this->dm === true) {
            $this->dmConfig = [];

            //Loading external Model
            /** @var \DistributeModule\Model\Table\SatellitesTable $SatellitesTable */
            $SatellitesTable = TableRegistry::getTableLocator()->get('DistributeModule.Satellites');
            $query = $SatellitesTable->find()
                ->where([
                    'Satellites.sync_instance' => 1
                ])
                ->all();

            $satellites = $query->toArray();

            $this->Satellites = $satellites;

            //Create default config folder for sat systems
            if (!is_dir($this->conf['satellite_path'])) {
                mkdir($this->conf['satellite_path']);
            }

            //Create rollout folder
            if (!is_dir($this->conf['rollout'])) {
                mkdir($this->conf['rollout']);
            }

            foreach ($this->Satellites as $satellite) {
                if (!is_dir($this->conf['satellite_path'] . $satellite->get('id'))) {
                    mkdir($this->conf['satellite_path'] . $satellite->get('id'));
                }

                if (!is_dir($this->conf['satellite_path'] . $satellite->get('id') . DS . $this->conf['config'])) {
                    mkdir($this->conf['satellite_path'] . $satellite->get('id') . DS . $this->conf['config']);
                }
            }

        }
    }

    /**
     * @param null|string $uuid
     */
    public function exportCommands($uuid = null) {
        /** @var CommandsTable $CommandsTable */
        $CommandsTable = TableRegistry::getTableLocator()->get('Commands');

        if ($uuid !== null) {
            $commands = [];
            $commands[] = $CommandsTable->getCommandByUuid($uuid);
        } else {
            $commands = $CommandsTable->getAllCommands();
        }

        if (!is_dir($this->conf['path'] . $this->conf['commands'])) {
            mkdir($this->conf['path'] . $this->conf['commands']);
        }

        if ($this->conf['minified'] == true) {
            $file = new File($this->conf['path'] . $this->conf['commands'] . 'commands_minified' . $this->conf['suffix']);
            if (!$file->exists()) {
                $file->create();
            }
            $content = $this->fileHeader();
        }

        foreach ($commands as $command) {
            if (!empty($command['Command']['command_line'])) {

                if ($this->conf['minified'] == false) {
                    $file = new File($this->conf['path'] . $this->conf['commands'] . $command['Command']['uuid'] . $this->conf['suffix']);
                    $content = $this->fileHeader();
                    if (!$file->exists()) {
                        $file->create();
                    }
                }


                $content .= $this->addContent('define command{', 0);
                $content .= $this->addContent('command_name', 1, $command['Command']['uuid']);
                $content .= $this->addContent('command_line', 1, $command['Command']['command_line']);
                $content .= $this->addContent('}', 0);

                if ($this->conf['minified'] == false) {
                    $file->write($content);
                    $file->close();
                }
            }
        }

        if ($this->conf['minified'] == true) {
            $file->write($content);
            $file->close();
        }
    }

    /**
     * @param null|string $uuid
     */
    public function exportContacts($uuid = null) {
        /** @var ContactsTable $ContactsTable */
        $ContactsTable = TableRegistry::getTableLocator()->get('Contacts');
        $contacts = $ContactsTable->getContactsForExport();


        if (!is_dir($this->conf['path'] . $this->conf['contacts'])) {
            mkdir($this->conf['path'] . $this->conf['contacts']);
        }


        if ($this->conf['minified']) {
            $file = new File($this->conf['path'] . $this->conf['contacts'] . 'contacts_minified' . $this->conf['suffix']);
            if (!$file->exists()) {
                $file->create();
            }
            $content = $this->fileHeader();
        }

        foreach ($contacts as $contact) {
            /** @var \App\Model\Entity\Contact $contact */
            if (!$this->conf['minified']) {
                $file = new File($this->conf['path'] . $this->conf['contacts'] . $contact['Contact']['uuid'] . $this->conf['suffix']);
                $content = $this->fileHeader();
                if (!$file->exists()) {
                    $file->create();
                }
            }

            $content .= $this->addContent('define contact{', 0);
            $content .= $this->addContent('contact_name', 1, $contact->get('uuid'));
            $content .= $this->addContent('alias', 1, $this->escapeLastBackslash($contact->get('description')));
            $content .= $this->addContent('host_notifications_enabled', 1, $contact->get('host_notifications_enabled'));
            $content .= $this->addContent('service_notifications_enabled', 1, $contact->get('service_notifications_enabled'));
            $content .= $this->addContent('host_notification_period', 1, $contact->get('host_timeperiod')->get('uuid'));
            $content .= $this->addContent('service_notification_period', 1, $contact->get('service_timeperiod')->get('uuid'));
            $content .= $this->addContent('host_notification_commands', 1, $contact->getHostCommandsForCfg());
            $content .= $this->addContent('service_notification_commands', 1, $contact->getServiceCommandsForCfg());
            $content .= $this->addContent('host_notification_options', 1, $contact->getHostNotificationOptionsForCfg());
            $content .= $this->addContent('service_notification_options', 1, $contact->getServiceNotificationOptionsForCfg());
            if (!empty($contact->get('email'))) {
                $content .= $this->addContent('email', 1, $contact->get('email'));
            }
            if (!empty($contact->get('phone'))) {
                $content .= $this->addContent('pager', 1, $contact->get('phone'));
            }

            if ($contact->hasCustomvariables()) {
                $content .= PHP_EOL;
                $content .= $this->addContent(';Custom  variables:', 1);
                foreach ($contact->getCustomvariablesForCfg() as $varName => $varValue) {
                    $content .= $this->addContent($varName, 1, $varValue);
                }
            }

            if (!empty($contact['Contact']['user_id'])) {
                $content .= $this->addContent(';OITC user association:', 1);
                $content .= $this->addContent('_OITCUSERID', 1, $contact['Contact']['user_id']);
            }

            $content .= $this->addContent('}', 0);
            if (!$this->conf['minified']) {
                $file->write($content);
                $file->close();
            }
        }

        if ($this->conf['minified']) {
            $file->write($content);
            $file->close();
        }
    }

    /**
     * @param null|string $uuid
     */
    public function exportContactgroups($uuid = null) {
        /** @var ContactgroupsTable $ContactgroupsTable */
        $ContactgroupsTable = TableRegistry::getTableLocator()->get('Contactgroups');
        $contactgroups = $ContactgroupsTable->getContactgroupsForExport($uuid);


        if (!is_dir($this->conf['path'] . $this->conf['contactgroups'])) {
            mkdir($this->conf['path'] . $this->conf['contactgroups']);
        }

        if ($this->conf['minified']) {
            $file = new File($this->conf['path'] . $this->conf['contactgroups'] . 'contactgroups_minified' . $this->conf['suffix']);
            if (!$file->exists()) {
                $file->create();
            }
            $content = $this->fileHeader();
        }


        foreach ($contactgroups as $contactgroup) {
            /** @var \App\Model\Entity\Contactgroup $contactgroup */

            if (!$this->conf['minified']) {
                $file = new File($this->conf['path'] . $this->conf['contactgroups'] . $contactgroup->uuid . $this->conf['suffix']);
                $content = $this->fileHeader();
                if (!$file->exists()) {
                    $file->create();
                }
            }

            $content .= $this->addContent('define contactgroup{', 0);
            $content .= $this->addContent('contactgroup_name', 1, $contactgroup->uuid);
            $content .= $this->addContent('alias', 1, $this->escapeLastBackslash(
                $contactgroup->getDescriptionForCfg()
            ));
            if ($contactgroup->hasContacts()) {
                $content .= $this->addContent('members', 1, $contactgroup->getContactsForCfg());
            }
            $content .= $this->addContent('}', 0);
            if (!$this->conf['minified']) {
                $file->write($content);
                $file->close();
            }

        }
        if ($this->conf['minified']) {
            $file->write($content);
            $file->close();
        }
    }

    /**
     * @param null|string $uuid
     */
    public function exportHosttemplates($uuid = null) {
        /** @var HosttemplatesTable $HosttemplatesTable */
        $HosttemplatesTable = TableRegistry::getTableLocator()->get('Hosttemplates');
        $hosttemplates = $HosttemplatesTable->getHosttemplatesForExport($uuid);

        if (!is_dir($this->conf['path'] . $this->conf['hosttemplates'])) {
            mkdir($this->conf['path'] . $this->conf['hosttemplates']);
        }

        if ($this->conf['minified']) {
            $file = new File($this->conf['path'] . $this->conf['hosttemplates'] . 'hosttemplates_minified' . $this->conf['suffix']);
            if (!$file->exists()) {
                $file->create();
            }
            $content = $this->fileHeader();
        }

        foreach ($hosttemplates as $hosttemplate) {
            /** @var \App\Model\Entity\Hosttemplate $hosttemplate */
            if (!$this->conf['minified']) {
                $file = new File($this->conf['path'] . $this->conf['hosttemplates'] . $hosttemplate->get('uuid') . $this->conf['suffix']);
                $content = $this->fileHeader();
                if (!$file->exists()) {
                    $file->create();
                }
            }

            $content .= $this->addContent('define host{', 0);
            $content .= $this->addContent('register', 1, 0);
            $content .= $this->addContent('use', 1, '8147201e91c4dcf7c016ba2ddeac3fd7e72edacc');
            $content .= $this->addContent('host_name', 1, $hosttemplate->get('uuid'));
            $content .= $this->addContent('name', 1, $hosttemplate->get('uuid'));
            $content .= $this->addContent('display_name', 1, $this->escapeLastBackslash(
                $hosttemplate->get('name')
            ));
            $content .= $this->addContent('alias', 1, $this->escapeLastBackslash(
                $hosttemplate->get('description')
            ));

            $content .= PHP_EOL;
            $content .= $this->addContent(';Check settings:', 1);

            if ($hosttemplate->hasHosttemplatecommandargumentvalues()) {
                $content .= $this->addContent('check_command', 1, $hosttemplate->get('check_command')->get('uuid') . '!' . $hosttemplate->getHosttemplatecommandargumentvaluesForCfg());
            } else {
                $content .= $this->addContent('check_command', 1, $hosttemplate->get('check_command')->get('uuid'));
            }


            $content .= $this->addContent('initial_state', 1, $this->_systemsettings['MONITORING']['MONITORING.HOST.INITSTATE']);
            $content .= $this->addContent('check_period', 1, $hosttemplate->get('check_period')->get('uuid'));
            $content .= $this->addContent('check_interval', 1, $hosttemplate->get('check_interval'));
            $content .= $this->addContent('retry_interval', 1, $hosttemplate->get('retry_interval'));
            $content .= $this->addContent('max_check_attempts', 1, $hosttemplate->get('max_check_attempts'));
            $content .= $this->addContent('active_checks_enabled', 1, $hosttemplate->get('active_checks_enabled'));
            $content .= $this->addContent('passive_checks_enabled', 1, 1);


            $content .= PHP_EOL;
            $content .= $this->addContent(';Notification settings:', 1);
            $content .= $this->addContent('notifications_enabled', 1, 1);

            if ($hosttemplate->hasContacts()) {
                $content .= $this->addContent('contacts', 1, $hosttemplate->getContactsForCfg());
            }


            if ($hosttemplate->hasContactgroups()) {
                $content .= $this->addContent('contact_groups', 1, $hosttemplate->getContactgroupsForCfg());
            }

            $content .= $this->addContent('notification_interval', 1, $hosttemplate->get('notification_interval'));
            $content .= $this->addContent('notification_period', 1, $hosttemplate->get('notify_period')->get('uuid'));

            $content .= $this->addContent('notification_options', 1, $hosttemplate->getHostNotificationOptionsForCfg());

            $content .= PHP_EOL;
            $content .= $this->addContent(';Flap detection settings:', 1);
            $content .= $this->addContent('flap_detection_enabled', 1, $hosttemplate->get('flap_detection_enabled'));
            if ($hosttemplate->get('flap_detection_enabled') === 1) {
                if ($hosttemplate->getHostFlapDetectionOptionsForCfg()) {
                    $content .= $this->addContent('flap_detection_options', 1, $hosttemplate->getHostFlapDetectionOptionsForCfg());
                }
            }

            $content .= PHP_EOL;
            $content .= $this->addContent(';Everything else:', 1);
            $content .= $this->addContent('process_perf_data', 1, $hosttemplate->get('process_performance_data'));

            if (!empty($hosttemplate->get('notes')))
                $content .= $this->addContent('notes', 1, $this->escapeLastBackslash($hosttemplate->get('notes')));


            if ($hosttemplate->hasCustomvariables()) {
                $content .= PHP_EOL;
                $content .= $this->addContent(';Custom  variables:', 1);
                foreach ($hosttemplate->getCustomvariablesForCfg() as $varName => $varValue) {
                    $content .= $this->addContent($varName, 1, $this->escapeLastBackslash($varValue));
                }
            }
            $content .= $this->addContent('}', 0);

            if (!$this->conf['minified']) {
                $file->write($content);
                $file->close();
            }

        }

        if ($this->conf['minified']) {
            $file->write($content);
            $file->close();
        }
    }

    /**
     * @param null|string $uuid
     */
    public function exportHosts($uuid = null) {
        /** @var $HostsTable HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');

        if ($uuid !== null) {
            $hosts = $HostsTable->getHostsForExport(null, null, $uuid);
        } else {
            //Multiple queries are faster than one big query
            $hostCount = $HostsTable->getHostsCountForExport();
            $chunk = 200;
            $queryCount = ceil($hostCount / $chunk);
            $hosts = [];
            for ($i = 0; $i < $queryCount; $i++) {
                $_hosts = $HostsTable->getHostsForExport($chunk, ($chunk * $i));
                foreach ($_hosts as $_host) {
                    $hosts[] = $_host;
                }
                unset($_hosts);
            }
        }

        if (!is_dir($this->conf['path'] . $this->conf['hosts'])) {
            mkdir($this->conf['path'] . $this->conf['hosts']);
        }

        if ($this->conf['minified']) {
            $fileName = $this->conf['path'] . $this->conf['hosts'] . 'hosts_minified' . $this->conf['suffix'];
            $file = new File($fileName);
            if (!$file->exists()) {
                $file->create();
            }
            $content = $this->fileHeader();
        }

        /** @var HosttemplatesTable $HosttemplatesTable */
        $HosttemplatesTable = TableRegistry::getTableLocator()->get('Hosttemplates');

        $HosttemplatesCache = new KeyValueStore();

        foreach ($hosts as $host) {
            /** @var $host \App\Model\Entity\Host */

            if (!$this->conf['minified']) {
                $file = new File($this->conf['path'] . $this->conf['hosts'] . $host->get('uuid') . $this->conf['suffix']);
                $content = $this->fileHeader();
                if (!$file->exists()) {
                    $file->create();
                }
            }

            if (!$HosttemplatesCache->has($host->get('hosttemplate_id'))) {
                $hosttemplate = $HosttemplatesTable->get($host->get('hosttemplate_id'));
                $HosttemplatesCache->set($host->get('hosttemplate_id'), $hosttemplate);
            }

            /** @var \App\Model\Entity\Hosttemplate $hosttemplate */
            $hosttemplate = $HosttemplatesCache->get($host->get('hosttemplate_id'));


            $content .= $this->addContent('define host{', 0);
            $content .= $this->addContent('use', 1, $hosttemplate->get('uuid'));
            $content .= $this->addContent('host_name', 1, $host->get('uuid'));
            $content .= $this->addContent('display_name', 1, $this->escapeLastBackslash($host->get('name')));
            $content .= $this->addContent('address', 1, $this->escapeLastBackslash($host->get('address')));


            if (!empty($host->get('description')))
                $content .= $this->addContent('alias', 1, $this->escapeLastBackslash($host->get('description')));

            if ($host->hasParentHostsForExport()) {
                $content .= $this->addContent('parents', 1, $host->getParentHostsForCfg());
            }

            $content .= PHP_EOL;
            $content .= $this->addContent(';Check settings:', 1);
            $commandarguments = null;
            if ($host->isSatelliteHost() === false) {
                if (!empty($host->get('hostcommandargumentvalues'))) {
                    if ($host->get('command_id') === null) {
                        //Host has own command arguments but uses the same command as the host template
                        $commandId = $hosttemplate->get('command_id');
                    } else {
                        //Host has own command arguments AND own check command
                        $commandId = $host->get('command_id');
                    }

                    $commandUuid = $this->CommandUuidsCache->get($commandId);

                    $commandarguments = $host->getCommandargumentValuesForCfg();
                    $content .= $this->addContent('check_command', 1, sprintf(
                        '%s!%s; %s',
                        $commandUuid,
                        implode('!', Hash::extract($commandarguments, '{n}.value')),
                        implode('!', Hash::extract($commandarguments, '{n}.human_name'))
                    ));
                } else {
                    //May be check command without arguments
                    if ($host->get('command_id') !== null) {
                        //Host has own check command but this command has no arguments at all
                        $commandId = $host->get('command_id');
                        $commandUuid = $this->CommandUuidsCache->get($commandId);
                        $content .= $this->addContent('check_command', 1, $commandUuid);
                    }
                }
            }

            if ($host->isSatelliteHost() === true) {
                //Host check fresh command
                $content .= $this->addContent('check_command', 1, '2106cf0bf26a82af262c4078e6d9f94eded84d2a');
            }

            if ($host->get('check_period_id')) {
                $timeperiodUuid = $this->TimeperiodUuidsCache->get($host->get('check_period_id'));
                $content .= $this->addContent('check_period', 1, $timeperiodUuid);
            }

            if ($host->get('check_interval') !== null && $host->get('check_interval') !== '') {
                $content .= $this->addContent('check_interval', 1, $host->get('check_interval'));
            }

            if ($host->get('retry_interval') !== null && $host->get('retry_interval') !== '') {
                $content .= $this->addContent('retry_interval', 1, $host->get('retry_interval'));
            }

            if ($host->get('max_check_attempts') !== null && $host->get('max_check_attempts') !== '') {
                $content .= $this->addContent('max_check_attempts', 1, $host->get('max_check_attempts'));
            }

            if ($host->isSatelliteHost() === true) {
                //Host is on a Satellite - so it's passive on the master instance
                $content .= $this->addContent('active_checks_enabled', 1, 0);
            } else {
                if ($host->get('active_checks_enabled')) {
                    $content .= $this->addContent('active_checks_enabled', 1, $host->get('active_checks_enabled'));
                }
            }


            $checkInterval = $host->get('check_interval');
            if ($checkInterval === null || $checkInterval === '') {
                $checkInterval = $hosttemplate->get('check_interval');
            }

            if ($checkInterval === 0 || $checkInterval === null) {
                //Default check interval - just in case...
                $checkInterval = 300;
            }


            if ($host->get('freshness_checks_enabled') !== null && $host->get('freshness_threshold')) {
                if ($host->isSatelliteHost() === true) {
                    //Host gets checked through a satellite system
                    $content .= $this->addContent('check_freshness', 1, 1);
                    $content .= $this->addContent('freshness_threshold', 1, (int)$host->get('freshness_threshold') + $checkInterval + $this->FRESHNESS_THRESHOLD_ADDITION);
                } else {
                    if ($host->get('freshness_checks_enabled') > 0) {
                        //Passive host on the master system
                        $content .= $this->addContent('check_freshness', 1, 1);
                        $content .= $this->addContent('freshness_threshold', 1, (int)$host->get('freshness_threshold') + $this->FRESHNESS_THRESHOLD_ADDITION);
                    }
                }
            } else {
                /*
                 * NOTICE:
                 * At the moment the host has no freshness_checks_enabled and freshness_threshold field.
                 * This will be available in one of the next versions...
                 *
                 * So this is a little workaround!!!
                 * We only add the freshness for hosts on SAT-Systems! Normal hosts can't have this option at the moment!
                 */
                if ($host->isSatelliteHost()) {
                    $content .= $this->addContent('check_freshness', 1, 1);
                    $content .= $this->addContent('freshness_threshold', 1, $checkInterval + $this->FRESHNESS_THRESHOLD_ADDITION);
                }
            }


            if ($host->get('passive_checks_enabled') !== null && $host->get('passive_checks_enabled') !== '')
                $content .= $this->addContent('passive_checks_enabled', 1, $host->get('passive_checks_enabled'));


            $content .= PHP_EOL;
            $content .= $this->addContent(';Notification settings:', 1);

            if ($host->get('notifications_enabled') !== null && $host->get('notifications_enabled') !== '')
                $content .= $this->addContent('notifications_enabled', 1, $host->get('notifications_enabled'));

            if (!empty($host->get('contacts')))
                $content .= $this->addContent('contacts', 1, $host->getContactsforCfg());

            if (!empty($host->get('contactgroups'))) {
                $content .= $this->addContent('contact_groups', 1, $host->getContactgroupsforCfg());
            }

            if ($host->get('notification_interval') !== null && $host->get('notification_interval') !== '')
                $content .= $this->addContent('notification_interval', 1, $host->get('notification_interval'));

            if ($host->get('notify_period_id')) {
                $timeperiodUuid = $this->TimeperiodUuidsCache->get($host->get('notify_period_id'));
                $content .= $this->addContent('notification_period', 1, $timeperiodUuid);
            }

            if (strlen($host->getNotificationOptionsForCfg($hosttemplate)) > 0) {
                $content .= $this->addContent('notification_options', 1, $host->getNotificationOptionsForCfg($hosttemplate));
            }

            $content .= PHP_EOL;
            $content .= $this->addContent(';Flap detection settings:', 1);

            if ($host->get('flap_detection_enabled') === 1 || $host->get('flap_detection_enabled') === 0)
                $content .= $this->addContent('flap_detection_enabled', 1, $host->get('flap_detection_enabled'));

            if ($host->get('flap_detection_enabled') === 1 && strlen($host->getFlapdetectionOptionsForCfg($hosttemplate)) > 0) {
                $content .= $this->addContent('flap_detection_options', 1, $host->getFlapdetectionOptionsForCfg($hosttemplate));
            }

            $content .= PHP_EOL;
            $content .= $this->addContent(';Everything else:', 1);

            if ($host->get('process_performance_data') === 1 || $host->get('process_performance_data') === 0)
                $content .= $this->addContent('process_perf_data', 1, $host->get('process_performance_data'));

            if ($host->get('notes') && strlen($host->get('notes')) > 0) {
                $content .= $this->addContent('notes', 1, $this->escapeLastBackslash($host->get('notes')));
            }

            if (!$this->HosttemplateHostgroupsCache->has($host->get('hosttemplate_id'))) {
                //Load hostgroups of the host template
                $hostgroups = [];
                $result = $HosttemplatesTable->getHostgroupsByHosttemplateId($host->get('hosttemplate_id'));
                if (!empty($result['hostgroups'])) {
                    $hostgroups = \Cake\Utility\Hash::extract($result['hostgroups'], '{n}.uuid');
                }

                $this->HosttemplateHostgroupsCache->set($host->get('hosttemplate_id'), implode(',', $hostgroups));
            }
            $hosttemplateHostgroupsForCfg = $this->HosttemplateHostgroupsCache->get($host->get('hosttemplate_id'));

            if (!empty($host->get('hostgroups'))) {
                //Use host groups of the host
                $content .= PHP_EOL;
                $content .= $this->addContent(';Hostgroup memberships:', 1);
                $content .= $this->addContent('hostgroups', 1, $host->getHostgroupsForCfg());
            } else if (empty($host->get('hostgroups')) && strlen($hosttemplateHostgroupsForCfg) > 0) {
                //Use host groups of host template configuration
                $content .= PHP_EOL;
                $content .= $this->addContent(';Hostgroup memberships:', 1);
                $content .= $this->addContent('hostgroups', 1, $hosttemplateHostgroupsForCfg);
            }

            if ($host->hasCustomvariables()) {
                $content .= PHP_EOL;
                $content .= $this->addContent(';Custom  variables:', 1);
                foreach ($host->getCustomvariablesForCfg() as $varName => $varValue) {
                    $content .= $this->addContent($varName, 1, $varValue);
                }
            }

            $content .= $this->addContent('}', 0);

            if (!$this->conf['minified']) {
                $file->write($content);
                $file->close();
            }


            if ($this->dm === true && $host->isSatelliteHost()) {
                //Generate config file for sat nagios
                $this->exportSatHost($host, $HosttemplatesTable, $HosttemplatesCache);
            }
        }
        if ($this->conf['minified']) {
            $file->write($content);
            $file->close();
        }

        if ($this->dm === true) {
            $this->exportSatHostgroups();
        }

        $this->deleteHostPerfdata();
    }

    /**
     * @param \App\Model\Entity\Host $host
     * @param HosttemplatesTable $HosttemplatesTable
     * @param KeyValueStore $HosttemplatesCache
     */
    private function exportSatHost(\App\Model\Entity\Host $host, HosttemplatesTable $HosttemplatesTable, KeyValueStore $HosttemplatesCache) {
        $satelliteId = $host->get('satellite_id');

        /** @var \App\Model\Entity\Hosttemplate $hosttemplate */
        $hosttemplate = $HosttemplatesCache->get($host->get('hosttemplate_id'));

        if (!is_dir($this->conf['satellite_path'] . $satelliteId . DS . $this->conf['hosts'])) {
            mkdir($this->conf['satellite_path'] . $satelliteId . DS . $this->conf['hosts']);
        }

        if (!$this->conf['minified']) {
            $file = new File($this->conf['satellite_path'] . $satelliteId . DS . $this->conf['hosts'] . $host->get('uuid') . $this->conf['suffix']);
            $content = $this->fileHeader();
        } else {
            $file = new File($this->conf['satellite_path'] . $satelliteId . DS . $this->conf['hosts'] . 'hosts_minified' . $this->conf['suffix']);
            $content = '';
        }

        if (!$file->exists()) {
            $file->create();
        }

        $content .= $this->addContent('define host{', 0);
        $content .= $this->addContent('use', 1, $hosttemplate->get('uuid'));
        $content .= $this->addContent('host_name', 1, $host->get('uuid'));
        $content .= $this->addContent('display_name', 1, $this->escapeLastBackslash($host->get('name')));
        $content .= $this->addContent('address', 1, $this->escapeLastBackslash($host->get('address')));


        if ($host->get('description'))
            $content .= $this->addContent('alias', 1, $this->escapeLastBackslash($host->get('description')));

        $parenthosts = $host->getParentHostsForSatCfgAsArray();
        if (!empty($parenthosts)) {
            $content .= $this->addContent('parents', 1, implode(',', $parenthosts));
        }

        $content .= PHP_EOL;
        $content .= $this->addContent(';Check settings:', 1);

        if (!empty($host->get('hostcommandargumentvalues'))) {
            if ($host->get('command_id') === null) {
                //Host has own command arguments but uses the same command as the host template
                $commandId = $hosttemplate->get('command_id');
            } else {
                //Host has own command arguments AND own check command
                $commandId = $host->get('command_id');
            }

            $commandUuid = $this->CommandUuidsCache->get($commandId);
            $commandarguments = $host->getCommandargumentValuesForCfg();
            $content .= $this->addContent('check_command', 1, sprintf(
                '%s!%s; %s',
                $commandUuid,
                implode('!', Hash::extract($commandarguments, '{n}.value')),
                implode('!', Hash::extract($commandarguments, '{n}.human_name'))
            ));
        } else {
            //May be check command without arguments
            if ($host->get('command_id') !== null) {
                //Host has own check command but this command has no arguments at all
                $commandId = $host->get('command_id');
                $commandUuid = $this->CommandUuidsCache->get($commandId);
                $content .= $this->addContent('check_command', 1, $commandUuid);
            }
        }

        if ($host->get('check_period_id')) {
            $timeperiodUuid = $this->TimeperiodUuidsCache->get($host->get('check_period_id'));
            $content .= $this->addContent('check_period', 1, $timeperiodUuid);
        }

        if ($host->get('check_interval') !== null && $host->get('check_interval') !== '') {
            $content .= $this->addContent('check_interval', 1, $host->get('check_interval'));
        }

        if ($host->get('retry_interval') !== null && $host->get('retry_interval') !== '') {
            $content .= $this->addContent('retry_interval', 1, $host->get('retry_interval'));
        }

        if ($host->get('max_check_attempts') !== null && $host->get('max_check_attempts') !== '') {
            $content .= $this->addContent('max_check_attempts', 1, $host->get('max_check_attempts'));
        }

        if ($host->get('active_checks_enabled') !== null && $host->get('active_checks_enabled') !== '')
            $content .= $this->addContent('active_checks_enabled', 1, $host->get('active_checks_enabled'));

        if ($host->get('passive_checks_enabled') !== null && $host->get('passive_checks_enabled') !== '')
            $content .= $this->addContent('passive_checks_enabled', 1, $host->get('passive_checks_enabled'));

        $content .= PHP_EOL;
        $content .= $this->addContent(';Notification settings:', 1);

        if ($host->get('notifications_enabled') !== null && $host->get('notifications_enabled') !== '')
            $content .= $this->addContent('notifications_enabled', 1, $host->get('notifications_enabled'));

        if (!empty($host->get('contacts')))
            $content .= $this->addContent('contacts', 1, $host->getContactsforCfg());

        if (!empty($host->get('contactgroups'))) {
            $content .= $this->addContent('contact_groups', 1, $host->getContactgroupsforCfg());
        }

        if ($host->get('notification_interval') !== null && $host->get('notification_interval') !== '')
            $content .= $this->addContent('notification_interval', 1, $host->get('notification_interval'));

        if ($host->get('notify_period_id')) {
            $timeperiodUuid = $this->TimeperiodUuidsCache->get($host->get('notify_period_id'));
            $content .= $this->addContent('notification_period', 1, $timeperiodUuid);
        }

        if (strlen($host->getNotificationOptionsForCfg($hosttemplate)) > 0) {
            $content .= $this->addContent('notification_options', 1, $host->getNotificationOptionsForCfg($hosttemplate));
        }

        $content .= PHP_EOL;
        $content .= $this->addContent(';Flap detection settings:', 1);

        if ($host->get('flap_detection_enabled') === 1 || $host->get('flap_detection_enabled') === 0)
            $content .= $this->addContent('flap_detection_enabled', 1, $host->get('flap_detection_enabled'));

        if ($host->get('flap_detection_enabled') === 1 && strlen($host->getFlapdetectionOptionsForCfg($hosttemplate)) > 0) {
            $content .= $this->addContent('flap_detection_options', 1, $host->getFlapdetectionOptionsForCfg($hosttemplate));
        }

        $content .= PHP_EOL;
        $content .= $this->addContent(';Everything else:', 1);

        if ($host->get('process_performance_data') === 1 || $host->get('process_performance_data') === 0)
            $content .= $this->addContent('process_perf_data', 1, $host->get('process_performance_data'));

        if ($host->get('notes') && strlen($host->get('notes')) > 0) {
            $content .= $this->addContent('notes', 1, $this->escapeLastBackslash($host->get('notes')));
        }

        if (!$this->HosttemplateHostgroupsCache->has($host->get('hosttemplate_id'))) {
            //Load hostgroups of the host template
            $hostgroups = [];
            $result = $HosttemplatesTable->getHostgroupsByHosttemplateId($host->get('hosttemplate_id'));
            if (!empty($result['hostgroups'])) {
                $hostgroups = Hash::extract($result['hostgroups'], '{n}.uuid');
            }

            $this->HosttemplateHostgroupsCache->set($host->get('hosttemplate_id'), implode(',', $hostgroups));
        }
        $hosttemplateHostgroupsForCfg = $this->HosttemplateHostgroupsCache->get($host->get('hosttemplate_id'));

        if (!empty($host->get('hostgroups'))) {
            //Use host groups of the host
            $content .= PHP_EOL;
            $content .= $this->addContent(';Hostgroup memberships:', 1);
            $content .= $this->addContent('hostgroups', 1, $host->getHostgroupsForCfg());
        } else if (empty($host->get('hostgroups')) && strlen($hosttemplateHostgroupsForCfg) > 0) {
            //Use host groups of host template configuration
            $content .= PHP_EOL;
            $content .= $this->addContent(';Hostgroup memberships:', 1);
            $content .= $this->addContent('hostgroups', 1, $hosttemplateHostgroupsForCfg);
        }

        if ($host->hasCustomvariables()) {
            $content .= PHP_EOL;
            $content .= $this->addContent(';Custom  variables:', 1);
            foreach ($host->getCustomvariablesForCfg() as $varName => $varValue) {
                $content .= $this->addContent($varName, 1, $varValue);
            }
        }
        $content .= $this->addContent('}', 0);

        if (!$this->conf['minified']) {
            $file->write($content);
        } else {
            $file->append($content);
        }
        $file->close();
    }

    /**
     * @param null|string $uuid
     */
    public function exportServicetemplates($uuid = null) {
        /** @var ServicetemplatesTable $ServicetemplatesTable */
        $ServicetemplatesTable = TableRegistry::getTableLocator()->get('Servicetemplates');
        $servicetemplates = $ServicetemplatesTable->getServicetemplatesForExport($uuid);

        if (!is_dir($this->conf['path'] . $this->conf['servicetemplates'])) {
            mkdir($this->conf['path'] . $this->conf['servicetemplates']);
        }

        if ($this->conf['minified']) {
            $file = new File($this->conf['path'] . $this->conf['servicetemplates'] . 'servicetemplates_minified' . $this->conf['suffix']);
            if (!$file->exists()) {
                $file->create();
            }
            $content = $this->fileHeader();
        }

        foreach ($servicetemplates as $servicetemplate) {
            /** @var \App\Model\Entity\Servicetemplate $servicetemplate */

            if (!$this->conf['minified']) {
                $file = new File($this->conf['path'] . $this->conf['servicetemplates'] . $servicetemplate->get('uuid') . $this->conf['suffix']);
                $content = $this->fileHeader();
                if (!$file->exists()) {
                    $file->create();
                }
            }

            $content .= $this->addContent('define service{', 0);
            $content .= $this->addContent('register', 1, 0);
            $content .= $this->addContent('use', 1, '689bfdd01af8a21c4a4706c5117849c2fc2c3f38');
            $content .= $this->addContent('name', 1, $servicetemplate->get('uuid'));
            $content .= $this->addContent('display_name', 1, $this->escapeLastBackslash(
                $servicetemplate->get('name')
            ));
            $content .= $this->addContent('service_description', 1, $servicetemplate->get('uuid'));

            $content .= PHP_EOL;
            $content .= $this->addContent(';Check settings:', 1);

            if ($servicetemplate->hasServicetemplatecommandargumentvalues()) {
                $content .= $this->addContent('check_command', 1, $servicetemplate->get('check_command')->get('uuid') . '!' . $servicetemplate->getServicetemplatecommandargumentvaluesForCfg());
            } else {
                $content .= $this->addContent('check_command', 1, $servicetemplate->get('check_command')->get('uuid'));
            }


            $content .= $this->addContent('initial_state', 1, $this->_systemsettings['MONITORING']['MONITORING.SERVICE.INITSTATE']);
            $content .= $this->addContent('check_period', 1, $servicetemplate->get('check_period')->get('uuid'));
            $content .= $this->addContent('check_interval', 1, $servicetemplate->get('check_interval'));
            $content .= $this->addContent('retry_interval', 1, $servicetemplate->get('retry_interval'));
            $content .= $this->addContent('max_check_attempts', 1, $servicetemplate->get('max_check_attempts'));
            $content .= $this->addContent('active_checks_enabled', 1, $servicetemplate->get('active_checks_enabled'));
            $content .= $this->addContent('passive_checks_enabled', 1, 1);

            if ($servicetemplate->get('freshness_checks_enabled') > 0) {
                $content .= $this->addContent('check_freshness', 1, 1);

                if ((int)$servicetemplate->get('freshness_threshold') > 0) {
                    $content .= $this->addContent('freshness_threshold', 1, (int)$servicetemplate->get('freshness_threshold') + $this->FRESHNESS_THRESHOLD_ADDITION);
                }
            }


            $content .= PHP_EOL;
            $content .= $this->addContent(';Notification settings:', 1);
            $content .= $this->addContent('notifications_enabled', 1, 1);

            if ($servicetemplate->hasContacts()) {
                $content .= $this->addContent('contacts', 1, $servicetemplate->getContactsForCfg());
            }

            if ($servicetemplate->hasContactgroups()) {
                $content .= $this->addContent('contact_groups', 1, $servicetemplate->getContactgroupsForCfg());
            }

            $content .= $this->addContent('notification_interval', 1, $servicetemplate->get('notification_interval'));
            $content .= $this->addContent('notification_period', 1, $servicetemplate->get('notify_period')->get('uuid'));

            $content .= $this->addContent('notification_options', 1, $servicetemplate->getServiceNotificationOptionsForCfg());

            $content .= PHP_EOL;
            $content .= $this->addContent(';Flap detection settings:', 1);
            $content .= $this->addContent('flap_detection_enabled', 1, $servicetemplate->get('flap_detection_enabled'));
            if ($servicetemplate->get('flap_detection_enabled') === 1) {
                if ($servicetemplate->getServiceFlapDetectionOptionsForCfg()) {
                    $content .= $this->addContent('flap_detection_options', 1, $servicetemplate->getServiceFlapDetectionOptionsForCfg());
                }
            }

            $content .= PHP_EOL;
            $content .= $this->addContent(';Everything else:', 1);
            $content .= $this->addContent('process_perf_data', 1, $servicetemplate->get('process_performance_data'));

            $content .= $this->addContent('is_volatile', 1, (int)$servicetemplate->get('is_volatile'));

            if (!empty($servicetemplate->get('notes')))
                $content .= $this->addContent('notes', 1, $this->escapeLastBackslash($servicetemplate->get('notes')));


            if ($servicetemplate->hasEventhandler()) {
                $content .= PHP_EOL;
                $content .= $this->addContent(';Event handler:', 1);

                if ($servicetemplate->hasServicetemplateeventcommandargumentvalues()) {
                    $content .= $this->addContent('check_command', 1, $servicetemplate->get('check_command')->get('uuid') . '!' . $servicetemplate->getServicetemplateeventcommandargumentvaluesForCfg());
                } else {
                    $content .= $this->addContent('check_command', 1, $servicetemplate->get('check_command')->get('uuid'));
                }
            }


            if ($servicetemplate->hasCustomvariables()) {
                $content .= PHP_EOL;
                $content .= $this->addContent(';Custom  variables:', 1);
                foreach ($servicetemplate->getCustomvariablesForCfg() as $varName => $varValue) {
                    $content .= $this->addContent($varName, 1, $this->escapeLastBackslash($varValue));
                }
            }
            $content .= $this->addContent('}', 0);

            if (!$this->conf['minified']) {
                $file->write($content);
                $file->close();
            }
        }

        if ($this->conf['minified']) {
            $file->write($content);
            $file->close();
        }

    }


    public function exportServices() {
        if (!is_dir($this->conf['path'] . $this->conf['services'])) {
            mkdir($this->conf['path'] . $this->conf['services']);
        }

        if ($this->conf['minified']) {
            $fileName = $this->conf['path'] . $this->conf['services'] . 'services_minified' . $this->conf['suffix'];
            $file = new File($fileName);
            if (!$file->exists()) {
                $file->create();
            }
            $content = $this->fileHeader();
        }

        /** @var HostsTable $HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');
        /** @var ServicesTable $ServicesTable */
        $ServicesTable = TableRegistry::getTableLocator()->get('Services');
        /** @var CommandsTable $CommandsTable */
        $CommandsTable = TableRegistry::getTableLocator()->get('Commands');
        /** @var ServicetemplatesTable $ServicetemplatesTable */
        $ServicetemplatesTable = TableRegistry::getTableLocator()->get('Servicetemplates');

        $ServicetemplatesCache = new KeyValueStore();

        $hosts = $HostsTable->getHostsForServiceExport();
        foreach ($hosts as $host) {
            /** @var Host $host */

            $services = $ServicesTable->getServicesForExportByHostId($host->get('id'));
            foreach ($services as $service) {
                /** @var Service $service */

                if (!$this->conf['minified']) {
                    $file = new File($this->conf['path'] . $this->conf['services'] . $service->get('uuid') . $this->conf['suffix']);
                    $content = $this->fileHeader();
                    if (!$file->exists()) {
                        $file->create();
                    }
                }

                if (!$ServicetemplatesCache->has($service->get('servicetemplate_id'))) {
                    $servicetemplate = $ServicetemplatesTable->getServicetemplateForServiceExport($service->get('servicetemplate_id'));
                    $ServicetemplatesCache->set($service->get('servicetemplate_id'), $servicetemplate);
                }
                /** @var \App\Model\Entity\Servicetemplate $servicetemplate */
                $servicetemplate = $ServicetemplatesCache->get($service->get('servicetemplate_id'));


                $content .= $this->addContent('define service{', 0);
                $content .= $this->addContent('use', 1, $servicetemplate->get('uuid'));
                $content .= $this->addContent('host_name', 1, $host->get('uuid'));

                $content .= $this->addContent('name', 1, $service->get('uuid'));

                if ($service->get('name') !== null && $service->get('name') !== '') {
                    $content .= $this->addContent('display_name', 1, $this->escapeLastBackslash(
                        $service->get('name')
                    ));
                } else {
                    $content .= $this->addContent('display_name', 1, $this->escapeLastBackslash(
                        $servicetemplate->get('name')
                    ));
                }

                $content .= $this->addContent('service_description', 1, $service->get('uuid'));

                $content .= PHP_EOL;
                $content .= $this->addContent(';Check settings:', 1);

                if ($host->isSatelliteHost() === true) {
                    // On the master system we set the freshness check command
                    // UNKNOWN: Service is no longer current
                    $content .= $this->addContent('check_command', 1, '2106cf0bf26a82af262c4078e6d9f94eded84d2a');
                }

                if ($host->isSatelliteHost() === false) {
                    if (!empty($service->get('servicecommandargumentvalues'))) {
                        if ($service->get('command_id') === null) {
                            //Service has own command arguments but uses the same command as the service template
                            $commandId = $servicetemplate->get('command_id');
                        } else {
                            //Service has own command arguments AND own check command
                            $commandId = $service->get('command_id');
                        }
                        $commandUuid = $this->CommandUuidsCache->get($commandId);
                        $commandarguments = $service->getCommandargumentValuesForCfg();
                        $content .= $this->addContent('check_command', 1, sprintf(
                            '%s!%s; %s',
                            $commandUuid,
                            implode('!', Hash::extract($commandarguments, '{n}.value')),
                            implode('!', Hash::extract($commandarguments, '{n}.human_name'))
                        ));
                    } else {
                        //May be check command without arguments
                        if ($service->get('command_id') !== null) {
                            //Service has own check command but this command has no arguments at all
                            $commandId = $service->get('command_id');
                            $commandUuid = $this->CommandUuidsCache->get($commandId);
                            $content .= $this->addContent('check_command', 1, $commandUuid);
                        }
                    }

                    if ($servicetemplate->hasEventhandler() || $service->hasEventhandler()) {
                        $content .= PHP_EOL;
                        $content .= $this->addContent(';Event handler:', 1);

                        if (!empty($service->get('serviceeventcommandargumentvalues'))) {
                            if ($service->get('eventhandler_command_id') === null) {
                                //Service has own command arguments but uses the same event handler command as the service template
                                $commandId = $servicetemplate->get('eventhandler_command_id');
                            } else {
                                //Service has own event handler command arguments AND own event handler command
                                $commandId = $service->get('eventhandler_command_id');
                            }
                            $commandUuid = $this->CommandUuidsCache->get($commandId);
                            $eventcommandarguments = $service->getEventhandlerCommandargumentValuesForCfg();
                            $content .= $this->addContent('event_handler', 1, sprintf(
                                '%s!%s; %s',
                                $commandUuid,
                                implode('!', Hash::extract($eventcommandarguments, '{n}.value')),
                                implode('!', Hash::extract($eventcommandarguments, '{n}.human_name'))
                            ));
                        } else {
                            //May be event handler command without arguments
                            if ($service->get('eventhandler_command_id') !== null) {
                                //Service has own evnet handler command but this command has no arguments at all
                                $commandId = $service->get('eventhandler_command_id');
                                $commandUuid = $this->CommandUuidsCache->get($commandId);
                                $content .= $this->addContent('event_handler', 1, $commandUuid);
                            }
                        }
                    }
                }

                if ($service->get('check_period_id')) {
                    $timeperiodUuid = $this->TimeperiodUuidsCache->get($service->get('check_period_id'));
                    $content .= $this->addContent('check_period', 1, $timeperiodUuid);
                }

                if ($service->get('check_interval') !== null && $service->get('check_interval') !== '')
                    $content .= $this->addContent('check_interval', 1, $service->get('check_interval'));

                if ($service->get('retry_interval') !== null && $service->get('retry_interval') !== '')
                    $content .= $this->addContent('retry_interval', 1, $service->get('retry_interval'));

                if ($service->get('max_check_attempts') !== null && $service->get('max_check_attempts') !== '')
                    $content .= $this->addContent('max_check_attempts', 1, $service->get('max_check_attempts'));


                if ($host->isSatelliteHost() === true) {
                    $content .= $this->addContent('active_checks_enabled', 1, 0);
                    $content .= $this->addContent('passive_checks_enabled', 1, 1);
                }

                if ($host->isSatelliteHost() === false) {
                    if ($service->get('active_checks_enabled') !== null && $service->get('active_checks_enabled') !== '') {
                        $content .= $this->addContent('active_checks_enabled', 1, $service->get('active_checks_enabled'));
                    }

                    if ($service->get('passive_checks_enabled') !== null && $service->get('passive_checks_enabled') !== '') {
                        $content .= $this->addContent('passive_checks_enabled', 1, $service->get('passive_checks_enabled'));
                    }
                }

                if ($service->get('freshness_checks_enabled') > 0 || $host->isSatelliteHost() === true) {
                    if ($host->isSatelliteHost() === true) {
                        // Service is checked by a satellite system
                        // Add freshness check on the master instance
                        $content .= $this->addContent('check_freshness', 1, 1);
                        $checkInterval = $service->get('check_interval');
                        if ($checkInterval === null || $checkInterval === '' || $checkInterval === 0) {
                            $checkInterval = $servicetemplate->get('check_interval');
                        }

                        $checkInterval = (int)$checkInterval;
                        $freshnessThreshold = (int)$service->get('freshness_threshold');

                        $content .= $this->addContent('freshness_threshold', 1, ($freshnessThreshold + $checkInterval + $this->FRESHNESS_THRESHOLD_ADDITION));
                    } else {
                        // Service is in the master instance

                        //Passive service on the master system
                        $content .= $this->addContent('check_freshness', 1, 1);
                        $freshnessThreshold = (int)$service->get('freshness_threshold');
                        if ($freshnessThreshold === null || $freshnessThreshold === '' || $freshnessThreshold === 0) {
                            $freshnessThreshold = (int)$servicetemplate->get('freshness_threshold');
                        }
                        $content .= $this->addContent('freshness_threshold', 1, $freshnessThreshold + $this->FRESHNESS_THRESHOLD_ADDITION);
                    }

                }

                $content .= PHP_EOL;
                $content .= $this->addContent(';Notification settings:', 1);

                if ($service->get('notifications_enabled') !== null && $service->get('notifications_enabled') !== '') {
                    $content .= $this->addContent('notifications_enabled', 1, $service->get('notifications_enabled'));
                }

                if (!empty($service->get('contacts'))) {
                    $content .= $this->addContent('contacts', 1, $service->getContactsforCfg());
                }

                if (!empty($service->get('contactgroups'))) {
                    $content .= $this->addContent('contact_groups', 1, $service->getContactgroupsforCfg());
                }


                if ($service->get('notification_interval') !== null && $service->get('notification_interval') !== '') {
                    $content .= $this->addContent('notification_interval', 1, $service->get('notification_interval'));
                }

                if ($service->get('notify_period_id')) {
                    $timeperiodUuid = $this->TimeperiodUuidsCache->get($service->get('notify_period_id'));
                    $content .= $this->addContent('notification_period', 1, $timeperiodUuid);
                }

                if (strlen($service->getNotificationOptionsForCfg($servicetemplate)) > 0) {
                    $content .= $this->addContent('notification_options', 1, $service->getNotificationOptionsForCfg($servicetemplate));
                }

                $content .= PHP_EOL;
                $content .= $this->addContent(';Flap detection settings:', 1);
                if ($service->get('flap_detection_enabled') === 1 || $service->get('flap_detection_enabled') === 0) {
                    $content .= $this->addContent('flap_detection_enabled', 1, $service->get('flap_detection_enabled'));
                }

                if ($service->get('flap_detection_enabled') === 1 && strlen($service->getFlapdetectionOptionsForCfg($servicetemplate)) > 0) {
                    $content .= $this->addContent('flap_detection_options', 1, $service->getFlapdetectionOptionsForCfg($servicetemplate));
                }

                $content .= PHP_EOL;
                $content .= $this->addContent(';Everything else:', 1);
                if ($service->get('process_performance_data') === 1 || $service->get('process_performance_data') === 0)
                    $content .= $this->addContent('process_perf_data', 1, $service->get('process_performance_data'));

                if ($service->get('notes') && strlen($service->get('notes')) > 0) {
                    $content .= $this->addContent('notes', 1, $this->escapeLastBackslash($service->get('notes')));
                }

                if ($service->get('is_volatile') === 1 || $service->get('is_volatile') === 0)
                    $content .= $this->addContent('is_volatile', 1, $service->get('is_volatile'));

                if (!empty($service->get('servicegroups'))) {
                    //Use service groups of the service
                    $content .= PHP_EOL;
                    $content .= $this->addContent(';Servicegroup memberships:', 1);
                    $content .= $this->addContent('servicegroups', 1, $service->getServicegroupsForCfg());
                } else if (empty($service->get('servicegroups')) && !empty($servicetemplate->get('servicegroups'))) {
                    //Use service groups of service template configuration
                    $content .= PHP_EOL;
                    $content .= $this->addContent(';Servicegroup memberships:', 1);
                    $content .= $this->addContent('servicegroups', 1, $servicetemplate->getServicegroupsForCfg());
                }

                if ($service->hasCustomvariables()) {
                    $content .= PHP_EOL;
                    $content .= $this->addContent(';Custom  variables:', 1);
                    foreach ($service->getCustomvariablesForCfg() as $varName => $varValue) {
                        $content .= $this->addContent($varName, 1, $varValue);
                    }
                }

                $content .= $this->addContent('}', 0);

                if (!$this->conf['minified']) {
                    $file->write($content);
                    $file->close();
                }


                if ($this->dm === true && $host->isSatelliteHost() === true) {
                    $this->exportSatService($service, $host, $servicetemplate);
                }
            }
        }

        if ($this->conf['minified']) {
            $file->write($content);
            $file->close();
        }

        if ($this->dm === true) {
            $this->exportSatServicegroups();
        }

        $this->deleteServicePerfdata();
    }

    /**
     * @param \App\Model\Entity\Service $service
     * @param \App\Model\Entity\Host $host
     * @param \App\Model\Entity\Servicetemplate $servicetemplate
     */
    public function exportSatService(\App\Model\Entity\Service $service, \App\Model\Entity\Host $host, \App\Model\Entity\Servicetemplate $servicetemplate) {
        /** @var CommandsTable $CommandsTable */
        $CommandsTable = TableRegistry::getTableLocator()->get('Commands');


        $satelliteId = $host->get('satellite_id');
        if (!is_dir($this->conf['satellite_path'] . $satelliteId . DS . $this->conf['services'])) {
            mkdir($this->conf['satellite_path'] . $satelliteId . DS . $this->conf['services']);
        }

        if (!$this->conf['minified']) {
            $file = new File($this->conf['satellite_path'] . $satelliteId . DS . $this->conf['services'] . $service->get('uuid') . $this->conf['suffix']);
            $content = $this->fileHeader();

        } else {
            $file = new File($this->conf['satellite_path'] . $satelliteId . DS . $this->conf['services'] . 'services_minified' . $this->conf['suffix']);
            $content = '';
        }

        if (!$file->exists()) {
            $file->create();
        }

        $content .= $this->addContent('define service{', 0);
        $content .= $this->addContent('use', 1, $servicetemplate->get('uuid'));
        $content .= $this->addContent('host_name', 1, $host->get('uuid'));

        $content .= $this->addContent('name', 1, $service->get('uuid'));
        if ($service->get('name') !== null && $service->get('name') !== '') {
            $content .= $this->addContent('display_name', 1, $this->escapeLastBackslash(
                $service->get('name')
            ));
        } else {
            $content .= $this->addContent('display_name', 1, $this->escapeLastBackslash(
                $servicetemplate->get('name')
            ));
        }

        $content .= $this->addContent('service_description', 1, $service->get('uuid'));

        $content .= PHP_EOL;
        $content .= $this->addContent(';Check settings:', 1);

        if (!empty($service->get('servicecommandargumentvalues'))) {
            if ($service->get('command_id') === null) {
                //Service has own command arguments but uses the same command as the service template
                $commandId = $servicetemplate->get('command_id');
            } else {
                //Service has own command arguments AND own check command
                $commandId = $service->get('command_id');
            }
            $commandUuid = $this->CommandUuidsCache->get($commandId);
            $commandarguments = $service->getCommandargumentValuesForCfg();
            $content .= $this->addContent('check_command', 1, sprintf(
                '%s!%s; %s',
                $commandUuid,
                implode('!', Hash::extract($commandarguments, '{n}.value')),
                implode('!', Hash::extract($commandarguments, '{n}.human_name'))
            ));
        } else {
            //May be check command without arguments
            if ($service->get('command_id') !== null) {
                //Service has own check command but this command has no arguments at all
                $commandId = $service->get('command_id');
                $commandUuid = $this->CommandUuidsCache->get($commandId);
                $content .= $this->addContent('check_command', 1, $commandUuid);
            }
        }

        if ($servicetemplate->hasEventhandler() || $service->hasEventhandler()) {
            $content .= PHP_EOL;
            $content .= $this->addContent(';Event handler:', 1);

            if (!empty($service->get('serviceeventcommandargumentvalues'))) {
                if ($service->get('eventhandler_command_id') === null) {
                    //Service has own command arguments but uses the same event handler command as the service template
                    $commandId = $servicetemplate->get('eventhandler_command_id');
                } else {
                    //Service has own event handler command arguments AND own event handler command
                    $commandId = $service->get('eventhandler_command_id');
                }
                $commandUuid = $this->CommandUuidsCache->get($commandId);
                $eventcommandarguments = $service->getEventhandlerCommandargumentValuesForCfg();
                $content .= $this->addContent('event_handler', 1, sprintf(
                    '%s!%s; %s',
                    $commandUuid,
                    implode('!', Hash::extract($eventcommandarguments, '{n}.value')),
                    implode('!', Hash::extract($eventcommandarguments, '{n}.human_name'))
                ));
            } else {
                //May be event handler command without arguments
                if ($service->get('eventhandler_command_id') !== null) {
                    //Service has own evnet handler command but this command has no arguments at all
                    $commandId = $service->get('eventhandler_command_id');
                    $commandUuid = $this->CommandUuidsCache->get($commandId);
                    $content .= $this->addContent('event_handler', 1, $commandUuid);
                }
            }
        }

        if ($service->get('check_period_id')) {
            $timeperiodUuid = $this->TimeperiodUuidsCache->get($service->get('check_period_id'));
            $content .= $this->addContent('check_period', 1, $timeperiodUuid);
        }

        if ($service->get('check_interval') !== null && $service->get('check_interval') !== '')
            $content .= $this->addContent('check_interval', 1, $service->get('check_interval'));

        if ($service->get('retry_interval') !== null && $service->get('retry_interval') !== '')
            $content .= $this->addContent('retry_interval', 1, $service->get('retry_interval'));

        if ($service->get('max_check_attempts') !== null && $service->get('max_check_attempts') !== '')
            $content .= $this->addContent('max_check_attempts', 1, $service->get('max_check_attempts'));


        if ($service->get('active_checks_enabled') !== null && $service->get('active_checks_enabled') !== '') {
            $content .= $this->addContent('active_checks_enabled', 1, $service->get('active_checks_enabled'));
        }

        $content .= $this->addContent('passive_checks_enabled', 1, 1);


        if ($service->get('freshness_checks_enabled') > 0) {
            $content .= $this->addContent('check_freshness', 1, 1);

            $freshnessThreshold = $service->get('freshness_threshold');
            if ($freshnessThreshold === null || $freshnessThreshold === '' || $freshnessThreshold === 0) {
                $freshnessThreshold = (int)$servicetemplate->get('freshness_threshold');
            }
            $content .= $this->addContent('freshness_threshold', 1, $freshnessThreshold + $this->FRESHNESS_THRESHOLD_ADDITION);
        }

        $content .= PHP_EOL;
        $content .= $this->addContent(';Notification settings:', 1);
        if ($service->get('notifications_enabled') !== null && $service->get('notifications_enabled') !== '') {
            $content .= $this->addContent('notifications_enabled', 1, $service->get('notifications_enabled'));
        }

        if (!empty($service->get('contacts'))) {
            $content .= $this->addContent('contacts', 1, $service->getContactsforCfg());
        }

        if (!empty($service->get('contactgroups'))) {
            $content .= $this->addContent('contact_groups', 1, $service->getContactgroupsforCfg());
        }


        if ($service->get('notification_interval') !== null && $service->get('notification_interval') !== '') {
            $content .= $this->addContent('notification_interval', 1, $service->get('notification_interval'));
        }

        if ($service->get('notify_period_id')) {
            $timeperiodUuid = $this->TimeperiodUuidsCache->get($service->get('notify_period_id'));
            $content .= $this->addContent('notification_period', 1, $timeperiodUuid);
        }

        if (strlen($service->getNotificationOptionsForCfg($servicetemplate)) > 0) {
            $content .= $this->addContent('notification_options', 1, $service->getNotificationOptionsForCfg($servicetemplate));
        }

        $content .= PHP_EOL;
        $content .= $this->addContent(';Flap detection settings:', 1);
        if ($service->get('flap_detection_enabled') === 1 || $service->get('flap_detection_enabled') === 0) {
            $content .= $this->addContent('flap_detection_enabled', 1, $service->get('flap_detection_enabled'));
        }

        if ($service->get('flap_detection_enabled') === 1 && strlen($service->getFlapdetectionOptionsForCfg($servicetemplate)) > 0) {
            $content .= $this->addContent('flap_detection_options', 1, $service->getFlapdetectionOptionsForCfg($servicetemplate));
        }

        $content .= PHP_EOL;
        $content .= $this->addContent(';Everything else:', 1);
        if ($service->get('process_performance_data') === 1 || $service->get('process_performance_data') === 0)
            $content .= $this->addContent('process_perf_data', 1, $service->get('process_performance_data'));

        if ($service->get('notes') && strlen($service->get('notes')) > 0) {
            $content .= $this->addContent('notes', 1, $this->escapeLastBackslash($service->get('notes')));
        }

        if ($service->get('is_volatile') === 1 || $service->get('is_volatile') === 0)
            $content .= $this->addContent('is_volatile', 1, $service->get('is_volatile'));

        if (!empty($service->get('servicegroups'))) {
            //Use service groups of the service
            $content .= PHP_EOL;
            $content .= $this->addContent(';Servicegroup memberships:', 1);
            $content .= $this->addContent('servicegroups', 1, $service->getServicegroupsForCfg());
        } else if (empty($service->get('servicegroups')) && !empty($servicetemplate->get('servicegroups'))) {
            //Use service groups of service template configuration
            $content .= PHP_EOL;
            $content .= $this->addContent(';Servicegroup memberships:', 1);
            $content .= $this->addContent('servicegroups', 1, $servicetemplate->getServicegroupsForCfg());
        }

        if ($service->hasCustomvariables()) {
            $content .= PHP_EOL;
            $content .= $this->addContent(';Custom  variables:', 1);
            foreach ($service->getCustomvariablesForCfg() as $varName => $varValue) {
                $content .= $this->addContent($varName, 1, $varValue);
            }
        }

        $content .= $this->addContent('}', 0);


        if (!$this->conf['minified']) {
            $file->write($content);
        } else {
            $file->append($content);
        }
        $file->close();

    }


    /**
     * @param null|string $uid
     */
    public function exportHostgroups() {
        /** @var HostgroupsTable $HostgroupsTable */
        $HostgroupsTable = TableRegistry::getTableLocator()->get('Hostgroups');
        $hostgroups = $HostgroupsTable->getHostgroupsForExport();

        if (!is_dir($this->conf['path'] . $this->conf['hostgroups'])) {
            mkdir($this->conf['path'] . $this->conf['hostgroups']);
        }

        if ($this->conf['minified']) {
            $file = new File($this->conf['path'] . $this->conf['hostgroups'] . 'hostgroups_minified' . $this->conf['suffix']);
            if (!$file->exists()) {
                $file->create();
            }
            $content = $this->fileHeader();
        }

        foreach ($hostgroups as $hostgroup) {
            /** @var \App\Model\Entity\Hostgroup $hostgroup */
            if (!$this->conf['minified']) {
                $file = new File($this->conf['path'] . $this->conf['hostgroups'] . $hostgroup->get('uuid') . $this->conf['suffix']);
                $content = $this->fileHeader();
                if (!$file->exists()) {
                    $file->create();
                }
            }

            $alias = $this->escapeLastBackslash($hostgroup->get('description'));
            if (empty($alias)) {
                $alias = $hostgroup->get('uuid');
            }
            $content .= $this->addContent('define hostgroup{', 0);
            $content .= $this->addContent('hostgroup_name', 1, $hostgroup->get('uuid'));
            $content .= $this->addContent('alias', 1, $alias);
            $content .= $this->addContent('}', 0);

            if (!$this->conf['minified']) {
                $file->write($content);
                $file->close();
            }
        }
        if ($this->conf['minified']) {
            $file->write($content);
            $file->close();
        }
    }

    public function exportServicegroups() {
        /** @var ServicegroupsTable $ServicegroupsTable */
        $ServicegroupsTable = TableRegistry::getTableLocator()->get('Servicegroups');
        $servicegroups = $ServicegroupsTable->getServicegroupsForExport();

        if (!is_dir($this->conf['path'] . $this->conf['servicegroups'])) {
            mkdir($this->conf['path'] . $this->conf['servicegroups']);
        }

        foreach ($servicegroups as $servicegroup) {
            /** @var \App\Model\Entity\Servicegroup $servicegroup */
            $file = new File($this->conf['path'] . $this->conf['servicegroups'] . $servicegroup->get('uuid') . $this->conf['suffix']);
            $content = $this->fileHeader();
            if (!$file->exists()) {
                $file->create();
            }

            $alias = $this->escapeLastBackslash($servicegroup->get('description'));
            if (empty($alias)) {
                $alias = $servicegroup->get('uuid');
            }

            $content .= $this->addContent('define servicegroup{', 0);
            $content .= $this->addContent('servicegroup_name', 1, $servicegroup->get('uuid'));
            $content .= $this->addContent('alias', 1, $alias);

            $content .= $this->addContent('}', 0);
            $file->write($content);
            $file->close();
        }
    }

    /**
     * @param null|string $uuid
     */
    public function exportHostescalations($uuid = null) {
        /* @var HostescalationsTable $HostescalationsTable */
        /* @var \App\Model\Entity\Hostescalation $hostescalation */
        /* @var \App\Model\Entity\Contact $contact */
        /* @var \App\Model\Entity\Hostgroup $hostgroup */
        /* @var \App\Model\Entity\Host $host */
        /* @var \App\Model\Entity\Contactgroup $contactgroup */
        $HostescalationsTable = TableRegistry::getTableLocator()->get('Hostescalations');
        $hostescalations = $HostescalationsTable->getHostescalationsForExport($uuid);

        if (!is_dir($this->conf['path'] . $this->conf['hostescalations'])) {
            mkdir($this->conf['path'] . $this->conf['hostescalations']);
        }
        foreach ($hostescalations as $hostescalation) {

            $hostsForCfg = [];
            $excludedHostsForCfg = [];
            $hosts = $hostescalation->get('hosts');
            if (empty($hosts)) {
                //This hostescalation is broken!
                $HostescalationsTable->delete($hostescalation);
                continue;
            }
            if (!is_null($hosts)) {
                foreach ($hosts as $host) {
                    if ($host->get('_joinData')->get('excluded') === 0) {
                        $hostsForCfg[] = $host->get('uuid');
                    } else {
                        $excludedHostsForCfg[] = '!' . $host->get('uuid');
                    }
                }
            }

            $hostgroupsForCfg = [];
            $excludedHostgroupsForCfg = [];
            $hostgroups = $hostescalation->get('hostgroups');
            if (!is_null($hostgroups)) {
                foreach ($hostgroups as $hostgroup) {
                    if ($hostgroup->get('_joinData')->get('excluded') === 0) {
                        $hostgroupsForCfg[] = $hostgroup->get('uuid');
                    } else {
                        $excludedHostgroupsForCfg[] = '!' . $hostgroup->get('uuid');
                    }
                }
            }

            if (empty($hostsForCfg)) {
                //This hostescalation is broken!
                $HostescalationsTable->delete($hostescalation);
                continue;
            }

            $contactUuids = [];
            foreach ($hostescalation->get('contacts') as $contact) {
                $contactUuids[] = $contact->get('uuid');
            }
            $contactgroupUuids = [];
            foreach ($hostescalation->get('contactgroups') as $contactgroup) {
                $contactgroupUuids[] = $contactgroup->get('uuid');
            }

            $file = new File($this->conf['path'] . $this->conf['hostescalations'] . $hostescalation->get('uuid') . $this->conf['suffix']);
            $content = $this->fileHeader();
            if (!$file->exists()) {
                $file->create();
            }
            $content .= $this->addContent('define hostescalation{', 0);
            if (!empty($hosts)) {
                $content .= $this->addContent('host_name', 1, implode(',', array_merge($hostsForCfg, $excludedHostsForCfg)));
            }

            if (!empty($hostgroups)) {
                $content .= $this->addContent('hostgroup_name', 1, implode(',', array_merge($hostgroupsForCfg, $excludedHostgroupsForCfg)));
            }
            if (!empty($contactUuids)) {
                $content .= $this->addContent('contacts', 1, implode(',', $contactUuids));
            }
            if (!empty($contactgroupUuids)) {
                $content .= $this->addContent('contact_groups', 1, implode(',', $contactgroupUuids));
            }
            $content .= $this->addContent('first_notification', 1, $hostescalation->get('first_notification'));
            $content .= $this->addContent('last_notification', 1, $hostescalation->get('last_notification'));
            $content .= $this->addContent('notification_interval', 1, (int)$hostescalation->get('notification_interval') * 60);

            $escalationTimeperiod = $hostescalation->get('timeperiod');
            if (!is_null($escalationTimeperiod)) {
                $content .= $this->addContent('escalation_period', 1, $escalationTimeperiod->get('uuid'));
            }

            $hostEscalationString = $hostescalation->getHostEscalationStringForCfg();
            if (!empty($hostEscalationString)) {
                $content .= $this->addContent('escalation_options', 1, $hostEscalationString);
            }
            $content .= $this->addContent('}', 0);
            $file->write($content);
            $file->close();
        }
    }

    /**
     * @param null|string $uuid
     */
    public function exportServiceescalations($uuid = null) {
        /* @var ServiceescalationsTable $ServiceescalationsTable */
        /* @var \App\Model\Entity\Serviceescalation $serviceescalation */
        /* @var \App\Model\Entity\Contact $contact */
        /* @var \App\Model\Entity\Servicegroup $servicegroup */
        /* @var \App\Model\Entity\Service $service */
        /* @var \App\Model\Entity\Contactgroup $contactgroup */
        $ServiceescalationsTable = TableRegistry::getTableLocator()->get('Serviceescalations');
        $serviceescalations = $ServiceescalationsTable->getServiceescalationsForExport($uuid);

        if (!is_dir($this->conf['path'] . $this->conf['serviceescalations'])) {
            mkdir($this->conf['path'] . $this->conf['serviceescalations']);
        }

        foreach ($serviceescalations as $serviceescalation) {
            $servicesForCfg = [];
            $services = $serviceescalation->get('services');
            if (empty($services)) {
                //This service escalation is broken!
                $ServiceescalationsTable->delete($serviceescalation);
                continue;
            }
            if (!is_null($services)) {
                foreach ($services as $service) {
                    //var_dump($service->get('host')->get('uuid'));
                    if ($service->get('_joinData')->get('excluded') === 0) {
                        $servicesForCfg[$service->get('host')->get('uuid')][] = $service->get('uuid');
                    } else {
                        $servicesForCfg[$service->get('host')->get('uuid')][] = '!' . $service->get('uuid');
                    }
                }
            }
            if (empty($servicesForCfg)) {
                //This service escalation is broken!
                $ServiceescalationsTable->delete($serviceescalation);
                continue;
            }
            $servicegroupsForCfg = [];
            $servicegroups = $serviceescalation->get('servicegroups');
            if (!is_null($servicegroups)) {
                foreach ($servicegroups as $servicegroup) {
                    if ($servicegroup->get('_joinData')->get('excluded') === 0) {
                        $servicegroupsForCfg[] = $servicegroup->get('uuid');
                    } else {
                        $servicegroupsForCfg[] = '!' . $servicegroup->get('uuid');
                    }
                }
            }

            $file = new File($this->conf['path'] . $this->conf['serviceescalations'] . $serviceescalation->get('uuid') . $this->conf['suffix']);
            $content = $this->fileHeader();
            if (!$file->exists()) {
                $file->create();
            }

            $contactUuids = [];
            foreach ($serviceescalation->get('contacts') as $contact) {
                $contactUuids[] = $contact->get('uuid');
            }
            $contactgroupUuids = [];
            foreach ($serviceescalation->get('contactgroups') as $contactgroup) {
                $contactgroupUuids[] = $contactgroup->get('uuid');
            }

            $serviceEscalationString = $serviceescalation->getServiceEscalationStringForCfg();

            if (!empty($servicesForCfg)) {
                foreach ($servicesForCfg as $hostUuid => $serviceUuids) {
                    if (sizeof($serviceUuids) === 1) {
                        if (strpos($serviceUuids[0], '!') === 0 && empty($servicegroupsForCfg)) {
                            //Only one service in escalation and this service should be ignored from the escalation - this makes no sense!
                            continue;
                        }
                    }

                    $content .= $this->addContent('define serviceescalation{', 0);
                    $content .= $this->addContent('host_name', 1, $hostUuid);
                    $content .= $this->addContent('service_description', 1, implode(',', $serviceUuids));

                    if (!empty($servicegroupsForCfg)) {
                        $content .= $this->addContent('servicegroup_name', 1, implode(',', $servicegroupsForCfg));
                    }

                    if (!empty($contactUuids)) {
                        $content .= $this->addContent('contacts', 1, implode(',', $contactUuids));
                    }
                    if (!empty($contactgroupUuids)) {
                        $content .= $this->addContent('contact_groups', 1, implode(',', $contactgroupUuids));
                    }
                    $content .= $this->addContent('first_notification', 1, $serviceescalation->get('first_notification'));
                    $content .= $this->addContent('last_notification', 1, $serviceescalation->get('last_notification'));
                    $content .= $this->addContent('notification_interval', 1, (int)$serviceescalation->get('notification_interval') * 60);

                    $escalationTimeperiod = $serviceescalation->get('timeperiod');
                    if (!is_null($escalationTimeperiod)) {
                        $content .= $this->addContent('escalation_period', 1, $escalationTimeperiod->get('uuid'));
                    }

                    if (!empty($serviceEscalationString)) {
                        $content .= $this->addContent('escalation_options', 1, $serviceEscalationString);
                    }
                    $content .= $this->addContent('}', 0);
                }
            }

            $file->write($content);
            $file->close();
        }
    }


    /**
     * @param null $uuid
     * @throws \Exception
     */
    public function exportTimeperiods($uuid = null) {
        /** @var $TimeperiodsTable TimeperiodsTable */
        $TimeperiodsTable = TableRegistry::getTableLocator()->get('Timeperiods');
        /** @var CalendarsTable $CalendarsTable */
        $CalendarsTable = TableRegistry::getTableLocator()->get('Calendars');
        if ($uuid !== null) {
            $timeperiods[] = $TimeperiodsTable->getTimeperiodWithTimerangesByUuid($uuid);
        } else {
            $timeperiods = $TimeperiodsTable->getTimeperiodWithTimeranges();
        }

        if (!is_dir($this->conf['path'] . $this->conf['timeperiods'])) {
            mkdir($this->conf['path'] . $this->conf['timeperiods']);
        }

        if ($this->conf['minified']) {
            $file = new File($this->conf['path'] . $this->conf['timeperiods'] . 'timeperiods_minified' . $this->conf['suffix']);
            if (!$file->exists()) {
                $file->create();
            }
            $content = $this->fileHeader();
        }

        $date = new \DateTime();
        $weekdays = [];
        for ($i = 1; $i <= 7; $i++) {
            $weekdays[$date->format('N')] = strtolower($date->format('l'));
            $date->modify('+1 day');
        }

        foreach ($timeperiods as $timeperiod) {
            $timeranges = [];
            if (!$this->conf['minified']) {
                $file = new File($this->conf['path'] . $this->conf['timeperiods'] . $timeperiod['Timeperiod']['uuid'] . $this->conf['suffix']);
                $content = $this->fileHeader();
                if (!$file->exists()) {
                    $file->create();
                }
            }

            $content .= $this->addContent('define timeperiod{', 0);
            $content .= $this->addContent('timeperiod_name', 1, $timeperiod['Timeperiod']['uuid']);
            if (strlen($timeperiod['Timeperiod']['description']) > 0) {
                $content .= $this->addContent('alias', 1, $this->escapeLastBackslash($timeperiod['Timeperiod']['description']));
            } else {
                //Naemon 1.0.0 fix
                $content .= $this->addContent('alias', 1, $timeperiod['Timeperiod']['uuid']);
            }

            foreach ($timeperiod['Timeperiod']['timeperiod_timeranges'] as $timerange) {
                $timeranges[$timerange['day']][] = $timerange['start'] . '-' . $timerange['end'];
            }

            foreach ($timeranges as $weekday => $timesArray) {
                asort($timesArray);
                $content .= $this->addContent($weekdays[$weekday], 1, implode(',', $timesArray));
            }

            //Merge Calendar records to timeperiod
            if ($timeperiod['Timeperiod']['calendar_id'] > 0) {
                try {
                    $calendar = $CalendarsTable->getCalendarById($timeperiod['Timeperiod']['calendar_id']);
                    foreach ($calendar->get('calendar_holidays') as $holiday) {
                        /** @var CalendarHoliday $holiday */
                        $timestamp = strtotime(sprintf('%s 00:00', $holiday->get('date')));

                        $calendarDay = sprintf('%s 00:00-24:00; %s',
                            strtolower(date('F j', $timestamp)),
                            $holiday->get('name')
                        );
                        $content .= $this->addContent($calendarDay, 1);
                    }
                } catch (RecordNotFoundException $e) {
                    Log::error(sprintf(
                        'NagiosConfigGenerator: Calendar with id "%s" not found',
                        $timeperiod['Timeperiod']['calendar_id']
                    ));
                    Log::error($e->getMessage());
                }
            }

            $content .= $this->addContent('}', 0);

            if (!$this->conf['minified']) {
                $file->write($content);
                $file->close();
            }
        }

        if ($this->conf['minified']) {
            $file->write($content);
            $file->close();
        }

        if ($this->dm === true) {
            foreach ($this->Satellites as $satelite) {
                $this->exportSatTimeperiods($timeperiods, $satelite);
            }
        }
    }

    /**
     * @param $timeperiods
     * @param EntityInterface $satelite
     * @throws \Exception
     */
    public function exportSatTimeperiods($timeperiods, EntityInterface $satelite) {
        if (!is_dir($this->conf['satellite_path'] . $satelite->get('id') . DS . $this->conf['timeperiods'])) {
            mkdir($this->conf['satellite_path'] . $satelite->get('id') . DS . $this->conf['timeperiods']);
        }

        /** @var CalendarsTable $CalendarsTable */
        $CalendarsTable = TableRegistry::getTableLocator()->get('Calendars');

        if ($this->conf['minified']) {
            $file = new File($this->conf['satellite_path'] . $satelite->get('id') . DS . $this->conf['timeperiods'] . 'timeperiods_minified' . $this->conf['suffix']);
            if (!$file->exists()) {
                $file->create();
            }
            $content = $this->fileHeader();
        }

        $date = new \DateTime();
        $weekdays = [];
        for ($i = 1; $i <= 7; $i++) {
            $weekdays[$date->format('N')] = strtolower($date->format('l'));
            $date->modify('+1 day');
        }

        foreach ($timeperiods as $timeperiod) {
            if (!$this->conf['minified']) {
                $file = new File($this->conf['satellite_path'] . $satelite->get('id') . DS . $this->conf['timeperiods'] . $timeperiod['Timeperiod']['uuid'] . $this->conf['suffix']);
                $content = $this->fileHeader();
                if (!$file->exists()) {
                    $file->create();
                }
            }

            $content .= $this->addContent('define timeperiod{', 0);
            $content .= $this->addContent('timeperiod_name', 1, $timeperiod['Timeperiod']['uuid']);
            if (strlen($timeperiod['Timeperiod']['description']) > 0) {
                $content .= $this->addContent('alias', 1, $this->escapeLastBackslash($timeperiod['Timeperiod']['description']));
            } else {
                //Naemon 1.0.0 fix
                $content .= $this->addContent('alias', 1, $timeperiod['Timeperiod']['uuid']);
            }
            $timeRanges = [];
            foreach ($timeperiod['Timeperiod']['timeperiod_timeranges'] as $timeRange) {
                if (empty($satelite->get('timezone')) || ($timeRange['start'] == '00:00' && $timeRange['end'] == '24:00')) {
                    $timeRanges[$weekdays[$timeRange['day']]][] = $timeRange['start'] . '-' . $timeRange['end'];
                } else {
                    $remoteTimeZone = new \DateTimeZone($satelite->get('timezone'));
                    $start = new \DateTime($weekdays[$timeRange['day']] . ' ' . $timeRange['start']);
                    $start = $start->setTimezone($remoteTimeZone);
                    $end = new \DateTime($weekdays[$timeRange['day']] . ' ' . (($timeRange['end'] == '24:00') ? '23:59' : $timeRange['end']));
                    $end = $end->setTimezone($remoteTimeZone);
                    if ($timeRange['end'] == '24:00') {
                        $end = $end->add(new \DateInterval('PT1M'));
                    }
                    if ($start->format('l') == $end->format('l')) {
                        $timeRanges[strtolower($start->format('l'))][] = $start->format('H:i') . '-' . $end->format('H:i');
                    } else {
                        $timeRanges[strtolower($start->format('l'))][] = $start->format('H:i') . '-24:00';
                        if ($end->format('H:i') != '00:00') {
                            $timeRanges[strtolower($end->format('l'))][] = '00:00-' . $end->format('H:i');
                        }
                    }
                }
            }
            foreach ($timeRanges as $day => $timeRange) {
                asort($timeRange);
                $content .= $this->addContent($day, 1, implode(',', $timeRange));
            }

            //Merge Calendar records to timeperiod
            if ($timeperiod['Timeperiod']['calendar_id'] > 0) {
                try {
                    $calendar = $CalendarsTable->getCalendarById($timeperiod['Timeperiod']['calendar_id']);
                    foreach ($calendar->get('calendar_holidays') as $holiday) {
                        /** @var CalendarHoliday $holiday */
                        $timestamp = strtotime(sprintf('%s 00:00', $holiday->get('date')));

                        $calendarDay = sprintf('%s 00:00-24:00; %s',
                            strtolower(date('F j', $timestamp)),
                            $holiday->get('name')
                        );
                        $content .= $this->addContent($calendarDay, 1);
                    }
                } catch (RecordNotFoundException $e) {
                    Log::error(sprintf(
                        'NagiosConfigGenerator: Calendar with id "%s" not found',
                        $timeperiod['Timeperiod']['calendar_id']
                    ));
                    Log::error($e->getMessage());
                }
            }

            $content .= $this->addContent('}', 0);

            if (!$this->conf['minified']) {
                $file->write($content);
                $file->close();
            }
        }

        if ($this->conf['minified']) {
            $file->write($content);
            $file->close();
        }
    }

    /**
     * @param null|string $uuid
     */
    public function exportHostdependencies($uuid = null) {
        /** @var HostdependenciesTable $HostdependenciesTable */
        $HostdependenciesTable = TableRegistry::getTableLocator()->get('Hostdependencies');
        /** @var HostgroupsTable $HostgroupsTable */
        $HostgroupsTable = TableRegistry::getTableLocator()->get('Hostgroups');

        $hostdependencies = $HostdependenciesTable->getHostdependenciesForExport($uuid);

        if (!is_dir($this->conf['path'] . $this->conf['hostdependencies'])) {
            mkdir($this->conf['path'] . $this->conf['hostdependencies']);
        }

        foreach ($hostdependencies as $hostdependency) {
            /** @var Hostdependency $hostdependency */
            $file = new File($this->conf['path'] . $this->conf['hostdependencies'] . $hostdependency->get('uuid') . $this->conf['suffix']);
            $content = $this->fileHeader();
            if (!$file->exists()) {
                $file->create();
            }

            $hostsForCfg = [];
            $dependentHostsForCfg = [];
            $hosts = $hostdependency->get('hosts');
            //Check if the host dependency is valid
            if (is_null($hosts)) {
                //This host dependency is broken, ther are no hosts in it!
                $HostdependenciesTable->delete($hostdependency);
                $file->close();
                if ($file->exists()) {
                    $file->delete();
                }
                continue;
            }

            $hostgroupsForCfg = [];
            $dependentHostgroupsForCfg = [];

            $hostgroupIds = [];
            $dependentHostgroupIds = [];

            if (!is_null($hostdependency->get('hostgroups'))) {
                $hostgroups = $hostdependency->get('hostgroups');
                foreach ($hostgroups as $hostgroup) {
                    /** @var Hostgroup $hostgroup */
                    if ($hostgroup->get('_joinData')->get('dependent') === 0) {
                        $hostgroupsForCfg[] = $hostgroup->get('uuid');
                        $hostgroupIds[] = $hostgroup->get('id');

                    } else {
                        $dependentHostgroupsForCfg[] = $hostgroup->get('uuid');
                        $dependentHostgroupIds[] = $hostgroup->get('id');
                    }
                }
            }

            foreach ($hosts as $host) {
                /** @var Host $host */
                if ($host->get('_joinData')->get('dependent') === 0) {
                    if (empty($dependentHostgroupIds)) {
                        $hostsForCfg[] = $host->get('uuid');
                    } else if (!empty($dependentHostgroupIds) && !$HostgroupsTable->isHostInHostgroup($host->get('id'), $dependentHostgroupIds)) {
                        $hostsForCfg[] = $host->get('uuid');
                    }

                } else {
                    if (empty($hostgroupIds)) {
                        $dependentHostsForCfg[] = $host->get('uuid');
                    } else if (!empty($hostgroupIds) && !$HostgroupsTable->isHostInHostgroup($host->get('id'), $hostgroupIds)) {
                        $dependentHostsForCfg[] = $host->get('uuid');
                    }
                }
            }

            if (empty($hostsForCfg) || empty($dependentHostsForCfg)) {
                //This host dependency is broken, there are no hosts in it!
                $HostdependenciesTable->delete($hostdependency);
                $file->close();
                if ($file->exists()) {
                    $file->delete();
                }
                continue;
            }

            if (!empty($hostsForCfg) && !empty($dependentHostsForCfg)) {
                $content .= $this->addContent('define hostdependency{', 0);

                $content .= $this->addContent('host_name', 1, implode(',', $hostsForCfg));
                $content .= $this->addContent('dependent_host_name', 1, implode(',', $dependentHostsForCfg));
            }

            if (!empty($hostgroupsForCfg)) {
                $content .= $this->addContent('hostgroup_name', 1, implode(',', $hostgroupsForCfg));
            }
            if (!empty($dependentHostgroupsForCfg)) {
                $content .= $this->addContent('dependent_hostgroup_name', 1, implode(',', $dependentHostgroupsForCfg));
            }

            $content .= $this->addContent('inherits_parent', 1, $hostdependency->get('inherits_parent'));

            $executionFailureCriteriaForCfgString = $hostdependency->getExecutionFailureCriteriaForCfg();
            if (!empty($executionFailureCriteriaForCfgString)) {
                $content .= $this->addContent('execution_failure_criteria', 1, $executionFailureCriteriaForCfgString);
            }
            $notificationFailureCriteriaForCfgString = $hostdependency->getNotificationFailureCriteriaForCfg();
            if (!empty($notificationFailureCriteriaForCfgString)) {
                $content .= $this->addContent('notification_failure_criteria', 1, $notificationFailureCriteriaForCfgString);
            }
            $dependencyTimeperiod = $hostdependency->get('timeperiod');
            if (!is_null($dependencyTimeperiod)) {
                $content .= $this->addContent('dependency_period', 1, $dependencyTimeperiod->get('uuid'));
            }
            $content .= $this->addContent('}', 0);

            $file->write($content);
            $file->close();
        }
    }

    /**
     * @param null|string $uuid
     */
    public function exportServicedependencies($uuid = null) {
        /** @var ServicedependenciesTable $ServicedependenciesTable */
        $ServicedependenciesTable = TableRegistry::getTableLocator()->get('Servicedependencies');
        /** @var \App\Model\Entity\Servicedependency $servicedependency */
        $servicedependencies = $ServicedependenciesTable->getServicedependenciesForExport($uuid);
        /** @var  ServicegroupsTable $ServicegroupsTable */
        $ServicegroupsTable = TableRegistry::getTableLocator()->get('Servicegroups');

        if (!is_dir($this->conf['path'] . $this->conf['servicedependencies'])) {
            mkdir($this->conf['path'] . $this->conf['servicedependencies']);
        }

        foreach ($servicedependencies as $servicedependency) {
            $file = new File($this->conf['path'] . $this->conf['servicedependencies'] . $servicedependency->get('uuid') . $this->conf['suffix']);
            $content = $this->fileHeader();
            if (!$file->exists()) {
                $file->create();
            }
            $masterServicesForCfg = [];
            $dependentServicesForCfg = [];

            $servicegroupIds = [];
            $dependentServicegroupIds = [];

            $services = $servicedependency->get('services', [
                'contain' => 'Hosts'
            ]);

            $servicegroupsForCfg = [];
            $dependentServicegroupsForCfg = [];
            if (!is_null($servicedependency->get('servicegroups'))) {
                $servicegroups = $servicedependency->get('servicegroups');
                foreach ($servicegroups as $servicegroup) {
                    /** @var Servicegroup $servicegroup */
                    if ($servicegroup->get('_joinData')->get('dependent') === 0) {
                        $servicegroupsForCfg[] = $servicegroup->get('uuid');
                        $servicegroupIds[] = $servicegroup->get('id');
                    } else {
                        $dependentServicegroupsForCfg[] = $servicegroup->get('uuid');
                        $dependentServicegroupIds[] = $servicegroup->get('id');
                    }
                }
            }

            foreach ($services as $service) {
                /** @var Service $service */
                if ($service->get('_joinData')->get('dependent') === 0) {
                    if (empty($dependentServicegroupIds)) {
                        $masterServicesForCfg[] = [
                            'hostUuid'    => $service->get('host')->get('uuid'),
                            'serviceUuid' => $service->get('uuid')
                        ];
                    } else if (!empty($dependentServicegroupIds) && !$ServicegroupsTable->isServiceInServicegroup($service->get('id'), $dependentServicegroupIds)) {
                        $masterServicesForCfg[] = [
                            'hostUuid'    => $service->get('host')->get('uuid'),
                            'serviceUuid' => $service->get('uuid')
                        ];
                    }
                } else {
                    if (empty($servicegroupIds)) {
                        $dependentServicesForCfg[] = [
                            'hostUuid'    => $service->get('host')->get('uuid'),
                            'serviceUuid' => $service->get('uuid')
                        ];
                    } else if (!empty($servicegroupIds) && !$ServicegroupsTable->isServiceInServicegroup($service->get('id'), $servicegroupIds)) {
                        $dependentServicesForCfg[] = [
                            'hostUuid'    => $service->get('host')->get('uuid'),
                            'serviceUuid' => $service->get('uuid')
                        ];
                    }
                }
            }

            //Check if the service dependency is valid
            if (empty($masterServicesForCfg) || empty($dependentServicesForCfg)) {
                //This service dependency is broken, there are no services or no dependent services in it!
                $ServicedependenciesTable->delete($servicedependency);
                $file->close();
                if ($file->exists()) {
                    $file->delete();
                }
                continue;
            }

            $serviceDependencyTimeperiod = null;
            $dependencyTimeperiod = $servicedependency->get('timeperiod');
            if (!is_null($dependencyTimeperiod)) {
                $serviceDependencyTimeperiod = $dependencyTimeperiod->get('uuid');
            }
            $executionFailureCriteriaForCfgString = $servicedependency->getExecutionFailureCriteriaForCfg();
            $notificationFailureCriteriaForCfgString = $servicedependency->getNotificationFailureCriteriaForCfg();


            foreach ($masterServicesForCfg as $masterServiceForCfg) {
                foreach ($dependentServicesForCfg as $dependentServiceForCfg) {
                    $content .= $this->addContent('define servicedependency{', 0);
                    $content .= $this->addContent('host_name', 1, $masterServiceForCfg['hostUuid']);
                    $content .= $this->addContent('service_description', 1, $masterServiceForCfg['serviceUuid']);
                    $content .= $this->addContent('dependent_host_name', 1, $dependentServiceForCfg['hostUuid']);
                    $content .= $this->addContent('dependent_service_description', 1, $dependentServiceForCfg['serviceUuid']);


                    if (!empty($servicegroupsForCfg)) {
                        $content .= $this->addContent('servicegroup_name', 1, implode(', ', $servicegroupsForCfg));
                    }

                    if (!empty($dependentServicegroupsForCfg)) {
                        $content .= $this->addContent('dependent_servicegroup_name', 1, implode(', ', $dependentServicegroupsForCfg));
                    }

                    $content .= $this->addContent('inherits_parent', 1, $servicedependency->get('inherits_parent'));

                    if (!empty($executionFailureCriteriaForCfgString)) {
                        // If all states are selected you get an warning like this:
                        //Warning: Ignoring lame service dependency (config file 'foo.cfg', line 83)
                        $content .= $this->addContent('execution_failure_criteria', 1, $executionFailureCriteriaForCfgString);
                    }
                    if (!empty($notificationFailureCriteriaForCfgString)) {
                        // If all states are selected you get an warning like this:
                        //Warning: Ignoring lame service dependency (config file 'foo.cfg', line 83)
                        $content .= $this->addContent('notification_failure_criteria', 1, $notificationFailureCriteriaForCfgString);
                    }

                    if (!is_null($serviceDependencyTimeperiod)) {
                        $content .= $this->addContent('dependency_period', 1, $serviceDependencyTimeperiod);
                    }

                    $content .= $this->addContent('}', 0);
                    $content .= PHP_EOL;
                }
            }
            $file->write($content);
            $file->close();
        }
    }


    public function exportMacros() {
        $file = new File($this->conf['path'] . $this->conf['macros'] . $this->conf['suffix']);
        $content = $this->hashFileHeader();
        if (!$file->exists()) {
            $file->create();
        }

        /** @var $Macro MacrosTable */
        $Macro = TableRegistry::getTableLocator()->get('Macros');
        $macros = $Macro->getAllMacrosInCake2Format();

        foreach ($macros as $macro) {
            $content .= $this->addContent($macro['Macro']['name'] . '=' . $macro['Macro']['value'], 0);
        }

        $file->write($content);
        $file->close();
    }

    /**
     * This function load export tasks from external modules
     * @todo implement me
     */
    private function loadExternTasks() {
        $this->externalTasks = [];
        return;
        $modulePlugins = array_filter(CakePlugin::loaded(), function ($value) {
            return strpos($value, 'Module') !== false;
        });
        foreach ($modulePlugins as $pluginName) {
            if (file_exists(OLD_APP . 'Plugin/' . $pluginName . '/Console/Command/Task/' . $pluginName . 'NagiosExportTask.php')) {
                $this->externalTasks[$pluginName] = $pluginName . 'NagiosExport';
            }
        }
    }

    /**
     * @todo implement me
     */
    public function exportExternalTasks() {
        return;
        foreach ($this->externalTasks as $pluginName => $taskName) {
            $_task = new TaskCollection($this);
            $extTask = $_task->load($pluginName . '.' . $taskName);
            $extTask->export();
        }
    }

    /**
     * @todo implement me
     */
    public function beforeExportExternalTasks() {
        $this->createMissingOitcAgentActiveChecks();
        return;

        foreach ($this->externalTasks as $pluginName => $taskName) {
            $_task = new TaskCollection($this);
            $extTask = $_task->load($pluginName . '.' . $taskName);
            $extTask->beforeExport();
        }
    }

    /**
     * @todo implement me
     */
    public function afterExportExternalTasks() {
        $this->createOitcAgentJsonConfig();
        return;
        //Restart oitc CMD to wipe old cached information
        exec('service oitc_cmd restart');


        foreach ($this->externalTasks as $pluginName => $taskName) {
            $_task = new TaskCollection($this);
            $extTask = $_task->load($pluginName . '.' . $taskName);
            $extTask->afterExport();
        }
    }

    public function exportSatHostgroups() {
        /** @var HostgroupsTable $HostgroupsTable */
        $HostgroupsTable = TableRegistry::getTableLocator()->get('Hostgroups');
        $hostgroups = $HostgroupsTable->getHostgroupsForExport();

        foreach ($this->Satellites as $satellite) {
            $satelliteId = $satellite->get('id');

            if (!is_dir($this->conf['satellite_path'] . $satelliteId . DS . $this->conf['hostgroups'])) {
                mkdir($this->conf['satellite_path'] . $satelliteId . DS . $this->conf['hostgroups']);
            }

            if ($this->conf['minified']) {
                $file = new File($this->conf['satellite_path'] . $satelliteId . DS . $this->conf['hostgroups'] . 'hostgroups_minified' . $this->conf['suffix']);
                if (!$file->exists()) {
                    $file->create();
                }
                $content = $this->fileHeader();
            }

            foreach ($hostgroups as $hostgroup) {
                /** @var \App\Model\Entity\Hostgroup $hostgroup */
                if (!$this->conf['minified']) {
                    $file = new File($this->conf['satellite_path'] . $satelliteId . DS . $this->conf['hostgroups'] . $hostgroup->get('uuid') . $this->conf['suffix']);

                    $content = $this->fileHeader();
                    if (!$file->exists()) {
                        $file->create();
                    }
                }

                $alias = $this->escapeLastBackslash($hostgroup->get('description'));
                if (empty($alias)) {
                    $alias = $hostgroup->get('uuid');
                }
                $content .= $this->addContent('define hostgroup{', 0);
                $content .= $this->addContent('hostgroup_name', 1, $hostgroup->get('uuid'));
                $content .= $this->addContent('alias', 1, $alias);
                $content .= $this->addContent('}', 0);

                if (!$this->conf['minified']) {
                    $file->write($content);
                    $file->close();
                }
            }
            if ($this->conf['minified']) {
                $file->write($content);
                $file->close();
            }
        }
    }

    public function exportSatServicegroups() {
        if (!empty($this->dmConfig) && is_array($this->dmConfig)) {
            foreach ($this->dmConfig as $sat_id => $data) {
                //Create service groups
                if (isset($data['Servicegroup']) && !empty($data['Servicegroup'])) {
                    if (!is_dir($this->conf['satellite_path'] . $sat_id . DS . $this->conf['servicegroups'])) {
                        mkdir($this->conf['satellite_path'] . $sat_id . DS . $this->conf['servicegroups']);
                    }
                    foreach ($data['Servicegroup'] as $servicegroupUuid => $members) {
                        $file = new File($this->conf['satellite_path'] . $sat_id . DS . $this->conf['servicegroups'] . $servicegroupUuid . $this->conf['suffix']);
                        $content = $this->fileHeader();
                        if (!$file->exists()) {
                            $file->create();
                        }

                        $content .= $this->addContent('define servicegroup{', 0);
                        $content .= $this->addContent('servicegroup_name', 1, $servicegroupUuid);
                        $content .= $this->addContent('alias', 1, $servicegroupUuid);

                        $content .= $this->addContent('members', 1, implode(',', $members));
                        $content .= $this->addContent('}', 0);
                        $file->write($content);
                        $file->close();
                    }
                }
            }
        }
    }

    /**
     * @return string
     */
    public function returnReloadCommand() {
        return $this->_systemsettings['MONITORING']['MONITORING.RELOAD'];
    }

    /**
     * @return string
     */
    public function returnAfterExportCommand() {
        return $this->_systemsettings['MONITORING']['MONITORING.AFTER_EXPORT'];
    }

    public function deleteHostPerfdata() {
        $basePath = Configure::read('rrd.path');

        /** @var $DeletedHostsTable DeletedHostsTable */
        $DeletedHostsTable = TableRegistry::getTableLocator()->get('DeletedHosts');

        foreach ($DeletedHostsTable->getDeletedHostsWherePerfdataWasNotDeletedYet() as $deletedHost) {
            /** @var \App\Model\Entity\DeletedHost $deletedHost */
            if (is_dir($basePath . $deletedHost->get('uuid'))) {
                $folder = new Folder($basePath . $deletedHost->get('uuid'));
                $folder->delete();
                unset($folder);
            }

            $deletedHost->set('deleted_perfdata', 1);
            $DeletedHostsTable->save($deletedHost);
        }
    }

    public function deleteServicePerfdata() {
        $basePath = Configure::read('rrd.path');

        /** @var $DeletedServicesTable DeletedServicesTable */
        $DeletedServicesTable = TableRegistry::getTableLocator()->get('DeletedServices');

        foreach ($DeletedServicesTable->getDeletedServicesWherePerfdataWasNotDeletedYet() as $deletedService) {
            /** @var \App\Model\Entity\DeletedService $deletedService */
            foreach (['rrd', 'xml'] as $extension) {
                //Check if perfdata files still exists and if we need to delete them
                $file = $basePath . $deletedService['DeletedService']['host_uuid'] . '/' . $deletedService['DeletedService']['uuid'] . '.' . $extension;
                if (file_exists($file)) {
                    unlink($file);
                }
            }
            $deletedService->set('deleted_perfdata', 1);
            $DeletedServicesTable->save($deletedService);
        }
    }

    public function deleteAllConfigfiles() {
        $result = scandir($this->conf['path'] . DS . 'config');
        foreach ($result as $filename) {
            if (!in_array($filename, ['.', '..'])) {
                if (is_dir($this->conf['path'] . DS . 'config' . DS . $filename)) {
                    $folder = new Folder($this->conf['path'] . DS . 'config' . DS . $filename);
                    $folder->delete();
                    unset($folder);
                }
            }
        }
    }

    /**
     * @param null|resource $file
     *
     * @return string
     */
    public function fileHeader($file = null) {
        $header = ";#########################################################################
;#    DO NOT EDIT THIS FILE BY HAND -- YOUR CHANGES WILL BE OVERWRITTEN  #
;#                                                                       #
;#                   File generated by openITCOCKPIT                     #
;#                                                                       #
;#                        Created: " . date('d.m.Y H:i') . "                      #
;#########################################################################

;Weblinks:
; https://assets.nagios.com/downloads/nagioscore/docs/nagioscore/4/en/objectdefinitions.html
; https://assets.nagios.com/downloads/nagioscore/docs/nagioscore/4/en/objecttricks.html
\n";
        if (is_resource($file)) {
            fwrite($file, $header);
        } else {
            return $header;
        }
    }

    /**
     * @param null|resource $file
     *
     * @return string
     */
    public function hashFileHeader($file = null) {
        $header = "#########################################################################
#    DO NOT EDIT THIS FILE BY HAND -- YOUR CHANGES WILL BE OVERWRITTEN  #
#                                                                       #
#                   File generated by openITCOCKPIT                     #
#                                                                       #
#                        Created: " . date('d.m.Y H:i') . "                      #
#########################################################################
#
#Weblinks:
# https://assets.nagios.com/downloads/nagioscore/docs/nagioscore/4/en/configmain.html#resource_file
# https://assets.nagios.com/downloads/nagioscore/docs/nagioscore/4/en/macrolist.html#user
";
        if (is_resource($file)) {
            fwrite($file, $header);
        } else {
            return $header;
        }
    }

    /**
     * @param string $str
     * @param int $deep
     * @param null|string $value
     * @param bool $newline
     *
     * @return string
     */
    public function addContent($str, $deep = 1, $value = null, $newline = true) {
        $c = "";
        $i = 0;
        while ($i < $deep) {
            $c .= '    ';
            $i++;
        }
        $c .= $str;

        if ($value !== null) {
            while ((strlen($c) < 40)) {
                $c .= ' ';
            }
        }

        if ($value !== null) {
            $c .= $value;
        }

        if ($newline === true) {
            $c .= PHP_EOL;
        }

        return $c;
    }

    public function escapeLastBackslash($str = '') {
        if (mb_substr($str, -1) === '\\') {
            $str = sprintf('%s\\', $str); //Add a \ to the end of the string - because last char is a \
        }
        return $str;
    }

    private function createMissingOitcAgentActiveChecks() {
        /** @var HostsTable $HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');
        /** @var ServicetemplatesTable $ServicetemplatesTable */
        $ServicetemplatesTable = TableRegistry::getTableLocator()->get('Servicetemplates');
        /** @var HosttemplatesTable $HosttemplatesTable */
        $HosttemplatesTable = TableRegistry::getTableLocator()->get('Hosttemplates');
        /** @var ServicesTable $ServicesTable */
        $ServicesTable = TableRegistry::getTableLocator()->get('Services');

        try {
            $servicetemplate = $ServicetemplatesTable->getServicetemplateByName('OITC_AGENT_ACTIVE', OITC_AGENT_SERVICE);
            $servicetemplateId = $servicetemplate->get('id');
            $hosts = $HostsTable->getHostsThatUseOitcAgentForExport();

            foreach ($hosts as $host) {
                $hostId = $host['id'];
                if (!$HostsTable->hasHostServiceFromServicetemplateId($hostId, $servicetemplateId)) {
                    //Create active oITC Agent check
                    $serviceData = [
                        'uuid'               => UUID::v4(),
                        'host_id'            => $hostId,
                        'servicetemplate_id' => $servicetemplateId,
                        'service_type'       => OITC_AGENT_SERVICE,

                        'flap_detection_enabled' => 0
                    ];

                    $service = $ServicesTable->newEntity($serviceData);
                    $ServicesTable->save($service);
                }
            }

        } catch (\Exception $e) {
            debug($e->getMessage());
            //Ignore error while export
        }
    }

    private function createOitcAgentJsonConfig() {
        /** @var HostsTable $HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');
        /** @var ServicesTable $ServicesTable */
        $ServicesTable = TableRegistry::getTableLocator()->get('Services');
        /** @var ProxiesTable $ProxiesTable */
        $ProxiesTable = TableRegistry::getTableLocator()->get('Proxies');
        $proxySettings = $ProxiesTable->getSettings();

        $hosts = $HostsTable->getHostsThatUseOitcAgentForExport();
        if (empty($hosts)) {
            return;
        }

        $configFile = sprintf('/opt/openitc/receiver/etc/production_%s.json', date('Y-m-d-H-i-s'));
        $outfile = '/opt/openitc/receiver/etc/production.json';
        $config = [];
        foreach ($hosts as $hostId => $host) {
            $hostUuid = $host['uuid'];

            if (!empty($host['agentconfig']) || !empty($host['agenthostscache'])) {
                $services = $ServicesTable->getAllOitcAgentServicesByHostIdForExport($hostId);

                if (!empty($services)) {
                    if (!empty($host['agentconfig'])) {
                        $config[$hostUuid] = [
                            'name'       => $host['name'],
                            'address'    => $host['address'],
                            'uuid'       => $hostUuid,
                            'port'       => $host['agentconfig'] && $host['agentconfig']['port'] ? $host['agentconfig']['port'] : '',
                            'proxy'      => $host['agentconfig'] && $host['agentconfig']['proxy'] && $host['agentconfig']['proxy'] == 1 ? $proxySettings['ipaddress'] . ':' . $proxySettings['port'] : '',
                            'use_https'  => $host['agentconfig'] && $host['agentconfig']['use_https'] ? $host['agentconfig']['use_https'] : '',
                            'insecure'   => $host['agentconfig'] && $host['agentconfig']['insecure'] ? $host['agentconfig']['insecure'] : '',
                            'basic_auth' => $host['agentconfig'] && $host['agentconfig']['basic_auth'] ? $host['agentconfig']['basic_auth'] : '',
                            'username'   => $host['agentconfig'] && $host['agentconfig']['username'] ? $host['agentconfig']['username'] : '',
                            'password'   => $host['agentconfig'] && $host['agentconfig']['password'] ? $host['agentconfig']['password'] : '',
                            'mode'       => 'pull',
                            'checks'     => []
                        ];
                    } else if (!empty($host['agenthostscache'])) {
                        $config[$hostUuid] = [
                            'name'    => $host['name'],
                            'address' => $host['address'],
                            'uuid'    => $hostUuid,
                            'mode'    => 'push',
                            'checks'  => []
                        ];
                    }

                    foreach ($services as $service) {
                        $servicename = $service['name'];
                        if ($servicename === null || $servicename === '') {
                            $servicename = $service['servicetemplate']['name'];
                        }

                        $config[$hostUuid]['checks'][] = [
                            'plugin'      => $service['servicetemplate']['agentcheck']['plugin_name'],
                            'servicename' => $servicename,
                            'uuid'        => $service['uuid'],
                            'args'        => $service['args_for_config']
                        ];
                    }
                }
            }
        }

        file_put_contents($configFile, json_encode($config, JSON_PRETTY_PRINT));

        if (file_exists($outfile)) {
            unlink($outfile);
        }
        symlink($configFile, $outfile);
    }
}
