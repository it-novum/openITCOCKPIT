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

namespace itnovum\openITCOCKPIT\Database;

class Cake4Paginator {

    /**
     * Current (CakePHP2) Controller
     * @var \Controller
     */
    private $Controller;

    /**
     * Current page
     * @var int
     */
    private $page = 1;

    /**
     * Limit per page
     * @var int
     */
    private $limit = 25;

    /**
     * Number of total records in database
     * for current query
     * @var int
     */
    private $count = 0;

    /**
     * Total pages
     * @var int
     */
    private $pages = 1;

    /**
     * Number of current records
     * (Amount of records on current page)
     * @var int
     */
    private $current = 0;

    public function __construct(\Controller $Controller) {
        $this->Controller = $Controller;

        if (property_exists($this->Controller, 'Paginator')) {
            $this->limit = (int)$this->Controller->Paginator->settings['limit'];
            if ($this->limit === 0 || $this->limit < 0) {
                $this->limit = 25;
            }

            //Uncomment for development purposes
            //$this->limit = 1;

            if (isset($this->Controller->Paginator->settings['page'])) {
                $this->page = (int)$this->Controller->Paginator->settings['page'];
            }
        }
    }

    /**
     * @param int $page
     */
    public function setPage($page) {
        $this->page = $page;
    }

    /**
     * @param int $limit
     */
    public function setLimit($limit) {
        $this->limit = $limit;
    }

    /**
     * @param $current
     */
    public function setCurrent($current) {
        $this->current = $current;
    }

    /**
     * @return int
     */
    public function getPage() {
        return $this->page;
    }

    /**
     * @return int
     */
    public function getOffset() {
        if ($this->page === 1) {
            return 0;
        }
        return (int)$this->limit * ($this->page - 1);
    }

    /**
     * @return int
     */
    public function getLimit() {
        return $this->limit;
    }

    /**
     * @return int
     */
    public function getPages() {
        return $this->pages;
    }

    public function setCountResult($count) {
        $this->count = (int)$count;
        if ($this->count === 0) {
            $this->pages = 1;
        } else {
            $this->pages = ceil($this->count / $this->limit);
        }
    }

    /**
     * @return bool
     */
    private function hasPrevPage() {
        if ($this->page !== 1) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    private function hasNextPage() {
        /**
         * $this->page = current page
         * $this->current = number of current items
         * $this->count = number of total items in table
         * $this->limit = max records per page
         */

        if ($this->page * $this->limit < $this->count) {
            return true;
        }

        return false;
    }

    public function getPagination() {
        return [
            'page'       => $this->page,
            'current'    => $this->current,
            'count'      => $this->count,
            'prevPage'   => $this->hasPrevPage(),
            'nextPage'   => $this->hasNextPage(),
            'pageCount'  => $this->pages,
            //'order'      => [
            //    'Hoststatus.current_state' => "desc",
            //],
            'limit'      => $this->limit,
            'options'    => [],
            'paramType'  => "named",
            'queryScope' => null,
        ];
    }

    public function paginate() {
        $this->Controller->set('paging', $this->getPagination());
    }


}
