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

namespace itnovum\openITCOCKPIT\Maps;

use itnovum\openITCOCKPIT\Maps\ValueObjects\Map;
use itnovum\openITCOCKPIT\Maps\ValueObjects\Mapgadget;
use itnovum\openITCOCKPIT\Maps\ValueObjects\Mapicon;
use itnovum\openITCOCKPIT\Maps\ValueObjects\Mapitem;
use itnovum\openITCOCKPIT\Maps\ValueObjects\Mapline;
use itnovum\openITCOCKPIT\Maps\ValueObjects\Mapsummaryitem;
use itnovum\openITCOCKPIT\Maps\ValueObjects\Maptext;

class MapForAngular {

    /**
     * @var array
     */
    private $map;

    /**
     * @var array
     */
    private $layers = [];

    /**
     * @var int|null
     */
    private $max_z_index = null;


    /**
     * MapForAngular constructor.
     * @param array $map
     */
    public function __construct($map) {
        $this->map = $map;
        $this->mapAsArray = $this->convertToArray();
    }

    /**
     * @return array
     */
    private function convertToArray() {
        $tmpMap = new Map($this->map);

        $map = [
            'Map' => $tmpMap->toArray()
        ];
        unset($tmpMap);

        if (isset($this->map['Mapitem'])) {
            foreach ($this->map['Mapitem'] as $mapitem) {
                $item = new Mapitem($mapitem);
                $this->layers[$item->getZIndex()] = sprintf('Layer %s', $item->getZIndex());
                $map['Mapitem'][] = $item->toArray();
            }
        }

        if (isset($this->map['Mapline'])) {
            foreach ($this->map['Mapline'] as $mapline) {
                $line = new Mapline($mapline);
                $this->layers[$line->getZIndex()] = sprintf('Layer %s', $line->getZIndex());
                $map['Mapline'][] = $line->toArray();
            }
        }

        if (isset($this->map['Mapgadget'])) {
            foreach ($this->map['Mapgadget'] as $mapgadget) {
                $gadget = new Mapgadget($mapgadget);
                $this->layers[$gadget->getZIndex()] = sprintf('Layer %s', $gadget->getZIndex());
                $map['Mapgadget'][] = $gadget->toArray();
            }
        }

        if (isset($this->map['Mapicon'])) {
            foreach ($this->map['Mapicon'] as $mapicon) {
                $icon = new Mapicon($mapicon);
                $this->layers[$icon->getZIndex()] = sprintf('Layer %s', $icon->getZIndex());
                $map['Mapicon'][] = $icon->toArray();
            }
        }

        if (isset($this->map['Maptext'])) {
            foreach ($this->map['Maptext'] as $maptext) {
                $text = new Maptext($maptext);
                $this->layers[$text->getZIndex()] = sprintf('Layer %s', $text->getZIndex());
                $map['Maptext'][] = $text->toArray();
            }
        }

        if (isset($this->map['Mapsummaryitem'])) {
            foreach ($this->map['Mapsummaryitem'] as $mapsummaryitem) {
                $summaryitem = new Mapsummaryitem($mapsummaryitem);
                $this->layers[$summaryitem->getZIndex()] = sprintf('Layer %s', $summaryitem->getZIndex());
                $map['Mapsummaryitem'][] = $summaryitem->toArray();
            }
        }

        if (isset($this->map['Container'])) {
            $map['Container'] = $this->map['Container'];
        }

        if (empty($this->layers)) {
            $this->layers[0] = sprintf('Layer %s', 0);
        }

        $this->max_z_index = (int)max(array_keys($this->layers));

        return $map;
    }

    /**
     * @return array
     */
    public function toArray() {
        if (empty($this->mapAsArray)) {
            $this->mapAsArray = $this->convertToArray();
        }

        return $this->mapAsArray;
    }

    /**
     * @return array
     */
    public function getLayers() {
        if (empty($this->layers)) {
            $this->convertToArray();
        }
        return $this->layers;
    }

    /**
     * @return int
     */
    public function getMaxZIndex() {
        if ($this->max_z_index === null) {
            $this->convertToArray();
        }
        return $this->max_z_index;
    }

}
