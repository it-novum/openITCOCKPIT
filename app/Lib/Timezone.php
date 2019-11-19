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
 * Class Timezone
 * @deprecated
 *
 * Only used by Graphgenerator anymore
 */
class Timezone {

    /**
     * Liefert die Zeitdifferenz in Sekunden zwischen Benutzerzeitzone und Systemzeitzone am angegebenen Datum zurueck
     *
     * @param  datetime Datum und Uhrzeit (im Format YYYY-MM-DD [HH:MM:SS]), an dem die Zeitdifferenz ermittelt werden
     *                        soll (optional, default = now)
     *
     * @return int      Zeitdifferenz in Sekunden (die auf Serverzeit addiert werden muss, um Clientzeit zu erhalten)
     * @deprecated
     */
    public static function getUserSystemOffset($userTimezone, $datetime = 'now') {
        if (strlen($userTimezone) < 2) {
            //Empty database or empty cookie workaround to avoid completely broken page!
            $userTimezone = "Europe/Berlin";
        }
        $d = new DateTime($userTimezone);

        return $d->getOffset();
    }
}
