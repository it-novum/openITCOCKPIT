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

class QueryLogShell extends AppShell {
    /**
     * @var string
     */
    private $logfile;

    /**
     * @var bool
     */
    private $prettyPrint = false;

    /**
     * @var bool
     */
    private $hidePermissionQueries = false;

    public function main() {
        App::uses('SqlFormatter', 'Lib');

        $this->logfile = LOGS . 'query.log';

        $this->parser = $this->getOptionParser();

        if (array_key_exists('pretty', $this->params)) {
            $this->prettyPrint = true;
        }

        if (array_key_exists('hide-acl', $this->params)) {
            $this->hidePermissionQueries = true;
        }

        $this->tailf();
    }

    public function tailf() {
        $options = [
            'cwd' => '/tmp',
            'env' => [
                'LANG'     => 'C',
                'LANGUAGE' => 'en_US.UTF-8',
                'LC_ALL'   => 'C',
                'PATH'     => '/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin'
            ],
        ];

        $descriptorspec = [
            0 => ["pipe", "r"],
            1 => ["pipe", "w"],
            2 => ["pipe", "r"],
        ];

        $process = proc_open('/usr/bin/tail -f -n 10 ' . $this->logfile, $descriptorspec, $pipes, $options['cwd'], $options['env']);
        while (true) {
            $status = proc_get_status($process);
            if ($status['running'] != 1) {
                fclose($pipes[0]);
                fclose($pipes[1]);
                fclose($pipes[2]);
                proc_close($process);
                break;
            }

            $string = fgets($pipes[1], 1024);

            if ($this->hidePermissionQueries) {
                $acoQuery = 'SELECT `Aco`.`id`, `Aco`.`parent_id`';
                $aroQuery = 'SELECT `Aro`.`id`, `Aro`.`parent_id`';
                $permissionQuery = 'SELECT `Permission`.`id`, `Permission`.`aro_id`';

                if (strstr($string, $acoQuery) || strstr($string, $aroQuery) || strstr($string, $permissionQuery)) {
                    continue;
                }
            }

            if ($this->prettyPrint) {
                $this->out(SqlFormatter::format($string), false);
            } else {
                $this->out(SqlFormatter::highlight($string), false);
            }
        }
    }

    public function getOptionParser() {
        $parser = parent::getOptionParser();
        $parser->addOptions([
            'pretty'   => ['short' => 'p', 'help' => __d('oitc_console', 'Pretty print sql queries')],
            'hide-acl' => ['short' => 'a', 'help' => __d('oitc_console', 'Hide ARO and ACO queries')],
            'type'     => ['short' => 't', 'help' => __d('oitc_console', 'Type of the notification host or service')],
            'hostname' => ['help' => __d('oitc_console', 'The uuid of the host')],
        ]);

        return $parser;
    }
}
