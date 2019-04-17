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
use App\Model\Table\CommandsTable;
use App\Model\Table\ContactgroupsTable;
use App\Model\Table\ContactsTable;
use App\Model\Table\DeletedHostsTable;
use App\Model\Table\DeletedServicesTable;
use App\Model\Table\HostdependenciesTable;
use App\Model\Table\HostgroupsTable;
use App\Model\Table\HostsTable;
use App\Model\Table\HosttemplatesTable;
use App\Model\Table\MacrosTable;
use App\Model\Table\TimeperiodsTable;
use Cake\Filesystem\File;
use Cake\Filesystem\Folder;
use Cake\ORM\TableRegistry;
use itnovum\openITCOCKPIT\Core\KeyValueStore;

/**
 * Class NagiosExportTask
 * @property Hosttemplate $Hosttemplate
 * @property Timeperiod $Timeperiod
 * @property Contact $Contact
 * @property Contactgroup $Contactgroup
 * @property Container $Container
 * @property Customvariable $Customvariable
 * @property Hosttemplatecommandargumentvalue $Hosttemplatecommandargumentvalue
 * @property Servicetemplatecommandargumentvalue $Servicetemplatecommandargumentvalue
 * @property Hostcommandargumentvalue $Hostcommandargumentvalue
 * @property Servicecommandargumentvalue $Servicecommandargumentvalue
 * @property Hostgroup $Hostgroup
 * @property Hostescalation $Hostescalation
 * @property Host $Host
 * @property Servicetemplate $Servicetemplate
 * @property Service $Service
 * @property Systemsetting $Systemsetting
 * @property Serviceescalation $Serviceescalation
 * @property Servicegroup $Servicegroup
 * @property Hostdependency $Hostdependency
 * @property Servicedependency $Servicedependency
 * @property DeletedService $DeletedService
 * @property DeletedHost $DeletedHost
 * @property Serviceeventcommandargumentvalue $Serviceeventcommandargumentvalue
 * @property Servicetemplateeventcommandargumentvalue $Servicetemplateeventcommandargumentvalue
 * @property Satellite $Satellite
 */
class NagiosExportTask extends AppShell {
    /**
     * This code gets executed by the sudo_server!
     * @var array
     */
    public $uses = [
        'Hosttemplate',
        'Timeperiod',
        'Contact',
        'Contactgroup',
        'Container',
        'Customvariable',
        'Hosttemplatecommandargumentvalue',
        'Servicetemplatecommandargumentvalue',
        'Hostcommandargumentvalue',
        'Servicecommandargumentvalue',
        'Hostgroup',
        'Hostescalation',
        'Host',
        'Servicetemplate',
        'Service',
        'Systemsetting',
        'Serviceescalation',
        'Servicegroup',
        'Hostdependency',
        'Servicedependency',
        'DeletedService',
        'DeletedHost',
        'Serviceeventcommandargumentvalue',
        'Servicetemplateeventcommandargumentvalue',
        'Calendar'
    ];

    /**
     * NagiosExportTask constructor.
     */
    public function __construct() {
        parent::__construct();
        //Loading components
        $this->init();
    }

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
     * SudoServer normally runs 24/7 so we need to refresh
     * class variables for each export, may be some thing changed in
     * Systemsettings, Satelliteconfig, or in on of the config files
     */
    public function init() {
        App::uses('Component', 'Controller');
        App::uses('ConstantsComponent', 'Controller/Component');
        $this->Constants = new ConstantsComponent();

        Configure::load('nagios');
        Configure::load('rrd');
        $this->conf = Configure::read('nagios.export');
        /** @var $Systemsettings App\Model\Table\SystemsettingsTable */
        $Systemsettings = TableRegistry::getTableLocator()->get('Systemsettings');
        $this->_systemsettings = $Systemsettings->findAsArray();
        $this->FRESHNESS_THRESHOLD_ADDITION = (int)$this->_systemsettings['MONITORING']['MONITORING.FRESHNESS_THRESHOLD_ADDITION'];

        //Loading external tasks
        $this->__loadExternTasks();

        //Loading distributed Monitoring support, if plugin is loaded
        $this->dm = false;
        $modulePlugins = array_filter(CakePlugin::loaded(), function ($value) {
            return strpos($value, 'Module') !== false;
        });


        /** @var $TimeperiodsTable TimeperiodsTable */
        $TimeperiodsTable = TableRegistry::getTableLocator()->get('Timeperiods');
        /** @var $CommandsTable CommandsTable */
        $CommandsTable = TableRegistry::getTableLocator()->get('Commands');

        $this->TimeperiodUuidsCache = new KeyValueStore();
        $this->TimeperiodUuidsCache->setArray($TimeperiodsTable->getAllTimeperiodsUuidsAsList());

        $this->CommandUuidsCache = new KeyValueStore();
        $this->CommandUuidsCache->setArray($CommandsTable->getAllCommandsUuidsAsList());

        $this->HosttemplateHostgroupsCache = new KeyValueStore();

        if (in_array('DistributeModule', $modulePlugins)) {
            $this->dm = true;
            $this->dmConfig = [];

            //Loading external Model
            $this->Satellite = ClassRegistry::init('DistributeModule.Satellite');
            $this->Satellites = $this->Satellite->find('all');

            //Create default config folder for sat systems
            if (!is_dir($this->conf['satellite_path'])) {
                mkdir($this->conf['satellite_path']);
            }

            //Create rollout folder
            if (!is_dir($this->conf['rollout'])) {
                mkdir($this->conf['rollout']);
            }

            foreach ($this->Satellites as $satellite) {
                if (!is_dir($this->conf['satellite_path'] . $satellite['Satellite']['id'])) {
                    mkdir($this->conf['satellite_path'] . $satellite['Satellite']['id']);
                }

                if (!is_dir($this->conf['satellite_path'] . $satellite['Satellite']['id'] . DS . $this->conf['config'])) {
                    mkdir($this->conf['satellite_path'] . $satellite['Satellite']['id'] . DS . $this->conf['config']);
                }
            }

        }
    }

    /**
     * @param null|string $uuid
     */
    public function exportCommands($uuid = null) {
        /** @var $CommandsTable CommandsTable */
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
        /** @var $ContactsTable ContactsTable */
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
                $content .= $this->nl();
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
        /** @var $ContactgroupsTable ContactgroupsTable */
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
        /** @var $HosttemplatesTable HosttemplatesTable */
        $HosttemplatesTable = TableRegistry::getTableLocator()->get('Hosttemplates');
        $hosttemplates = $HosttemplatesTable->getHosttemplatesForExport();

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

            $content .= $this->nl();
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


            $content .= $this->nl();
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

            $content .= $this->nl();
            $content .= $this->addContent(';Flap detection settings:', 1);
            $content .= $this->addContent('flap_detection_enabled', 1, $hosttemplate->get('flap_detection_enabled'));
            if ($hosttemplate->get('flap_detection_enabled') === 1) {
                if ($hosttemplate->getHostFlapDetectionOptionsForCfg()) {
                    $content .= $this->addContent('flap_detection_options', 1, $hosttemplate->getHostFlapDetectionOptionsForCfg());
                }
            }

            $content .= $this->nl();
            $content .= $this->addContent(';Everything else:', 1);
            $content .= $this->addContent('process_perf_data', 1, $hosttemplate->get('process_performance_data'));

            if (!empty($hosttemplate->get('notes')))
                $content .= $this->addContent('notes', 1, $this->escapeLastBackslash($hosttemplate->get('notes')));


            if ($hosttemplate->hasCustomvariables()) {
                $content .= $this->nl();
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

        /** @var $HosttemplatesTable HosttemplatesTable */
        $HosttemplatesTable = TableRegistry::getTableLocator()->get('Hosttemplates');

        foreach ($hosts as $host) {
            /** @var $host \App\Model\Entity\Host */

            if (!$this->conf['minified']) {
                $file = new File($this->conf['path'] . $this->conf['hosts'] . $host->get('uuid') . $this->conf['suffix']);
                $content = $this->fileHeader();
                if (!$file->exists()) {
                    $file->create();
                }
            }

            $content .= $this->addContent('define host{', 0);
            $content .= $this->addContent('use', 1, $host->get('hosttemplate')->get('uuid'));
            $content .= $this->addContent('host_name', 1, $host->get('uuid'));
            $content .= $this->addContent('display_name', 1, $this->escapeLastBackslash($host->get('name')));
            $content .= $this->addContent('address', 1, $this->escapeLastBackslash($host->get('address')));


            if (!empty($host->get('description')))
                $content .= $this->addContent('alias', 1, $this->escapeLastBackslash($host->get('description')));

            if ($host->hasParentHostsForExport()) {
                $content .= $this->addContent('parents', 1, $host->getParentHostsForCfg());
            }

            $content .= $this->nl();
            $content .= $this->addContent(';Check settings:', 1);
            $commandarguments = null;
            if ($host->isSatelliteHost() === false) {
                if (!empty($host->get('hostcommandargumentvalues'))) {
                    if ($host->get('command_id') === null) {
                        //Host has own command arguments but uses the same command as the host template
                        $commandId = $host->get('hosttemplate')->get('command_id');
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
                $checkInterval = $host->get('hosttemplate')->get('check_interval');
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


            $content .= $this->nl();
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

            if (strlen($host->getNotificationOptionsForCfg()) > 0) {
                $content .= $this->addContent('notification_options', 1, $host->getNotificationOptionsForCfg());
            }

            $content .= $this->nl();
            $content .= $this->addContent(';Flap detection settings:', 1);

            if ($host->get('flap_detection_enabled') === 1 || $host->get('flap_detection_enabled') === 0)
                $content .= $this->addContent('flap_detection_enabled', 1, $host->get('flap_detection_enabled'));

            if ($host->get('flap_detection_enabled') === 1 && strlen($host->getFlapdetectionOptionsForCfg()) > 0) {
                $content .= $this->addContent('flap_detection_options', 1, $host->getFlapdetectionOptionsForCfg());
            }

            $content .= $this->nl();
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
                $content .= $this->nl();
                $content .= $this->addContent(';Hostgroup memberships:', 1);
                $content .= $this->addContent('hostgroups', 1, $host->getHostgroupsForCfg());
            } else if (empty($host->get('hostgroups')) && strlen($hosttemplateHostgroupsForCfg) > 0) {
                //Use host groups of host template configuration
                $content .= $this->nl();
                $content .= $this->addContent(';Hostgroup memberships:', 1);
                $content .= $this->addContent('hostgroups', 1, $hosttemplateHostgroupsForCfg);
            }

            if ($host->hasCustomvariables()) {
                $content .= $this->nl();
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
                $this->exportSatHost($host, $HosttemplatesTable);
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
     */
    private function exportSatHost(\App\Model\Entity\Host $host, HosttemplatesTable $HosttemplatesTable) {
        $satelliteId = $host->get('satellite_id');

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
        $content .= $this->addContent('use', 1, $host->get('hosttemplate')->get('uuid'));
        $content .= $this->addContent('host_name', 1, $host->get('uuid'));
        $content .= $this->addContent('display_name', 1, $this->escapeLastBackslash($host->get('name')));
        $content .= $this->addContent('address', 1, $this->escapeLastBackslash($host->get('address')));


        if ($host->get('description'))
            $content .= $this->addContent('alias', 1, $this->escapeLastBackslash($host->get('description')));

        $parenthosts = $host->getParentHostsForSatCfgAsArray();
        if (!empty($parenthosts)) {
            $content .= $this->addContent('parents', 1, implode(',', $parenthosts));
        }

        $content .= $this->nl();
        $content .= $this->addContent(';Check settings:', 1);

        if (!empty($host->get('hostcommandargumentvalues'))) {
            if ($host->get('command_id') === null) {
                //Host has own command arguments but uses the same command as the host template
                $commandId = $host->get('hosttemplate')->get('command_id');
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

        $content .= $this->nl();
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

        if (strlen($host->getNotificationOptionsForCfg()) > 0) {
            $content .= $this->addContent('notification_options', 1, $host->getNotificationOptionsForCfg());
        }

        $content .= $this->nl();
        $content .= $this->addContent(';Flap detection settings:', 1);

        if ($host->get('flap_detection_enabled') === 1 || $host->get('flap_detection_enabled') === 0)
            $content .= $this->addContent('flap_detection_enabled', 1, $host->get('flap_detection_enabled'));

        if ($host->get('flap_detection_enabled') === 1 && strlen($host->getFlapdetectionOptionsForCfg()) > 0) {
            $content .= $this->addContent('flap_detection_options', 1, $host->getFlapdetectionOptionsForCfg());
        }

        $content .= $this->nl();
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
            $content .= $this->nl();
            $content .= $this->addContent(';Hostgroup memberships:', 1);
            $content .= $this->addContent('hostgroups', 1, $host->getHostgroupsForCfg());
        } else if (empty($host->get('hostgroups')) && strlen($hosttemplateHostgroupsForCfg) > 0) {
            //Use host groups of host template configuration
            $content .= $this->nl();
            $content .= $this->addContent(';Hostgroup memberships:', 1);
            $content .= $this->addContent('hostgroups', 1, $hosttemplateHostgroupsForCfg);
        }

        if ($host->hasCustomvariables()) {
            $content .= $this->nl();
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
        if ($uuid !== null) {
            $_servicetemplates = [];
            $_servicetemplates[] = $this->Servicetemplate->findByUuid($uuid);
        } else {
            $_servicetemplates = $this->Servicetemplate->find('all', [
                'recursive' => -1,
                'contain'   => [
                    'CheckPeriod',
                    'NotifyPeriod',
                    'CheckCommand',
                    'EventhandlerCommand',
                    'Customvariable',
                    'Servicetemplatecommandargumentvalue'      => [
                        'Commandargument',
                    ],
                    'Servicetemplateeventcommandargumentvalue' => [
                        'Commandargument',
                    ],
                    'Contact',
                    'Contactgroup',
                ],
            ]);
        }

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

        foreach ($_servicetemplates as $servicetemplates) {
            if (!$this->conf['minified']) {
                $file = new File($this->conf['path'] . $this->conf['servicetemplates'] . $servicetemplates['Servicetemplate']['uuid'] . $this->conf['suffix']);
                $content = $this->fileHeader();
                if (!$file->exists()) {
                    $file->create();
                }
            }

            $commandarguments = [];
            if (!empty($servicetemplates['Servicetemplatecommandargumentvalue'])) {
                //Select command arguments + command, because we have arguments!
                $commandarguments = Hash::sort($servicetemplates['Servicetemplatecommandargumentvalue'], '{n}.Commandargument.name', 'asc', 'natural');
            }

            $content .= $this->addContent('define service{', 0);
            $content .= $this->addContent('register', 1, 0);
            $content .= $this->addContent('use', 1, '689bfdd01af8a21c4a4706c5117849c2fc2c3f38');
            $content .= $this->addContent('name', 1, $servicetemplates['Servicetemplate']['uuid']);
            $content .= $this->addContent('display_name', 1, $this->escapeLastBackslash(
                $servicetemplates['Servicetemplate']['name']
            ));
            $content .= $this->addContent('service_description', 1, $servicetemplates['Servicetemplate']['uuid']);

            $content .= $this->nl();
            $content .= $this->addContent(';Check settings:', 1);
            if (isset($commandarguments) && !empty($commandarguments)) {
                $content .= $this->addContent('check_command', 1, $servicetemplates['CheckCommand']['uuid'] . '!' . implode('!', Hash::extract($commandarguments, '{n}.value')) . '; ' . implode('!', Hash::extract($commandarguments, '{n}.Commandargument.human_name')));
            } else {
                $content .= $this->addContent('check_command', 1, $servicetemplates['CheckCommand']['uuid']);
            }

            if (isset($commandarguments)) {
                unset($commandarguments);
            }

            $content .= $this->addContent('initial_state', 1, $this->_systemsettings['MONITORING']['MONITORING.SERVICE.INITSTATE']);
            $content .= $this->addContent('check_period', 1, $servicetemplates['CheckPeriod']['uuid']);
            $content .= $this->addContent('check_interval', 1, $servicetemplates['Servicetemplate']['check_interval']);
            $content .= $this->addContent('retry_interval', 1, $servicetemplates['Servicetemplate']['retry_interval']);
            $content .= $this->addContent('max_check_attempts', 1, $servicetemplates['Servicetemplate']['max_check_attempts']);
            $content .= $this->addContent('active_checks_enabled', 1, $servicetemplates['Servicetemplate']['active_checks_enabled']);
            $content .= $this->addContent('passive_checks_enabled', 1, 1);

            if ($servicetemplates['Servicetemplate']['freshness_checks_enabled'] > 0) {
                $content .= $this->addContent('check_freshness', 1, 1);

                if ((int)$servicetemplates['Servicetemplate']['freshness_threshold'] > 0) {
                    $content .= $this->addContent('freshness_threshold', 1, (int)$servicetemplates['Servicetemplate']['freshness_threshold'] + $this->FRESHNESS_THRESHOLD_ADDITION);
                }
            }


            $content .= $this->nl();
            $content .= $this->addContent(';Notification settings:', 1);
            $content .= $this->addContent('notifications_enabled', 1, 1);

            $contacts = Hash::extract($servicetemplates['Contact'], '{n}.uuid');
            if (!empty($contacts))
                $content .= $this->addContent('contacts', 1, implode(',', $contacts));

            $contactgroups = Hash::extract($servicetemplates['Contactgroup'], '{n}.uuid');
            if (!empty($contactgroups))
                $content .= $this->addContent('contact_groups', 1, implode(',', $contactgroups));
            $content .= $this->addContent('notification_interval', 1, $servicetemplates['Servicetemplate']['notification_interval']);
            if ($servicetemplates['NotifyPeriod']['uuid'] !== null && $servicetemplates['NotifyPeriod']['uuid'] !== '') {
                $content .= $this->addContent('notification_period', 1, $servicetemplates['NotifyPeriod']['uuid']);
            }

            $serviceNotificationString = $this->serviceNotificationString($servicetemplates['Servicetemplate']);
            if (!empty($serviceNotificationString)) {
                $content .= $this->addContent('notification_options', 1, $serviceNotificationString);
            }

            $content .= $this->nl();
            $content .= $this->addContent(';Flap detection settings:', 1);
            $content .= $this->addContent('flap_detection_enabled', 1, $servicetemplates['Servicetemplate']['flap_detection_enabled']);
            if ($servicetemplates['Servicetemplate']['flap_detection_enabled'] == 1) {
                $content .= $this->addContent('flap_detection_options', 1, $this->serviceFlapdetectionString($servicetemplates['Servicetemplate']));
            }

            $content .= $this->nl();
            $content .= $this->addContent(';Everything else:', 1);
            if (isset($servicetemplates['Servicetemplate']['process_performance_data'])) {
                $content .= $this->addContent('process_perf_data', 1, $servicetemplates['Servicetemplate']['process_performance_data']);
            }

            if (isset($servicetemplates['Servicetemplate']['is_volatile'])) {
                $content .= $this->addContent('is_volatile', 1, (int)$servicetemplates['Servicetemplate']['is_volatile']);
            }

            if (!empty($servicetemplates['Servicetemplate']['notes']))
                $content .= $this->addContent('notes', 1, $servicetemplates['Servicetemplate']['notes']);

            //Export event handlers to template
            $eventarguments = [];
            if (isset($servicetemplates['EventhandlerCommand']['id']) && $servicetemplates['EventhandlerCommand']['id'] !== null) {
                $content .= $this->addContent(';Event handler:', 1);
                if (!empty($servicetemplates['Servicetemplateeventcommandargumentvalue'])) {
                    //Select command arguments + command, because we have arguments!
                    $eventarguments = Hash::sort($servicetemplates['Servicetemplateeventcommandargumentvalue'], '{n}.Commandargument.name', 'asc', 'natural');
                }

                if (isset($eventarguments) && !empty($eventarguments)) {
                    $content .= $this->addContent('event_handler', 1, $servicetemplates['EventhandlerCommand']['uuid'] . '!' . implode('!', Hash::extract($eventarguments, '{n}.value')) . '; ' . implode('!', Hash::extract($eventarguments, '{n}.Commandargument.human_name')));
                } else {
                    $content .= $this->addContent('event_handler', 1, $servicetemplates['EventhandlerCommand']['uuid']);
                }

                if (isset($eventarguments)) {
                    unset($eventarguments);
                }
            }

            if (!empty($servicetemplates['Customvariable'])) {
                $content .= $this->nl();
                $content .= $this->addContent(';Custom  variables:', 1);
                foreach ($servicetemplates['Customvariable'] as $customvariable) {
                    $content .= $this->addContent('_' . $customvariable['name'], 1, $customvariable['value']);
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
     * @param array $options with keys limit and offset
     */
    public function exportServices($uuid = null, $options = []) {
        if (!is_dir($this->conf['path'] . $this->conf['services'])) {
            mkdir($this->conf['path'] . $this->conf['services']);
        }

        if ($this->conf['minified']) {
            $fileName = $this->conf['path'] . $this->conf['services'] . 'services_minified' . $this->conf['suffix'];
            if (isset($options['limit']) && isset($options['offset'])) {
                $fileName = $this->conf['path'] . $this->conf['services'] . 'services_minified_' . $options['limit'] . '_' . $options['offset'] . $this->conf['suffix'];
            }
            $file = new File($fileName);
            if (!$file->exists()) {
                $file->create();
            }
            $content = $this->fileHeader();
        }

        $hosts = $this->Host->find('all', [
            'contain'    => [],
            'recursive'  => -1,
            'conditions' => [
                'Host.disabled' => 0,
            ],
            'fields'     => [
                'Host.id',
                'Host.uuid',
                'Host.satellite_id',
            ],
        ]);

        foreach ($hosts as $host) {
            $services = $this->Service->find('all', [
                'recursive'  => -1,
                'conditions' => [
                    'Service.disabled' => 0,
                    'Service.host_id'  => $host['Host']['id'],
                ],
                'contain'    => [
                    'Servicetemplate'                  => [
                        'fields'       => [
                            'Servicetemplate.id',
                            'Servicetemplate.uuid',
                            'Servicetemplate.name',
                            'Servicetemplate.check_interval',
                            'Servicetemplate.eventhandler_command_id',
                        ],
                        'Servicegroup' => [
                            'fields' => [
                                'Servicegroup.uuid',
                            ],
                        ],
                    ],
                    'CheckPeriod',
                    'CheckCommand',
                    'NotifyPeriod',
                    'Servicecommandargumentvalue'      => [
                        'Commandargument',
                    ],
                    'Serviceeventcommandargumentvalue' => [
                        'Commandargument',
                    ],
                    'EventhandlerCommand',
                    'Customvariable',
                    'Servicegroup',
                    'Contactgroup',
                    'Contact',
                ],
            ]);
            foreach ($services as $service) {
                /** @var $CommandsTable CommandsTable */
                $CommandsTable = TableRegistry::getTableLocator()->get('Commands');

                if (!$this->conf['minified']) {
                    $file = new File($this->conf['path'] . $this->conf['services'] . $service['Service']['uuid'] . $this->conf['suffix']);
                    $content = $this->fileHeader();
                    if (!$file->exists()) {
                        $file->create();
                    }
                }


                $commandarguments = [];
                if (!empty($service['Servicecommandargumentvalue'])) {
                    $commandarguments = Hash::sort($service['Servicecommandargumentvalue'], '{n}.Commandargument.name', 'asc', 'natural');
                }

                $content .= $this->addContent('define service{', 0);
                $content .= $this->addContent('use', 1, $service['Servicetemplate']['uuid']);
                $content .= $this->addContent('host_name', 1, $host['Host']['uuid']);

                $content .= $this->addContent('name', 1, $service['Service']['uuid']);
                if ($service['Service']['name'] !== null && $service['Service']['name'] !== '') {
                    $content .= $this->addContent('display_name', 1, $this->escapeLastBackslash(
                        $service['Service']['name'])
                    );
                } else {
                    $content .= $this->addContent('display_name', 1, $this->escapeLastBackslash(
                        $service['Servicetemplate']['name'])
                    );
                }

                $content .= $this->addContent('service_description', 1, $service['Service']['uuid']);

                $content .= $this->nl();
                $content .= $this->addContent(';Check settings:', 1);

                $eventcommandarguments = [];
                if (!empty($service['Serviceeventcommandargumentvalue'])) {
                    $eventcommandarguments = Hash::sort($service['Serviceeventcommandargumentvalue'], '{n}.Commandargument.name', 'asc', 'natural');
                }

                if ($host['Host']['satellite_id'] == 0) {
                    if (isset($commandarguments) && !empty($commandarguments)) {
                        if ($service['CheckCommand']['uuid'] !== null && $service['CheckCommand']['uuid'] !== '') {
                            //The host has its own check_command and own command args
                            $content .= $this->addContent('check_command', 1, $service['CheckCommand']['uuid'] . '!' . implode('!', Hash::extract($commandarguments, '{n}.value')) . '; ' . implode('!', Hash::extract($commandarguments, '{n}.Commandargument.human_name')));
                        } else {
                            //The services only has its own command args, but the same command as the servicetemplate
                            //This is not supported by nagios, so we need to select the command and create the
                            //config with the right command uuid
                            $command_id = Hash::extract($commandarguments, '{n}.Commandargument.command_id');
                            if (!empty($command_id)) {
                                $command_id = array_pop($command_id);
                                $command = $CommandsTable->getCommandUuidByCommandId($command_id);
                                $content .= $this->addContent('check_command', 1, $command . '!' . implode('!', Hash::extract($commandarguments, '{n}.value')) . '; ' . implode('!', Hash::extract($commandarguments, '{n}.Commandargument.human_name')));
                                unset($command);
                            }
                        }
                    } else {
                        if ($service['CheckCommand']['uuid'] !== null && $service['CheckCommand']['uuid'] !== '')
                            $content .= $this->addContent('check_command', 1, $service['CheckCommand']['uuid']);
                    }


                    // Only export event handlers if the services is on the master system. for SAT event handlers see $this->exportSatService()
                    if (isset($eventcommandarguments) && !empty($eventcommandarguments)) {
                        if ($service['EventhandlerCommand']['uuid'] !== null && $service['EventhandlerCommand']['uuid'] !== '') {
                            //The service has its own event_handler and own event handler args
                            $content .= $this->addContent('event_handler', 1, $service['EventhandlerCommand']['uuid'] . '!' . implode('!', Hash::extract($eventcommandarguments, '{n}.value')) . '; ' . implode('!', Hash::extract($eventcommandarguments, '{n}.Commandargument.human_name')));
                        } else {
                            //The services only has its own event handler args, but the same event handler command as the servicetemplate
                            //This is not supported by nagios, so we need to select the event handler command and create the
                            //config with the right command uuid and pass the arguments of the service
                            $command_id = Hash::extract($eventcommandarguments, '{n}.Commandargument.command_id');
                            if (!empty($command_id)) {
                                $command_id = array_pop($command_id);
                                $command = $CommandsTable->getCommandUuidByCommandId($command_id);
                                $content .= $this->addContent('event_handler', 1, $command . '!' . implode('!', Hash::extract($eventcommandarguments, '{n}.value')) . '; ' . implode('!', Hash::extract($eventcommandarguments, '{n}.Commandargument.human_name')));
                                unset($command);
                            }
                        }
                    } else {
                        //Own event_handler without any handler args
                        if ($service['EventhandlerCommand']['uuid'] !== null && $service['EventhandlerCommand']['uuid'] !== '')
                            $content .= $this->addContent('event_handler', 1, $service['EventhandlerCommand']['uuid']);
                    }


                } else {
                    $content .= $this->addContent('check_command', 1, '2106cf0bf26a82af262c4078e6d9f94eded84d2a');
                }


                if ($service['Service']['check_period_id'] !== null && $service['Service']['check_period_id'] !== '')
                    $content .= $this->addContent('check_period', 1, $service['CheckPeriod']['uuid']);

                if ($service['Service']['check_interval'] !== null && $service['Service']['check_interval'] !== '')
                    $content .= $this->addContent('check_interval', 1, $service['Service']['check_interval']);

                if ($service['Service']['retry_interval'] !== null && $service['Service']['retry_interval'] !== '')
                    $content .= $this->addContent('retry_interval', 1, $service['Service']['retry_interval']);

                if ($service['Service']['max_check_attempts'] !== null && $service['Service']['max_check_attempts'] !== '')
                    $content .= $this->addContent('max_check_attempts', 1, $service['Service']['max_check_attempts']);


                if ($host['Host']['satellite_id'] > 0) {
                    $content .= $this->addContent('active_checks_enabled', 1, 0);
                    $content .= $this->addContent('passive_checks_enabled', 1, 1);
                } else {
                    if ($service['Service']['active_checks_enabled'] !== null && $service['Service']['active_checks_enabled'] !== '') {
                        $content .= $this->addContent('active_checks_enabled', 1, (int)$service['Service']['active_checks_enabled']);
                    }

                    if ($service['Service']['passive_checks_enabled'] !== null && $service['Service']['passive_checks_enabled'] !== '') {
                        $content .= $this->addContent('passive_checks_enabled', 1, (int)$service['Service']['passive_checks_enabled']);
                    }
                }

                if ((int)$service['Service']['freshness_checks_enabled'] > 0 || $host['Host']['satellite_id'] > 0) {

                    if ($host['Host']['satellite_id'] > 0) {
                        //Services gets checked by a SAT-System
                        $content .= $this->addContent('check_freshness', 1, 1);

                        $checkInterrval = null;
                        if ($service['Service']['check_interval'] !== null && $service['Service']['check_interval'] !== '') {
                            $checkInterrval = $service['Service']['check_interval'];
                        } else {
                            $checkInterrval = $service['Servicetemplate']['check_interval'];
                        }

                        $content .= $this->addContent('freshness_threshold', 1, (int)$service['Service']['freshness_threshold'] + $checkInterrval + $this->FRESHNESS_THRESHOLD_ADDITION);
                    } else {
                        //Passive service on the master system
                        $content .= $this->addContent('check_freshness', 1, 1);
                        $content .= $this->addContent('freshness_threshold', 1, (int)$service['Service']['freshness_threshold'] + $this->FRESHNESS_THRESHOLD_ADDITION);
                    }
                }

                $content .= $this->nl();
                $content .= $this->addContent(';Notification settings:', 1);
                if ($service['Service']['notifications_enabled'] !== null && $service['Service']['notifications_enabled'] !== '')
                    $content .= $this->addContent('notifications_enabled', 1, $service['Service']['notifications_enabled']);

                if (!empty($service['Contact']))
                    $content .= $this->addContent('contacts', 1, implode(',', Hash::extract($service['Contact'], '{n}.uuid')));

                if (!empty($service['Contactgroup']))
                    $content .= $this->addContent('contact_groups', 1, implode(',', Hash::extract($service['Contactgroup'], '{n}.uuid')));

                if ($service['Service']['notification_interval'] !== null && $service['Service']['notification_interval'] !== '')
                    $content .= $this->addContent('notification_interval', 1, $service['Service']['notification_interval']);

                if ($service['NotifyPeriod']['uuid'] !== null && $service['NotifyPeriod']['uuid'] !== '')
                    $content .= $this->addContent('notification_period', 1, $service['NotifyPeriod']['uuid']);

                if (
                    ($service['Service']['notify_on_warning'] === '1' || $service['Service']['notify_on_warning'] === '0') ||
                    ($service['Service']['notify_on_unknown'] === '1' || $service['Service']['notify_on_unknown'] === '0') ||
                    ($service['Service']['notify_on_critical'] === '1' || $service['Service']['notify_on_critical'] === '0') ||
                    ($service['Service']['notify_on_recovery'] === '1' || $service['Service']['notify_on_recovery'] === '0') ||
                    ($service['Service']['notify_on_flapping'] === '1' || $service['Service']['notify_on_flapping'] === '0') ||
                    ($service['Service']['notify_on_downtime'] === '1' || $service['Service']['notify_on_downtime'] === '0')
                ) {
                    $content .= $this->addContent('notification_options', 1, $this->serviceNotificationString($service['Service']));
                }

                $content .= $this->nl();
                $content .= $this->addContent(';Flap detection settings:', 1);
                if ($service['Service']['flap_detection_enabled'] === '0' || $service['Service']['flap_detection_enabled'] === '1')
                    $content .= $this->addContent('flap_detection_enabled', 1, $service['Service']['flap_detection_enabled']);

                if (
                    ($service['Service']['flap_detection_on_ok'] === '1' || $service['Service']['flap_detection_on_ok'] === '0') ||
                    ($service['Service']['flap_detection_on_warning'] === '1' || $service['Service']['flap_detection_on_warning'] === '0') ||
                    ($service['Service']['flap_detection_on_unknown'] === '1' || $service['Service']['flap_detection_on_unknown'] === '0') ||
                    ($service['Service']['flap_detection_on_critical'] === '1' || $service['Service']['flap_detection_on_critical'] === '0')
                ) {
                    if ($service['Service']['flap_detection_enabled'] === '1') {
                        $content .= $this->addContent('flap_detection_options', 1, $this->serviceFlapdetectionString($service['Service']));
                    }
                }

                $content .= $this->nl();
                $content .= $this->addContent(';Everything else:', 1);
                if (isset($service['Service']['process_performance_data'])) {
                    if ($service['Service']['process_performance_data'] == 1 || $service['Service']['process_performance_data'] == 0)
                        $content .= $this->addContent('process_perf_data', 1, $service['Service']['process_performance_data']);
                }
                if (isset($service['Service']['is_volatile'])) {
                    if ($service['Service']['is_volatile'] !== null && $service['Service']['is_volatile'] !== '')
                        $content .= $this->addContent('is_volatile', 1, (int)$service['Service']['is_volatile']);
                }
                if (!empty($service['Service']['notes']))
                    $content .= $this->addContent('notes', 1, $service['Service']['notes']);


                if (!empty($service['Customvariable'])) {
                    $content .= $this->nl();
                    $content .= $this->addContent(';Custom  variables:', 1);
                    foreach ($service['Customvariable'] as $customvariable) {
                        $content .= $this->addContent('_' . $customvariable['name'], 1, $customvariable['value']);
                    }
                }

                if (!empty($service['Servicegroup'])) {
                    $content .= $this->addContent('servicegroups', 1, implode(',', Hash::extract($service['Servicegroup'], '{n}.uuid')));
                } else if (empty($service['Servicegroup']) && !empty($service['Servicetemplate']['Servicegroup'])) {
                    $content .= $this->addContent('servicegroups', 1, implode(',', Hash::extract($service['Servicetemplate']['Servicegroup'], '{n}.uuid')));
                }

                $content .= $this->addContent('}', 0);

                if (!$this->conf['minified']) {
                    $file->write($content);
                    $file->close();
                }


                if ($this->dm === true && $host['Host']['satellite_id'] > 0) {

                    $this->exportSatService($service, $host, $commandarguments, $eventcommandarguments);

                    /*
                     * May be not all services in servicegroup 'foo' are available on SAT system 'bar', so we create an array
                     * with all services from system 'bar' in servicegroup 'foo' for each SAT system
                     */
                    if (!empty($service['Servicegroup'])) {
                        foreach ($service['Servicegroup'] as $servicegroup) {
                            $this->dmConfig[$host['Host']['satellite_id']]['Servicegroup'][$servicegroup['uuid']][] = $host['Host']['uuid'] . ',' . $service['Service']['uuid'];
                        }
                    }
                    if (empty($service['Servicegroup']) && !empty($service['Servicetemplate']['Servicegroup'])) {
                        foreach ($service['Servicetemplate']['Servicegroup'] as $servicegroup) {
                            $this->dmConfig[$host['Host']['satellite_id']]['Servicegroup'][$servicegroup['uuid']][] = $host['Host']['uuid'] . ',' . $service['Service']['uuid'];
                        }
                    }
                }

                if (isset($commandarguments)) {
                    unset($commandarguments);
                }

                if (isset($eventcommandarguments)) {
                    unset($eventcommandarguments);
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
     * @param $service
     * @param $host
     * @param $commandarguments
     * @param $eventcommandarguments
     */
    public function exportSatService($service, $host, $commandarguments, $eventcommandarguments) {
        /** @var $CommandsTable CommandsTable */
        $CommandsTable = TableRegistry::getTableLocator()->get('Commands');


        $satelliteId = $host['Host']['satellite_id'];
        if (!is_dir($this->conf['satellite_path'] . $satelliteId . DS . $this->conf['services'])) {
            mkdir($this->conf['satellite_path'] . $satelliteId . DS . $this->conf['services']);
        }

        if (!$this->conf['minified']) {
            $file = new File($this->conf['satellite_path'] . $satelliteId . DS . $this->conf['services'] . $service['Service']['uuid'] . $this->conf['suffix']);
            $content = $this->fileHeader();

        } else {
            $file = new File($this->conf['satellite_path'] . $satelliteId . DS . $this->conf['services'] . 'services_minified' . $this->conf['suffix']);
            $content = '';
        }

        if (!$file->exists()) {
            $file->create();
        }

        $content .= $this->addContent('define service{', 0);
        $content .= $this->addContent('use', 1, $service['Servicetemplate']['uuid']);
        $content .= $this->addContent('host_name', 1, $host['Host']['uuid']);

        $content .= $this->addContent('name', 1, $service['Service']['uuid']);
        if ($service['Service']['name'] !== null && $service['Service']['name'] !== '') {
            $content .= $this->addContent('display_name', 1, $this->escapeLastBackslash(
                $service['Service']['name']
            ));
        } else {
            $content .= $this->addContent('display_name', 1, $this->escapeLastBackslash(
                $service['Servicetemplate']['name']
            ));
        }

        $content .= $this->addContent('service_description', 1, $service['Service']['uuid']);

        $content .= $this->nl();
        $content .= $this->addContent(';Check settings:', 1);

        if (isset($commandarguments) && !empty($commandarguments)) {
            if ($service['CheckCommand']['uuid'] !== null && $service['CheckCommand']['uuid'] !== '') {
                //The host has its own check_command and own command args
                $content .= $this->addContent('check_command', 1, $service['CheckCommand']['uuid'] . '!' . implode('!', Hash::extract($commandarguments, '{n}.value')) . '; ' . implode('!', Hash::extract($commandarguments, '{n}.Commandargument.human_name')));
            } else {
                //The services only has its own command args, but the same command as the servicetemplate
                //This is not supported by nagios, so we need to select the command and create the
                //config with the right comman
                $command_id = Hash::extract($commandarguments, '{n}.Commandargument.command_id');
                if (!empty($command_id)) {
                    $command_id = array_pop($command_id);
                    $command = $CommandsTable->getCommandUuidByCommandId($command_id);
                    $content .= $this->addContent('check_command', 1, $command . '!' . implode('!', Hash::extract($commandarguments, '{n}.value')) . '; ' . implode('!', Hash::extract($commandarguments, '{n}.Commandargument.human_name')));
                    unset($command);
                }
            }
        } else {
            if ($service['CheckCommand']['uuid'] !== null && $service['CheckCommand']['uuid'] !== '')
                $content .= $this->addContent('check_command', 1, $service['CheckCommand']['uuid']);
        }

        // Export event handler to SAT-System
        if (isset($eventcommandarguments) && !empty($eventcommandarguments)) {
            if ($service['EventhandlerCommand']['uuid'] !== null && $service['EventhandlerCommand']['uuid'] !== '') {
                //The service has its own event_handler and own event handler args
                $content .= $this->addContent('event_handler', 1, $service['EventhandlerCommand']['uuid'] . '!' . implode('!', Hash::extract($eventcommandarguments, '{n}.value')) . '; ' . implode('!', Hash::extract($eventcommandarguments, '{n}.Commandargument.human_name')));
            } else {
                //The services only has its own event handler args, but the same event handler command as the servicetemplate
                //This is not supported by nagios, so we need to select the event handler command and create the
                //config with the right command uuid and pass the arguments of the service
                $command_id = Hash::extract($eventcommandarguments, '{n}.Commandargument.command_id');
                if (!empty($command_id)) {
                    $command_id = array_pop($command_id);
                    $command = $CommandsTable->getCommandUuidByCommandId($command_id);
                    $content .= $this->addContent('event_handler', 1, $command . '!' . implode('!', Hash::extract($eventcommandarguments, '{n}.value')) . '; ' . implode('!', Hash::extract($eventcommandarguments, '{n}.Commandargument.human_name')));
                    unset($command);
                }
            }
        } else {
            //Own event_handler without any handler args
            if ($service['EventhandlerCommand']['uuid'] !== null && $service['EventhandlerCommand']['uuid'] !== '')
                $content .= $this->addContent('event_handler', 1, $service['EventhandlerCommand']['uuid']);
        }


        if ($service['Service']['check_period_id'] !== null && $service['Service']['check_period_id'] !== '')
            $content .= $this->addContent('check_period', 1, $service['CheckPeriod']['uuid']);

        if ($service['Service']['check_interval'] !== null && $service['Service']['check_interval'] !== '')
            $content .= $this->addContent('check_interval', 1, $service['Service']['check_interval']);

        if ($service['Service']['retry_interval'] !== null && $service['Service']['retry_interval'] !== '')
            $content .= $this->addContent('retry_interval', 1, $service['Service']['retry_interval']);

        if ($service['Service']['max_check_attempts'] !== null && $service['Service']['max_check_attempts'] !== '')
            $content .= $this->addContent('max_check_attempts', 1, $service['Service']['max_check_attempts']);

        if ($service['Service']['active_checks_enabled'] !== null && $service['Service']['active_checks_enabled'] !== '')
            $content .= $this->addContent('active_checks_enabled', 1, $service['Service']['active_checks_enabled']);

        if ($service['Service']['passive_checks_enabled'] !== null && $service['Service']['passive_checks_enabled'] !== '')
            $content .= $this->addContent('passive_checks_enabled', 1, 1);


        if ($service['Service']['freshness_checks_enabled'] > 0) {
            $content .= $this->addContent('check_freshness', 1, 1);

            if ($service['Service']['freshness_threshold'] !== null && $service['Service']['freshness_threshold'] !== '') {
                $content .= $this->addContent('freshness_threshold', 1, (int)$service['Service']['freshness_threshold'] + $this->FRESHNESS_THRESHOLD_ADDITION);
            }
        }

        $content .= $this->nl();
        $content .= $this->addContent(';Notification settings:', 1);
        if ($service['Service']['notifications_enabled'] !== null && $service['Service']['notifications_enabled'] !== '')
            $content .= $this->addContent('notifications_enabled', 1, $service['Service']['notifications_enabled']);

        if (!empty($service['Contact']))
            $content .= $this->addContent('contacts', 1, implode(',', Hash::extract($service['Contact'], '{n}.uuid')));

        if (!empty($service['Contactgroup']))
            $content .= $this->addContent('contact_groups', 1, implode(',', Hash::extract($service['Contactgroup'], '{n}.uuid')));

        if ($service['Service']['notification_interval'] !== null && $service['Service']['notification_interval'] !== '')
            $content .= $this->addContent('notification_interval', 1, $service['Service']['notification_interval']);

        if ($service['NotifyPeriod']['uuid'] !== null && $service['NotifyPeriod']['uuid'] !== '')
            $content .= $this->addContent('notification_period', 1, $service['NotifyPeriod']['uuid']);

        if (
            ($service['Service']['notify_on_warning'] === '1' || $service['Service']['notify_on_warning'] === '0') ||
            ($service['Service']['notify_on_unknown'] === '1' || $service['Service']['notify_on_unknown'] === '0') ||
            ($service['Service']['notify_on_critical'] === '1' || $service['Service']['notify_on_critical'] === '0') ||
            ($service['Service']['notify_on_recovery'] === '1' || $service['Service']['notify_on_recovery'] === '0') ||
            ($service['Service']['notify_on_flapping'] === '1' || $service['Service']['notify_on_flapping'] === '0') ||
            ($service['Service']['notify_on_downtime'] === '1' || $service['Service']['notify_on_downtime'] === '0')
        ) {
            $content .= $this->addContent('notification_options', 1, $this->serviceNotificationString($service['Service']));
        }

        $content .= $this->nl();
        $content .= $this->addContent(';Flap detection settings:', 1);
        if ($service['Service']['flap_detection_enabled'] === '0' || $service['Service']['flap_detection_enabled'] === '1')
            $content .= $this->addContent('flap_detection_enabled', 1, $service['Service']['flap_detection_enabled']);

        if (
            ($service['Service']['flap_detection_on_ok'] === '1' || $service['Service']['flap_detection_on_ok'] === '0') ||
            ($service['Service']['flap_detection_on_warning'] === '1' || $service['Service']['flap_detection_on_warning'] === '0') ||
            ($service['Service']['flap_detection_on_unknown'] === '1' || $service['Service']['flap_detection_on_unknown'] === '0') ||
            ($service['Service']['flap_detection_on_critical'] === '1' || $service['Service']['flap_detection_on_critical'] === '0')
        ) {
            if ($service['Service']['flap_detection_enabled'] === '1') {
                $content .= $this->addContent('flap_detection_options', 1, $this->serviceFlapdetectionString($service['Service']));
            }
        }

        $content .= $this->nl();
        $content .= $this->addContent(';Everything else:', 1);
        if (isset($service['Service']['process_performance_data'])) {
            if ($service['Service']['process_performance_data'] == 1 || $service['Service']['process_performance_data'] == 0)
                $content .= $this->addContent('process_perf_data', 1, $service['Service']['process_performance_data']);
        }
        if (isset($service['Service']['is_volatile'])) {
            if ($service['Service']['is_volatile'] !== null && $service['Service']['is_volatile'] !== '')
                $content .= $this->addContent('is_volatile', 1, (int)$service['Service']['is_volatile']);
        }
        if (!empty($service['Service']['notes']))
            $content .= $this->addContent('notes', 1, $service['Service']['notes']);


        if (!empty($service['Customvariable'])) {
            $content .= $this->nl();
            $content .= $this->addContent(';Custom  variables:', 1);
            foreach ($service['Customvariable'] as $customvariable) {
                $content .= $this->addContent('_' . $customvariable['name'], 1, $customvariable['value']);
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
        /** @var $HostgroupsTable HostgroupsTable */
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
            /** @var $hostgroup \App\Model\Entity\Hostgroup */
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

    /**
     * @param null|string $uuid
     */
    public function exportServicegroups($uuid = null) {
        if ($uuid !== null) {
            $servicegroups = [];
            $servicegroups[] = $this->Servicegroup->findByUuid($uuid);
        } else {
            $servicegroups = $this->Servicegroup->find('all', [
                'recursive' => -1,
                'fields'    => [
                    'Servicegroup.id',
                    'Servicegroup.uuid',
                    'Servicegroup.description',
                ],
            ]);
        }

        if (!is_dir($this->conf['path'] . $this->conf['servicegroups'])) {
            mkdir($this->conf['path'] . $this->conf['servicegroups']);
        }

        foreach ($servicegroups as $servicegroup) {
            $file = new File($this->conf['path'] . $this->conf['servicegroups'] . $servicegroup['Servicegroup']['uuid'] . $this->conf['suffix']);
            $content = $this->fileHeader();
            if (!$file->exists()) {
                $file->create();
            }

            $content .= $this->addContent('define servicegroup{', 0);
            $content .= $this->addContent('servicegroup_name', 1, $servicegroup['Servicegroup']['uuid']);
            $content .= $this->addContent('alias', 1, $this->escapeLastBackslash(
                $servicegroup['Servicegroup']['description']
            ));

            $content .= $this->addContent('}', 0);
            $file->write($content);
            $file->close();
        }
    }

    /**
     * @param null|string $uuid
     */
    public function exportHostescalations($uuid = null) {
        $query = [
            'recursive' => -1,
            'contain'   => [
                'HostescalationHostMembership'      => [
                    'Host' => [
                        'conditions' => [
                            'Host.disabled' => 0
                        ],
                        'fields'     => [
                            'uuid'
                        ],
                    ],
                ],
                'HostescalationHostgroupMembership' => [
                    'Hostgroup' => [
                        'fields' => [
                            'Hostgroup.uuid'
                        ]
                    ],
                ],
                'Timeperiod'                        => [
                    'fields' => [
                        'uuid',
                    ],
                ],
            ],
        ];

        if ($uuid !== null) {
            $hostescalations = [];
            $query['conditions'] = [
                'Hostescalation.uuid' => $uuid
            ];
            $hostescalations[] = $this->Hostescalation->find('first', $query);
        } else {
            $hostescalations = $this->Hostescalation->find('all', $query);
        }

        if (!is_dir($this->conf['path'] . $this->conf['hostescalations'])) {
            mkdir($this->conf['path'] . $this->conf['hostescalations']);
        }

        foreach ($hostescalations as $hostescalation) {
            if (!empty($hostescalation['HostescalationHostMembership']) || !empty($hostescalation['HostescalationHostgroupMembership'])) {
                $includedHosts = Hash::extract($hostescalation, 'HostescalationHostMembership.{n}[excluded=0].Host.uuid');
                $exludedHosts = Hash::extract($hostescalation, 'HostescalationHostMembership.{n}[excluded=1].Host.uuid');

                // Prefix the hosts with an !
                $_exludedHosts = [];
                foreach ($exludedHosts as $extHost) {
                    $_exludedHosts[] = '!' . $extHost;
                }

                $hosts = Hash::merge($includedHosts, $_exludedHosts);

                $includedHostgroups = Hash::extract($hostescalation, 'HostescalationHostgroupMembership.{n}[excluded=0].Hostgroup.uuid');
                $exludedHostgroups = Hash::extract($hostescalation, 'HostescalationHostgroupMembership.{n}[excluded=1].Hostgroup.uuid');

                // Prefix the hosts with an !
                $_exludedHostgroups = [];
                foreach ($exludedHostgroups as $extHostgroup) {
                    $_exludedHostgroups[] = '!' . $extHostgroup;
                }
                $hostgroups = Hash::merge($includedHostgroups, $_exludedHostgroups);

                if (!empty($includedHosts) || !empty($includedHostgroups)) {
                    $file = new File($this->conf['path'] . $this->conf['hostescalations'] . $hostescalation['Hostescalation']['uuid'] . $this->conf['suffix']);
                    $content = $this->fileHeader();
                    if (!$file->exists()) {
                        $file->create();
                    }

                    $content .= $this->addContent('define hostescalation{', 0);
                    if (!empty($hosts)) {
                        $content .= $this->addContent('host_name', 1, implode(',', $hosts));
                    }

                    if (!empty($hostgroups)) {
                        $content .= $this->addContent('hostgroup_name', 1, implode(',', $hostgroups));
                    }
                    if (!empty($hostescalation['Contact'])) {
                        $content .= $this->addContent('contacts', 1, implode(',', Hash::extract($hostescalation['Contact'], '{n}.uuid')));
                    }
                    if (!empty($hostescalation['Contactgroup'])) {
                        $content .= $this->addContent('contact_groups', 1, implode(',', Hash::extract($hostescalation['Contactgroup'], '{n}.uuid')));
                    }
                    $content .= $this->addContent('first_notification', 1, $hostescalation['Hostescalation']['first_notification']);
                    $content .= $this->addContent('last_notification', 1, $hostescalation['Hostescalation']['last_notification']);
                    $content .= $this->addContent('notification_interval', 1, (int)$hostescalation['Hostescalation']['notification_interval'] * 60);
                    $content .= $this->addContent('escalation_period', 1, $hostescalation['Timeperiod']['uuid']);
                    $hostEscalationString = $this->hostEscalationString($hostescalation['Hostescalation']);
                    if (!empty($hostEscalationString)) {
                        $content .= $this->addContent('escalation_options', 1, $hostEscalationString);
                    }

                    $content .= $this->addContent('}', 0);
                    $file->write($content);
                    $file->close();
                } else {
                    //This hostescalation is broken!
                    $this->Hostescalation->delete($hostescalation['Hostescalation']['id']);
                }
            } else {
                //This hostescalation is broken!
                $this->Hostescalation->delete($hostescalation['Hostescalation']['id']);
            }
        }
    }

    /**
     * @param null|string $uuid
     */
    public function exportServiceescalations($uuid = null) {
        $query = [
            'recursive' => -1,
            'contain'   => [
                'ServiceescalationServiceMembership'      => [
                    'Service' => [
                        'conditions' => [
                            'Service.disabled' => 0
                        ],
                        'fields'     => [
                            'uuid',
                        ],
                        'Host'       => [
                            'fields' => [
                                'uuid',
                            ],
                        ],
                    ],
                ],
                'ServiceescalationServicegroupMembership' => [
                    'Servicegroup' => [
                        'fields' => [
                            'Servicegroup.uuid'
                        ]
                    ],
                ],
                'Timeperiod'                              => [
                    'fields' => [
                        'uuid',
                    ],
                ],
            ],
        ];

        if ($uuid !== null) {
            $serviceescalations = [];
            $query['conditions'] = [
                'Serviceescalation.uuid' => $uuid
            ];
            $serviceescalations[] = $this->Serviceescalation->find('first', $query);
        } else {
            $serviceescalations = $this->Serviceescalation->find('all', $query);
        }
        foreach ($serviceescalations as $serviceescalation) {
            if (!empty($serviceescalation['ServiceescalationServiceMembership']) || !empty($serviceescalation['ServiceescalationServicegroupMembership'])) {

                $includedServices = array_filter(Hash::extract($serviceescalation['ServiceescalationServiceMembership'], '{n}[excluded=0].Service'));

                $exludedServices = array_filter(Hash::extract($serviceescalation['ServiceescalationServiceMembership'], '{n}[excluded=1].Service'));

                $includedServicegroups = Hash::extract($serviceescalation['ServiceescalationServicegroupMembership'], '{n}[excluded=0].Servicegroup.uuid');
                $exludedServicegroups = Hash::extract($serviceescalation['ServiceescalationServicegroupMembership'], '{n}[excluded=1].Servicegroup.uuid');


                $includedHosts = array_unique(Hash::extract($includedServices, '{n}.Host.uuid'));
                $includedServices = Hash::extract($includedServices, '{n}.uuid');

                $exludedHosts = array_unique(Hash::extract($exludedServices, '{n}.Host.uuid'));
                $exludedServices = Hash::extract($exludedServices, '{n}.uuid');


                // Prefix exluded services with an !
                $_exludedServices = [];
                foreach ($exludedServices as $extService) {
                    $_exludedServices[] = '!' . $extService;
                }

                $_exludedHosts = [];
                foreach ($exludedHosts as $extHost) {
                    $_exludedHosts[] = '!' . $extHost;
                }

                $services = Hash::merge($includedServices, $_exludedServices);
                $hosts = Hash::merge($includedHosts, $_exludedHosts);

                // Prefix excluded servicegroups with an !
                $_exludedServicegroups = [];
                foreach ($exludedServicegroups as $extServicegroup) {
                    $_exludedServicegroups[] = '!' . $extServicegroup;
                }
                $servicegroups = Hash::merge($includedServicegroups, $_exludedServicegroups);

                if ((!empty($includedServices && !empty($hosts))) || (!empty($includedServicegroups) && !empty($hosts))) {
                    $file = new File($this->conf['path'] . $this->conf['serviceescalations'] . $serviceescalation['Serviceescalation']['uuid'] . $this->conf['suffix']);
                    $content = $this->fileHeader();
                    if (!$file->exists()) {
                        $file->create();
                    }


                    $content .= $this->addContent('define serviceescalation{', 0);
                    $content .= $this->addContent('host_name', 1, implode(',', $hosts));

                    if (!empty($services)) {
                        $content .= $this->addContent('service_description', 1, implode(',', $services));
                    }

                    if (!empty($servicegroups)) {
                        $content .= $this->addContent('servicegroup_name', 1, implode(',', $servicegroups));
                    }

                    if (!empty($serviceescalation['Contact'])) {
                        $content .= $this->addContent('contacts', 1, implode(',', Hash::extract($serviceescalation['Contact'], '{n}.uuid')));
                    }
                    if (!empty($serviceescalation['Contactgroup'])) {
                        $content .= $this->addContent('contact_groups', 1, implode(',', Hash::extract($serviceescalation['Contactgroup'], '{n}.uuid')));
                    }
                    $content .= $this->addContent('first_notification', 1, $serviceescalation['Serviceescalation']['first_notification']);
                    $content .= $this->addContent('last_notification', 1, $serviceescalation['Serviceescalation']['last_notification']);
                    $content .= $this->addContent('notification_interval', 1, (int)$serviceescalation['Serviceescalation']['notification_interval'] * 60);
                    $content .= $this->addContent('escalation_period', 1, $serviceescalation['Timeperiod']['uuid']);
                    $serviceEscalationString = $this->serviceEscalationString($serviceescalation['Serviceescalation']);
                    if (!empty($serviceEscalationString)) {
                        $content .= $this->addContent('escalation_options', 1, $serviceEscalationString);
                    }

                    $content .= $this->addContent('}', 0);
                    $file->write($content);
                    $file->close();
                } else {
                    //This service escalation is broken!
                    $this->Serviceescalation->delete($serviceescalation['Serviceescalation']['id']);
                }
            } else {
                //This service escalation is broken!
                $this->Serviceescalation->delete($serviceescalation['Serviceescalation']['id']);
            }
        }
    }

    /**
     * @param null $uuid
     * @throws Exception
     */
    public function exportTimeperiods($uuid = null) {
        /** @var $TimeperiodsTable TimeperiodsTable */
        $TimeperiodsTable = TableRegistry::getTableLocator()->get('Timeperiods');
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

        $date = new DateTime();
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
                $calendar = $this->Calendar->find('first', [
                    'recursive'  => -1,
                    'conditions' => [
                        'Calendar.id' => $timeperiod['Timeperiod']['calendar_id']
                    ],
                    'contain'    => ['CalendarHoliday']
                ]);
                foreach ($calendar['CalendarHoliday'] as $holiday) {
                    $timestamp = strtotime(sprintf('%s 00:00', $holiday['date']));

                    $calendarDay = sprintf('%s 00:00-24:00; %s',
                        strtolower(date('F j', $timestamp)),
                        $holiday['name']
                    );
                    $content .= $this->addContent($calendarDay, 1);
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
     * @param $satelite
     */
    public function exportSatTimeperiods($timeperiods, $satelite) {
        if (!is_dir($this->conf['satellite_path'] . $satelite['Satellite']['id'] . DS . $this->conf['timeperiods'])) {
            mkdir($this->conf['satellite_path'] . $satelite['Satellite']['id'] . DS . $this->conf['timeperiods']);
        }

        if ($this->conf['minified']) {
            $file = new File($this->conf['satellite_path'] . $satelite['Satellite']['id'] . DS . $this->conf['timeperiods'] . 'timeperiods_minified' . $this->conf['suffix']);
            if (!$file->exists()) {
                $file->create();
            }
            $content = $this->fileHeader();
        }

        $date = new DateTime();
        $weekdays = [];
        for ($i = 1; $i <= 7; $i++) {
            $weekdays[$date->format('N')] = strtolower($date->format('l'));
            $date->modify('+1 day');
        }

        foreach ($timeperiods as $timeperiod) {
            if (!$this->conf['minified']) {
                $file = new File($this->conf['satellite_path'] . $satelite['Satellite']['id'] . DS . $this->conf['timeperiods'] . $timeperiod['Timeperiod']['uuid'] . $this->conf['suffix']);
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
                if (empty($satelite['Satellite']['timezone']) || ($timeRange['start'] == '00:00' && $timeRange['end'] == '24:00')) {
                    $timeRanges[$weekdays[$timeRange['day']]][] = $timeRange['start'] . '-' . $timeRange['end'];
                } else {
                    $remoteTimeZone = new DateTimeZone($satelite['Satellite']['timezone']);
                    $start = new DateTime($weekdays[$timeRange['day']] . ' ' . $timeRange['start']);
                    $start = $start->setTimezone($remoteTimeZone);
                    $end = new DateTime($weekdays[$timeRange['day']] . ' ' . (($timeRange['end'] == '24:00') ? '23:59' : $timeRange['end']));
                    $end = $end->setTimezone($remoteTimeZone);
                    if ($timeRange['end'] == '24:00') {
                        $end = $end->add(new DateInterval('PT1M'));
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
                $calendar = $this->Calendar->find('first', [
                    'recursive'  => -1,
                    'conditions' => [
                        'Calendar.id' => $timeperiod['Timeperiod']['calendar_id']
                    ],
                    'contain'    => ['CalendarHoliday']
                ]);
                foreach ($calendar['CalendarHoliday'] as $holiday) {
                    $timestamp = strtotime(sprintf('%s 00:00', $holiday['date']));

                    $calendarDay = sprintf('%s 00:00-24:00; %s',
                        strtolower(date('F j', $timestamp)),
                        $holiday['name']
                    );
                    $content .= $this->addContent($calendarDay, 1);
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
        /** @var $HostdependenciesTable HostdependenciesTable */
        $HostdependenciesTable = TableRegistry::getTableLocator()->get('Hostdependencies');
        $hostdependencies = $HostdependenciesTable->getHostdependenciesForExport($uuid);

        if (!is_dir($this->conf['path'] . $this->conf['hostdependencies'])) {
            mkdir($this->conf['path'] . $this->conf['hostdependencies']);
        }

        foreach ($hostdependencies as $hostdependency) {
            $file = new File($this->conf['path'] . $this->conf['hostdependencies'] . $hostdependency->get('uuid') . $this->conf['suffix']);
            $content = $this->fileHeader();
            if (!$file->exists()) {
                $file->create();
            }

            $hostsForCfg = [];
            $dependentHostsForCfg = [];
            $hosts = $hostdependency->get('hosts');
            //Check if the host dependency is valid
            if(is_null($hosts)){
                //This host dependency is broken, ther are no hosts in it!
                $HostdependenciesTable->delete($hostdependency);
                $file->close();
                if ($file->exists()) {
                    $file->delete();
                }
                continue;
            }
            foreach ($hosts as $host) {
                if ($host->get('_joinData')->get('dependent') === 0) {
                    $hostsForCfg[] = $host->get('uuid');
                } else {
                    $dependentHostsForCfg[] = $host->get('uuid');
                }
            }
            if (!empty($hostsForCfg) && !empty($dependentHostsForCfg)) {
                $content .= $this->addContent('define hostdependency{', 0);

                $content .= $this->addContent('host_name', 1, implode(',', $hostsForCfg));
                $content .= $this->addContent('dependent_host_name', 1, implode(',', $dependentHostsForCfg));
            }
            $hostgroupsForCfg = [];
            $dependentHostgroupsForCfg = [];
            if (!is_null($hostdependency->get('hostgroups'))) {
                $hostgroups = $hostdependency->get('hostgroups');
                foreach ($hostgroups as $hostgroup) {
                    if ($hostgroup->get('_joinData')->get('dependent') === 0) {
                        $hostgroupsForCfg[] = $hostgroup->get('uuid');
                    } else {
                        $dependentHostgroupsForCfg[] = $hostgroup->get('uuid');
                    }
                }
            }
            if (!empty($hostgroupsForCfg)) {
                $content .= $this->addContent('hostgroup_name', 1, implode(',', $hostgroupsForCfg));
            }
            if (!empty($dependentHostgroupsForCfg)) {
                $content .= $this->addContent('dependent_hostgroup_name', 1, implode(',', $dependentHostgroupsForCfg));
            }

            $content .= $this->addContent('inherits_parent', 1, $hostdependency->get('inherits_parent'));

            $executionFailureCriteriaForCfgString = $hostdependency->getExecutionFailureCriteriaForCfg();
            if(!empty($executionFailureCriteriaForCfgString)){
                $content .= $this->addContent('execution_failure_criteria', 1, $executionFailureCriteriaForCfgString);
            }
            $notificationFailureCriteriaForCfgString = $hostdependency->getNotificationFailureCriteriaForCfg();
            if(!empty($notificationFailureCriteriaForCfgString)){
                $content .= $this->addContent('notification_failure_criteria', 1, $notificationFailureCriteriaForCfgString);
            }
            $dependencyTimeperiod = $hostdependency->get('timeperiod');
            if(!is_null($dependencyTimeperiod)){
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
        $query = [
            'recursive' => -1,
            'contain'   => [
                'ServicedependencyServiceMembership'      => [
                    'Service' => [
                        'conditions' => [
                            'Service.disabled' => 0
                        ],
                        'fields'     => [
                            'uuid',
                        ],
                        'Host'       => [
                            'fields'     => [
                                'uuid',
                            ],
                            'conditions' => [
                                'Host.disabled' => 0
                            ],
                        ],
                    ],
                ],
                'ServicedependencyServicegroupMembership' => [
                    'Servicegroup' => [
                        'fields' => [
                            'Servicegroup.uuid'
                        ]
                    ],
                ],
                'Timeperiod'                              => [
                    'fields' => [
                        'uuid',
                    ],
                ],
            ],
        ];

        if ($uuid !== null) {
            $servicedependencies = [];
            $query['conditions'] = [
                'Servicedependency.uuid' => $uuid
            ];
            $servicedependencies[] = $this->Servicedependency->find('first', $query);
        } else {
            $servicedependencies = $this->Servicedependency->find('all', $query);
        }

        if (!is_dir($this->conf['path'] . $this->conf['servicedependencies'])) {
            mkdir($this->conf['path'] . $this->conf['servicedependencies']);
        }
        foreach ($servicedependencies as $servicedependency) {
            $file = new File($this->conf['path'] . $this->conf['servicedependencies'] . $servicedependency['Servicedependency']['uuid'] . $this->conf['suffix']);
            $content = $this->fileHeader();
            if (!$file->exists()) {
                $file->create();
            }

            $masterServices = array_filter(Hash::extract($servicedependency['ServicedependencyServiceMembership'], '{n}[dependent=0].Service'));
            $dependentServices = array_filter(Hash::extract($servicedependency['ServicedependencyServiceMembership'], '{n}[dependent=1].Service'));

            $masterServicegroups = Hash::extract($servicedependency['ServicedependencyServicegroupMembership'], '{n}[dependent=0].Servicegroup.uuid');
            $dependentServicegroups = Hash::extract($servicedependency['ServicedependencyServicegroupMembership'], '{n}[dependent=1].Servicegroup.uuid');


            if (!empty($masterServicegroups)) {
                $masterServicegroups = implode(',', Hash::extract($masterServicegroups, '{n}.Servicegroup.uuid'));
            }
            if (!empty($dependentServicegroups)) {
                $dependentServicegroups = implode(',', Hash::extract($dependentServicegroups, '{n}.Servicegroup.uuid'));
            }

            $hasMasterServicesAndDependentService = (!empty($masterServices) && !empty($dependentServices));
            if ($hasMasterServicesAndDependentService === true) {
                foreach ($masterServices as $masterService) {
                    foreach ($dependentServices as $dependentService) {
                        $content .= $this->addContent('define servicedependency{', 0);
                        $content .= $this->addContent('host_name', 1, $masterService['Host']['uuid']);
                        $content .= $this->addContent('service_description', 1, $masterService['uuid']);
                        $content .= $this->addContent('dependent_host_name', 1, $dependentService['Host']['uuid']);
                        $content .= $this->addContent('dependent_service_description', 1, $dependentService['uuid']);

                        if (!empty($masterServicegroups)) {
                            $content .= $this->addContent('servicegroup_name', 1, $masterServicegroups);
                        }

                        if (!empty($dependentServicegroups)) {
                            $content .= $this->addContent('dependent_servicegroup_name', 1, $dependentServicegroups);
                        }

                        $content .= $this->addContent('inherits_parent', 1, $servicedependency['Servicedependency']['inherits_parent']);
                        $serviceDependencyExecutionString = $this->serviceDependencyExecutionString($servicedependency['Servicedependency']);
                        if (!empty($serviceDependencyExecutionString)) {
                            $content .= $this->addContent('execution_failure_criteria', 1, $serviceDependencyExecutionString);
                        }
                        $serviceDependencyNotificationString = $this->serviceDependencyNotificationString($servicedependency['Servicedependency']);
                        if (!empty($serviceDependencyNotificationString)) {
                            $content .= $this->addContent('notification_failure_criteria', 1, $serviceDependencyNotificationString);
                        }
                        if ($servicedependency['Timeperiod']['uuid'] !== null && $servicedependency['Timeperiod']['uuid'] !== '') {
                            $content .= $this->addContent('dependency_period', 1, $servicedependency['Timeperiod']['uuid']);
                        }

                        $content .= $this->addContent('}', 0);
                        $content .= $this->nl();
                    }
                }
            } else {
                //This service dependency is broken, ther are no services in it
                $this->Servicedependency->delete($servicedependency['Servicedependency']['id']);
                $file->close();
                if ($file->exists()) {
                    $file->delete();
                }
                continue;
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
     */
    protected function __loadExternTasks() {
        $this->externalTasks = [];
        $modulePlugins = array_filter(CakePlugin::loaded(), function ($value) {
            return strpos($value, 'Module') !== false;
        });
        foreach ($modulePlugins as $pluginName) {
            if (file_exists(OLD_APP . 'Plugin/' . $pluginName . '/Console/Command/Task/' . $pluginName . 'NagiosExportTask.php')) {
                $this->externalTasks[$pluginName] = $pluginName . 'NagiosExport';
            }
        }
    }

    public function exportExternalTasks() {
        foreach ($this->externalTasks as $pluginName => $taskName) {
            $_task = new TaskCollection($this);
            $extTask = $_task->load($pluginName . '.' . $taskName);
            $extTask->export();
        }
    }

    public function beforeExportExternalTasks() {
        foreach ($this->externalTasks as $pluginName => $taskName) {
            $_task = new TaskCollection($this);
            $extTask = $_task->load($pluginName . '.' . $taskName);
            $extTask->beforeExport();
        }
    }

    public function afterExportExternalTasks() {
        //Restart oitc CMD to wipe old cached information
        exec('service oitc_cmd restart');

        foreach ($this->externalTasks as $pluginName => $taskName) {
            $_task = new TaskCollection($this);
            $extTask = $_task->load($pluginName . '.' . $taskName);
            $extTask->afterExport();
        }
    }

    public function exportSatHostgroups() {
        /** @var $HostgroupsTable HostgroupsTable */
        $HostgroupsTable = TableRegistry::getTableLocator()->get('Hostgroups');
        $hostgroups = $HostgroupsTable->getHostgroupsForExport();

        foreach ($this->Satellites as $satellite) {
            $satelliteId = $satellite['Satellite']['id'];

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
                /** @var $hostgroup \App\Model\Entity\Hostgroup */
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
     * @param array $serviceOrServicetemplate
     *
     * @return string
     * @deprecated Move to Entity
     */
    public function serviceNotificationString($serviceOrServicetemplate = []) {
        $fields = ['notify_on_warning' => 'w', 'notify_on_unknown' => 'u', 'notify_on_critical' => 'c', 'notify_on_recovery' => 'r', 'notify_on_flapping' => 'f', 'notify_on_downtime' => 's'];

        return $this->_implode($serviceOrServicetemplate, $fields);
    }

    /**
     * @param array $hostOrHosttemplate
     *
     * @return string
     * @deprecated Move to Entity
     */
    public function hostFlapdetectionString($hostOrHosttemplate = []) {
        $fields = ['flap_detection_on_up' => 'o', 'flap_detection_on_down' => 'd', 'flap_detection_on_unreachable' => 'u'];

        return $this->_implode($hostOrHosttemplate, $fields);
    }

    /**
     * @param array $serviceOrServicetemplate
     *
     * @return string
     * @deprecated Move to Entity
     */
    public function serviceFlapdetectionString($serviceOrServicetemplate = []) {
        $fields = ['flap_detection_on_ok' => 'o', 'flap_detection_on_warning' => 'w', 'flap_detection_on_unknown' => 'u', 'flap_detection_on_critical' => 'c'];

        return $this->_implode($serviceOrServicetemplate, $fields);
    }

    /**
     * @param array $contact
     *
     * @return string
     * @deprecated Move to Entity
     */
    public function contactHostNotificationOptions($contact = []) {
        $fields = ['notify_host_recovery' => 'r', 'notify_host_down' => 'd', 'notify_host_unreachable' => 'u', 'notify_host_flapping' => 'f', 'notify_host_downtime' => 's'];

        return $this->_implode($contact, $fields);
    }

    /**
     * @param array $contact
     *
     * @return string
     * @deprecated Move to Entity
     */
    public function contactServiceNotificationOptions($contact = []) {
        $fields = ['notify_service_downtime' => 's', 'notify_service_flapping' => 'f', 'notify_service_critical' => 'c', 'notify_service_unknown' => 'u', 'notify_service_warning' => 'w', 'notify_service_recovery' => 'r'];

        return $this->_implode($contact, $fields);
    }

    /**
     * @param array $hostescalation
     *
     * @return string
     * @deprecated Move to Entity
     */
    public function hostEscalationString($hostescalation = []) {
        $fields = ['escalate_on_recovery' => 'r', 'escalate_on_down' => 'd', 'escalate_on_unreachable' => 'u'];

        return $this->_implode($hostescalation, $fields);
    }

    /**
     * @param array $hostescalation
     *
     * @return string
     * @deprecated Move to Entity
     */
    public function serviceEscalationString($hostescalation = []) {
        $fields = ['escalate_on_recovery' => 'r', 'escalate_on_warning' => 'w', 'escalate_on_unknown' => 'u', 'escalate_on_critical' => 'c'];

        return $this->_implode($hostescalation, $fields);
    }

    /**
     * @param array $hostdependency
     *
     * @return string
     * @deprecated Move to Entity
     */
    public function hostDependencyExecutionString($hostdependency = []) {
        $fields = ['execution_fail_on_up' => 'o', 'execution_fail_on_down' => 'd', 'execution_fail_on_unreachable' => 'u', 'execution_fail_on_pending' => 'p', 'execution_none' => 'n'];

        return $this->_implode($hostdependency, $fields);
    }

    /**
     * @param array $hostdependency
     *
     * @return string
     * @deprecated Move to Entity
     */
    public function hostDependencyNotificationString($hostdependency = []) {
        $fields = ['notification_fail_on_up' => 'o', 'notification_fail_on_down' => 'd', 'notification_fail_on_unreachable' => 'u', 'notification_fail_on_pending' => 'p', 'notification_none' => 'n'];

        return $this->_implode($hostdependency, $fields);
    }

    /**
     * @param array $servicedependeny
     *
     * @return string
     * @deprecated Move to Entity
     */
    public function serviceDependencyExecutionString($servicedependeny = []) {
        $fields = ['execution_fail_on_ok' => 'o', 'execution_fail_on_warning' => 'w', 'execution_fail_on_unknown' => 'u', 'execution_fail_on_critical' => 'c', 'execution_fail_on_pending' => 'p', 'execution_none' => 'n'];

        return $this->_implode($servicedependeny, $fields);
    }

    /**
     * @param array $servicedependeny
     *
     * @return string
     * @deprecated Move to Entity
     */
    public function serviceDependencyNotificationString($servicedependeny = []) {
        $fields = ['notification_fail_on_ok' => 'o', 'notification_fail_on_warning' => 'w', 'notification_fail_on_unknown' => 'u', 'notification_fail_on_critical' => 'c', 'notification_fail_on_pending' => 'p', 'notification_none' => 'n'];

        return $this->_implode($servicedependeny, $fields);
    }

    /**
     * @param $object
     * @param $fields
     *
     * @return string
     * @deprecated
     */
    private function _implode($object, $fields) {
        $nagios = [];
        foreach ($fields as $field => $nagios_value) {
            if (isset($object[$field]) && $object[$field] == 1) {
                $nagios[] = $nagios_value;
            }
        }

        return implode(',', $nagios);
    }

    public function verify() {
        $this->out("<info>" . __d('oitc_console', 'verifying configuration files, please standby...') . "</info>");
        exec('sudo -u ' . $this->_systemsettings['MONITORING']['MONITORING.USER'] . ' ' . Configure::read('nagios.basepath') . Configure::read('nagios.bin') . Configure::read('nagios.nagios_bin') . ' ' . Configure::read('nagios.verify') . ' ' . Configure::read('nagios.nagios_cfg'), $out);
        foreach ($out as $line) {
            echo $line . PHP_EOL;
        }
    }

    /**
     * @return string
     */
    public function returnVerifyCommand() {
        return 'sudo -u ' . $this->_systemsettings['MONITORING']['MONITORING.USER'] . ' ' . Configure::read('nagios.basepath') . Configure::read('nagios.bin') . Configure::read('nagios.nagios_bin') . ' ' . Configure::read('nagios.verify') . ' ' . Configure::read('nagios.nagios_cfg');
    }

    /**
     * @return mixed
     */
    public function returnReloadCommand() {
        return $this->_systemsettings['MONITORING']['MONITORING.RELOAD'];
    }

    /**
     * @return mixed
     */
    public function returnAfterExportCommand() {
        return $this->_systemsettings['MONITORING']['MONITORING.AFTER_EXPORT'];
    }

    /**
     * @deprecated
     */
    public function restart() {
    }

    public function deleteHostPerfdata() {
        App::uses('Folder', 'Utility');
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
;http://nagios.sourceforge.net/docs/nagioscore/4/en/objectdefinitions.html
;http://nagios.sourceforge.net/docs/nagioscore/4/en/objecttricks.html
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
#Weblink:
# http://nagios.sourceforge.net/docs/nagioscore/4/en/configmain.html#resource_file
# http://nagios.sourceforge.net/docs/nagioscore/4/en/macrolist.html#user
";
        if (is_resource($file)) {
            fwrite($file, $header);
        } else {
            return $header;
        }
    }

    /**
     * @param             $string
     * @param int $deep
     * @param null|string $value
     * @param bool $newline
     *
     * @return string
     */
    public function addContent($string, $deep = 1, $value = null, $newline = true) {
        $c = "";
        $i = 0;
        while ($i < $deep) {
            $c .= '    ';
            $i++;
        }
        $c .= $string;

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

    /**
     * @param $pathForBackup
     *
     * @return array
     */
    public function makeSQLBackup($pathForBackup) {
        $connection = ConnectionManager::sourceList();
        $connectionList = ConnectionManager::enumConnectionObjects();
        $usedConnectionDetails = $connectionList[$connection[0]];
        $dbc_dbname = $usedConnectionDetails["database"];
        $output = [];
        $returncode = 0;
        exec("mysqldump --defaults-extra-file=/etc/mysql/debian.cnf --databases $dbc_dbname --flush-privileges --single-transaction --triggers --routines --events --hex-blob --ignore-table=$dbc_dbname.nagios_acknowledgements --ignore-table=$dbc_dbname.nagios_commands --ignore-table=$dbc_dbname.nagios_commenthistory --ignore-table=$dbc_dbname.nagios_configfiles --ignore-table=$dbc_dbname.nagios_configfilevariables --ignore-table=$dbc_dbname.nagios_conninfo --ignore-table=$dbc_dbname.nagios_contact_addresses --ignore-table=$dbc_dbname.nagios_contact_notificationcommands --ignore-table=$dbc_dbname.nagios_contactgroup_members --ignore-table=$dbc_dbname.nagios_contactgroups --ignore-table=$dbc_dbname.nagios_contactnotificationmethods --ignore-table=$dbc_dbname.nagios_contactnotifications --ignore-table=$dbc_dbname.nagios_contacts --ignore-table=$dbc_dbname.nagios_contactstatus --ignore-table=$dbc_dbname.nagios_customvariables --ignore-table=$dbc_dbname.nagios_customvariablestatus --ignore-table=$dbc_dbname.nagios_dbversion --ignore-table=$dbc_dbname.nagios_downtimehistory --ignore-table=$dbc_dbname.nagios_eventhandlers --ignore-table=$dbc_dbname.nagios_externalcommands --ignore-table=$dbc_dbname.nagios_flappinghistory --ignore-table=$dbc_dbname.nagios_host_contactgroups --ignore-table=$dbc_dbname.nagios_host_contacts --ignore-table=$dbc_dbname.nagios_host_parenthosts --ignore-table=$dbc_dbname.nagios_hostchecks --ignore-table=$dbc_dbname.nagios_hostdependencies --ignore-table=$dbc_dbname.nagios_hostescalation_contactgroups --ignore-table=$dbc_dbname.nagios_hostescalation_contacts --ignore-table=$dbc_dbname.nagios_hostescalations --ignore-table=$dbc_dbname.nagios_hostgroup_members --ignore-table=$dbc_dbname.nagios_hostgroups --ignore-table=$dbc_dbname.nagios_hosts --ignore-table=$dbc_dbname.nagios_hoststatus --ignore-table=$dbc_dbname.nagios_instances --ignore-table=$dbc_dbname.nagios_logentries --ignore-table=$dbc_dbname.nagios_notifications --ignore-table=$dbc_dbname.nagios_processevents --ignore-table=$dbc_dbname.nagios_programstatus --ignore-table=$dbc_dbname.nagios_runtimevariables --ignore-table=$dbc_dbname.nagios_scheduleddowntime --ignore-table=$dbc_dbname.nagios_service_contactgroups --ignore-table=$dbc_dbname.nagios_service_contacts --ignore-table=$dbc_dbname.nagios_service_parentservices --ignore-table=$dbc_dbname.nagios_servicechecks --ignore-table=$dbc_dbname.nagios_servicedependencies --ignore-table=$dbc_dbname.nagios_serviceescalation_contactgroups --ignore-table=$dbc_dbname.nagios_serviceescalation_contacts --ignore-table=$dbc_dbname.nagios_serviceescalations --ignore-table=$dbc_dbname.nagios_servicegroup_members --ignore-table=$dbc_dbname.nagios_servicegroups --ignore-table=$dbc_dbname.nagios_services --ignore-table=$dbc_dbname.nagios_servicestatus --ignore-table=$dbc_dbname.nagios_statehistory --ignore-table=$dbc_dbname.nagios_systemcommands --ignore-table=$dbc_dbname.nagios_timedeventqueue --ignore-table=$dbc_dbname.nagios_timedevents --ignore-table=$dbc_dbname.nagios_timeperiod_timeranges --ignore-table=$dbc_dbname.nagios_timeperiods > " . $pathForBackup, $output, $returncode);
        $return = [
            'output'     => $output,
            'returncode' => $returncode,
        ];

        return $return;
    }

    /**
     * @param $dumpFile
     *
     * @return array
     */
    public function restoreSQLBackup($dumpFile) {
        $connection = ConnectionManager::sourceList();
        $connectionList = ConnectionManager::enumConnectionObjects();
        $usedConnectionDetails = $connectionList[$connection[0]];
        $dbc_dbname = $usedConnectionDetails["database"];
        $host = $usedConnectionDetails["host"];
        $pwd = $usedConnectionDetails["password"];
        $user = $usedConnectionDetails["login"];
        $output = [];
        $returncode = 0;
        exec("mysql --host=$host --user=$user --password=$pwd -v $dbc_dbname < $dumpFile", $output, $returncode);
        exec("mysql --host=$host --user=$user --password=$pwd -e 'truncate openitcockpit.exports'");
        $return = [
            'output'     => $output,
            'returncode' => $returncode,
        ];

        return $return;
    }

    public function escapeLastBackslash($str = '') {
        if (mb_substr($str, -1) === '\\') {
            $str = sprintf('%s\\', $str); //Add a \ to the end of the string - because last char is a \
        }
        return $str;
    }
}
