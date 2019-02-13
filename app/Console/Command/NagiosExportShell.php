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

/**
 * Class NagiosExportShell
 * @property Systemsetting $Systemsetting
 * @property DefaultNagiosConfigTask $DefaultNagiosConfig
 * @property NagiosExportTask $NagiosExport
 */
class NagiosExportShell extends AppShell {
    /**
     * @var array
     */
    public $uses = ['Systemsetting'];

    /**
     * @var array
     */
    public $tasks = ['DefaultNagiosConfig', 'NagiosExport'];

    /**
     * NagiosExportShell constructor.
     */
    public function __construct() {
        parent::__construct();
        //Loading components
        App::uses('Component', 'Controller');
        App::uses('ConstantsComponent', 'Controller/Component');
        $this->Constants = new ConstantsComponent();
    }

    public function main() {
        Configure::load('nagios');
        $this->conf = Configure::read('nagios.export');

        /** @var $Systemsettings App\Model\Table\SystemsettingsTable */
        $Systemsettings = TableRegistry::getTableLocator()->get('Systemsettings');
        $this->_systemsettings = $Systemsettings->findAsArray();

        if (!is_dir($this->conf['path'] . $this->conf['config'])) {
            mkdir($this->conf['path'] . $this->conf['config']);
        }

        $this->parser = $this->getOptionParser();
        if (sizeof($this->params) > 4) {

            if (!is_dir($this->conf['path'])) {
                mkdir($this->conf['path']);
            }

            if (array_key_exists('all', $this->params)) {
                $this->NagiosExport->beforeExportExternalTasks();
                $this->NagiosExport->deleteAllConfigfiles();
                $this->DefaultNagiosConfig->execute();
                $this->NagiosExport->exportHosttemplates();
                $this->NagiosExport->exportHosts();
                $this->NagiosExport->exportCommands();
                $this->NagiosExport->exportContacts();
                $this->NagiosExport->exportContactgroups();
                $this->NagiosExport->exportTimeperiods();
                $this->NagiosExport->exportHostgroups();
                $this->NagiosExport->exportHostescalations();
                $this->NagiosExport->exportMacros();
                $this->NagiosExport->exportServicetemplates();
                $this->NagiosExport->exportServices();
                $this->NagiosExport->exportServiceescalations();
                $this->NagiosExport->exportServicegroups();
                $this->NagiosExport->exportHostdependencies();
                $this->NagiosExport->exportServicedependencies();
                $this->NagiosExport->afterExportExternalTasks();
            }

            $this->DefaultNagiosConfig->execute();

            if (array_key_exists('delete', $this->params)) {
                $this->NagiosExport->deleteAllConfigfiles();
            }

            if (array_key_exists('hosttemplates', $this->params)) {
                $this->NagiosExport->exportHosttemplates();
            }

            if (array_key_exists('hosts', $this->params)) {
                $this->NagiosExport->exportHosts();
            }

            if (array_key_exists('servicetemplates', $this->params)) {
                $this->NagiosExport->exportServicetemplates();
            }

            if (array_key_exists('services', $this->params)) {
                $this->NagiosExport->exportServices();
            }

            if (array_key_exists('commands', $this->params)) {
                $this->NagiosExport->exportCommands();
            }

            if (array_key_exists('contacts', $this->params)) {
                $this->NagiosExport->exportContacts();
            }

            if (array_key_exists('contactgroups', $this->params)) {
                $this->NagiosExport->exportContactgroups();
            }

            if (array_key_exists('timeperiods', $this->params)) {
                $this->NagiosExport->exportTimeperiods();
            }

            if (array_key_exists('hostgroups', $this->params)) {
                $this->NagiosExport->exportHostgroups();
            }

            if (array_key_exists('hostescalations', $this->params)) {
                $this->NagiosExport->exportHostescalations();
            }

            if (array_key_exists('macros', $this->params)) {
                $this->NagiosExport->exportMacros();
            }

            if (array_key_exists('serviceescalations', $this->params)) {
                $this->NagiosExport->exportServiceescalations();
            }

            if (array_key_exists('servicegroups', $this->params)) {
                $this->NagiosExport->exportServicegroups();
            }

            if (array_key_exists('hostdependencies', $this->params)) {
                $this->NagiosExport->exportHostdependencies();
            }

            if (array_key_exists('servicedependencies', $this->params)) {
                $this->NagiosExport->exportServicedependencies();
            }

            if (array_key_exists('external', $this->params)) {
                $this->NagiosExport->beforeExportExternalTasks();
                $this->NagiosExport->afterExportExternalTasks();
            }


            if (array_key_exists('verify', $this->params)) {
                $this->NagiosExport->verify();
            }


        } else {
            $this->out('<error>No parameters given</error>');
        }

    }

    /**
     * @return ConsoleOptionParser
     */
    public function getOptionParser() {
        $parser = parent::getOptionParser();
        $parser->addOptions([
            'recursive'           => ['short' => 'h', 'help' => 'Searching for files recursive', 'boolean' => true],
            'hosttemplates'       => ['help' => "Will export all host templates"],
            'servicetemplates'    => ['help' => "Will export all service templates"],
            'commands'            => ['help' => "Will export all commands"],
            'contacts'            => ['help' => "Will export all contacts"],
            'contactgroups'       => ['help' => "Will export all contactgroups"],
            'hosts'               => ['help' => "Will export all hosts"],
            'services'            => ['help' => "Will export all services"],
            'timeperiods'         => ['help' => "Will export all timeperiods"],
            'hostgroups'          => ['help' => "Will export all host groups"],
            'hostescalations'     => ['help' => "Will export all hostescalations"],
            'serviceescalations'  => ['help' => "Will export all serviceescalations"],
            'servicegroups'       => ['help' => "Will export all service groups"],
            'hostdependencies'    => ['help' => "Will export all hostdependencies"],
            'servicedependencies' => ['help' => "Will export all servicedependencies"],
            'external'            => ['help' => "Will execute all external export mothodes (from modules like Check_MK)"],
            'macros'              => ['help' => "Will export all macros to resource.cfg"],
            'all'                 => ['help' => "Will rewrite all configuration files"],
            'verify'              => ['help' => "Verify all configuration data. You also can run multiple commands example: --servicetemplates --verify"],
            'delete'              => ['help' => 'Will delete all configuration files'],
        ]);

        return $parser;
    }
}
