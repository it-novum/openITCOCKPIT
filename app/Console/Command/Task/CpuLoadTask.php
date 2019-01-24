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

use itnovum\openITCOCKPIT\Core\Interfaces\CronjobInterface;

class CpuLoadTask extends AppShell implements CronjobInterface {

    function execute($quiet = false) {
        $this->params['quiet'] = $quiet;
        if (file_exists('/proc/loadavg')) {
            $this->stdout->styles('green', ['text' => 'green']);
            $this->out('Fetch current CPU load...', false);
            $load = file('/proc/loadavg');
            $records = [];
            if (file_exists(OLD_TMP . 'loadavg')) {
                $records = file(OLD_TMP . 'loadavg');
            }

            $newLoad = [];
            if (sizeof($records) > 15) {
                //Truncate file if more that 15 entries
                $records = array_reverse($records);
                for ($i = 0; $i < 15; $i++) {
                    $newLoad[] = $records[$i];
                }
                $newLoad = array_reverse($newLoad);
            } else {
                $newLoad = $records;
            }

            $newLoad[] = time() . ' ' . $load[0];

            unset($records);
            $file = fopen(OLD_TMP . 'loadavg', 'w+');
            foreach ($newLoad as $line) {
                fwrite($file, $line);
            }
            fclose($file);
            $this->out('<green>   Ok</green>');
            $this->hr();
        }
    }

}