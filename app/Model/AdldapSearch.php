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

namespace Model;

use Adldap\Classes\AdldapSearch as AdldapSearchMain;

class AdldapSearch extends AdldapSearchMain{

    public function paginate($perPage = 50, $currentPage = 0, $isCritical = true){
        // Stores all LDAP entries in a page array
        $pages = [];
        $cookie = '';
        do {
            $this->connection->controlPagedResult($perPage, $isCritical, $cookie);
            $results = $this->connection->search($this->adldap->getBaseDn(), $this->adldap->getMyPersonFilter(), $this->getSelects());
            if ($results) {
                $this->connection->controlPagedResultResponse($results, $cookie);
                $pages[] = $results;
            }
        } while ($cookie !== null && !empty($cookie));
        if (count($pages) > 0) {
            return $this->processPaginatedResults($pages, $perPage, $currentPage);
        }
        return false;
    }

    private function processPaginatedResults($pages, $perPage = 50, $currentPage = 0){
        if (count($pages) > 0) {
            $objects = [];
            $entries = $this->connection->getEntries($pages[$currentPage]);

            if (is_array($entries) && array_key_exists('count', $entries)) {
                for ($i = 0; $i < $entries['count']; $i++) {
                    if(isset($entries[$i]['dn'])){
                        $entries[$i]['dn'] = [$entries[$i]['dn']];
                    }
                    $entry = $this->newLdapEntry($entries[$i], $this->connection);
                    $objects[] = $entry->getAttributes();
                }
            }
            return [count($pages), $objects];
        }

        return false;
    }
}