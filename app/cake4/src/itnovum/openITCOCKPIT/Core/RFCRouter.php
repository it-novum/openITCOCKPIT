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


namespace itnovum\openITCOCKPIT\Core;


use Cake\Routing\Route\Route;

class RFCRouter extends Route {

    /**
     * Generates a well-formed querystring from $q
     *
     * @param string|array $q Query string Either a string of already compiled query string arguments or
     *    an array of arguments to convert into a query string.
     * @param array $extra Extra querystring parameters.
     * @param bool $escape Whether or not to use escaped &
     * @return array
     */
    public static function queryString($q, $extra = [], $escape = false) {
        if (empty($q) && empty($extra)) {
            return null;
        }
        $join = '&';
        if ($escape === true) {
            $join = '&amp;';
        }
        $out = '';

        if (is_array($q)) {
            $q = array_merge($q, $extra);
        } else {
            $out = $q;
            $q = $extra;
        }
        $addition = http_build_query($q, null, $join, PHP_QUERY_RFC3986);

        if ($out && $addition && substr($out, strlen($join) * -1, strlen($join)) !== $join) {
            $out .= $join;
        }

        $out .= $addition;

        if (isset($out[0]) && $out[0] !== '?') {
            $out = '?' . $out;
        }
        return $out;
    }

}