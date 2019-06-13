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

/**
 * Class PerfdataHelper
 * @deprecated
 */
class PerfdataHelper extends AppHelper {
    /**
     * Parst Perfdata-String und gibt Ergebnis als Array zurueck
     *
     * @param string $perfdata_string Performance string z.B. batCapacity=100%;75:;50:;0;100 batVoltage=54;;
     *
     * @return array $perfdata_array formatierstes Array als Performance-Infos
     * @deprecated
     */
    public function parsePerfData($perfdata_string) {
        $perfdata = [];
        $perf_data_structure = ['label', 'current_value', 'unit', 'warning', 'critical', 'min', 'max'];
        $i = 0;

        preg_match_all('/ ([^=]*=[^ ]*)/', ' ' . $perfdata_string, $matches);
        foreach ($matches[0] as $data_set) {
            foreach (explode(';', trim($data_set)) as $value) {
                if (preg_match('/=/', $value)) {
                    $s = preg_split('/=/', $value);
                    if (isset($s[0])) {
                        $perfdata[$i][] = $s[0];
                        //Einheit auslesen
                        $number = '';
                        $unit = '';
                        foreach (str_split($s[1]) as $char) {
                            if ($char == '.' || $char == ',' || ($char >= '0' && $char <= '9')) {
                                $number .= $char;
                            } else {
                                $unit .= $char;
                            }
                        }
                        $perfdata[$i][] = str_replace(',', '.', $number);
                        $perfdata[$i][] = $unit;
                        continue;
                    }
                }
                if (isset($s[0])) {
                    $perfdata[$i][] = $value;
                }
            }
            if (isset($s[0])) {
                $perfdata[$i] = array_combine($perf_data_structure, array_merge($perfdata[$i], ((sizeof($perf_data_structure) - sizeof($perfdata[$i])) > 0) ? array_fill(sizeof($perfdata[$i]), (sizeof($perf_data_structure) - sizeof($perfdata[$i])), '') : []));
                unset($s);
            }
            $i++;
        }

        return ($perfdata);
    }
}
