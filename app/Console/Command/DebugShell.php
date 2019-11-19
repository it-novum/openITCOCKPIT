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

class DebugShell extends AppShell {

    public $tasks = ['DebugConfigNagios'];

    public function main() {
        $this->stdout->styles('red_bold', ['text' => 'red', 'bold' => true]);
        Configure::load('nagios');

        $this->conf = Configure::read('nagios.export');

        $this->parser = $this->getOptionParser();

        if (array_key_exists('stdin', $this->params)) {
            $this->DebugConfigNagios->setup($this->conf);
            $this->DebugConfigNagios->translateStdin();
            exit(0);
        }

        $this->out(__d('oitc_console', 'Interactive openITCOCKPIT debugging shell'));
        $this->hr();
        $this->out(__d('oitc_console', '[D]ebug Monitoring Configuration'));
        $this->out(__d('oitc_console', '[Q]uit'));


        if (array_key_exists('debug', $this->params)) {
            $this->monitoringMenu();
        }

        if (array_key_exists('tail', $this->params)) {
            $this->DebugConfigNagios->setup($this->conf);
            $this->DebugConfigNagios->parseMonitoringLogfile();
        }

        if (array_key_exists('tailf', $this->params)) {
            $this->DebugConfigNagios->setup($this->conf);
            $this->DebugConfigNagios->tailf();
        }

        $menuSelection = strtoupper($this->in(__d('oitc_console', 'What would you like to debug?'), ['D', 'Q']));
        switch ($menuSelection) {
            case 'D':
                $this->monitoringMenu();
                break;
            case 'Q':
                $this->_exit();
            default:
                $this->out(__d('oitc_console', 'You have made an invalid selection. Please choose by entering D or C.'));
        }

        $this->hr();
        $this->main();

    }

    public function getOptionParser() {
        $parser = parent::getOptionParser();
        $parser->addOptions([
            'tail'  => ['help' => __d('oitc_console', 'Tail and parse monitoring logfile')],
            'tailf' => ['short' => 't', 'help' => __d('oitc_console', 'Tailf and parse monitoring logfile')],
            'stdin' => ['short' => 's', 'help' => __d('oitc_console', 'Read and translate from stdin. Example cat file.cfg | oitc debug -s')],
            'debug' => ['short' => 'd', 'help' => __d('oitc_console', 'Debuging menu')],
        ]);

        return $parser;
    }

    private function monitoringMenu() {
        $this->out(__d('oitc_console', '[T]ail and parse monitoring log file'));
        $this->out(__d('oitc_console', '[TF] Tail -f and parse monitoring log file'));
        $this->out(__d('oitc_console', '[H] Debug host configuratgion files'));
        $this->out(__d('oitc_console', '[HT] Debug host template configuration files'));
        $this->out(__d('oitc_console', '[S] Debug service configuration files'));
        $this->out(__d('oitc_console', '[ST] Debug service template configuration files'));
        $this->out(__d('oitc_console', '[TP] Debug timeperiod configuration files'));
        $this->out(__d('oitc_console', '[CM] Debug command configuration files'));
        $this->out(__d('oitc_console', '[C] Debug contact configuration files'));
        $this->out(__d('oitc_console', '[CG] Debug contact group configuration files'));
        $this->out(__d('oitc_console', '[HE] Debug host escalation configuration files'));
        $this->out(__d('oitc_console', '[SE] Debug service escalation configuration files'));
        $this->out(__d('oitc_console', '[HD] Debug host dependency configuration files'));
        $this->out(__d('oitc_console', '[SD] Debug service dependency configuration files'));
        $this->out(__d('oitc_console', '[UUID] Search object by UUID'));
        $this->out(__d('oitc_console', '[B]ack'));

        $menuSelection = strtoupper($this->in(__d('oitc_console', 'What would you like to do?'), ['T', 'TF', 'H', 'HT', 'S', 'ST', 'TP', 'CM', 'C', 'CG', 'HE', 'SE', 'HD', 'SD', 'UUID', 'B']));
        $this->DebugConfigNagios->setup($this->conf);
        switch ($menuSelection) {
            case 'T':
                $this->DebugConfigNagios->parseMonitoringLogfile();
                break;
            case 'TF':
                $this->DebugConfigNagios->tailf();
                break;
            case 'H':
                $this->DebugConfigNagios->debug('Host', 'hosts');
                break;
            case 'HT':
                $this->DebugConfigNagios->debug('Hosttemplate', 'hosttemplates');
                break;
            case 'S':
                $this->DebugConfigNagios->debug('Service', 'services');
                break;
            case 'ST':
                $this->DebugConfigNagios->debug('Servicetemplate', 'servicetemplates');
                break;
            case 'TP':
                $this->DebugConfigNagios->debug('Timeperiod', 'timeperiods');
                break;
            case 'CM':
                $this->DebugConfigNagios->debug('Command', 'commands');
                break;
            case 'C':
                $this->DebugConfigNagios->debug('Contact', 'contacts');
                break;
            case 'CG':
                $this->DebugConfigNagios->debug('Contactgroup', 'contactgroups');
                break;
            case 'HE':
                $this->DebugConfigNagios->debug('Hostescalation', 'hostescalations');
                break;
            case 'SE':
                $this->DebugConfigNagios->debug('Serviceescalation', 'serviceescalations');
                break;
            case 'HD':
                $this->DebugConfigNagios->debug('Hostdependency', 'hostdependencies');
                break;
            case 'SD':
                $this->DebugConfigNagios->debug('Servicedependency', 'servicedependencies');
                break;
            case 'UUID':
                $this->DebugConfigNagios->debugByUuid();
                break;
            case 'B':
                return $this->main();
            default:
                $this->out(__d('oitc_console', 'You have made an invalid selection. Please choose by entering T or B.'));
        }

        $this->hr();
        $this->monitoringMenu();
    }


    private function _exit() {
        $this->out(__d('oitc_console', 'Hopefully i was helpful'));
        $this->out(__d('oitc_console', 'Thanks for using me, bye'));
        exit();
    }
}