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

namespace itnovum\openITCOCKPIT\Maps\ValueObjects;


class Map {

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $background;

    /**
     * @var int
     */
    private $refresh_interval;

    /**
     * Map constructor.
     * @param array $map
     */
    public function __construct($map) {
        if (isset($map['id'])) {
            $this->id = (int)$map['id'];
        }

        if (isset($map['name'])) {
            $this->name = $map['name'];
        }

        if (isset($map['title'])) {
            $this->title = $map['title'];
        }

        if (isset($map['background'])) {
            $this->background = $map['background'];
        }

        if (isset($map['refresh_interval'])) {
            $this->refresh_interval = (int)$map['refresh_interval'];
        }
    }

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getBackground() {
        return $this->background;
    }

    /**
     * @return int
     */
    public function getRefreshInterval() {
        return $this->refresh_interval;
    }

    /**
     * @return array
     */
    public function toArray() {
        $arr = get_object_vars($this);
        return $arr;
    }

}
