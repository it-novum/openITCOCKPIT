<?php
// Copyright (C) <2015>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//  This program is free software: you can redistribute it and/or modify
//  it under the terms of the GNU General Public License as published by
//  the Free Software Foundation, version 3 of the License.
//
//  This program is distributed in the hope that it will be useful,
//  but WITHOUT ANY WARRANTY; without even the implied warranty of
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//  GNU General Public License for more details.
//
//  You should have received a copy of the GNU General Public License
//  along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
// 2.
//  If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//  under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//  License agreement and license key will be shipped with the order
//  confirmation.

namespace App\itnovum\openITCOCKPIT\Database;

class SanitizeOrder {

    /**
     * Only allow to pass column_name or Table.column_name into the MySQL ORDER BY query
     *
     * @param string $sort
     * @return array|string|string[]|null
     */
    public static function filterOrderColumn(string $sort) {
        $sort = trim($sort);
        // Only match to column_name or Table.column_name (no numbers, no special characters or umlauts)
        if (preg_match('/^[a-zA-Z_]+(\.[a-zA-Z_]+)*$/', $sort) === 1) {
            // This should be unnecessary because the preg_match above should never match on invalid characters
            // However, better safe than sorry
            return preg_replace('/[^a-zA-Z_\.]/', '', $sort);
        }
        // Ref.: https://xkcd.com/327/
        throw new FishyQueryException('Bobby Tables?');
    }
}
