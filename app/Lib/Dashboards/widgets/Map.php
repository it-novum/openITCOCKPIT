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

namespace Dashboard\Widget;

use CakePlugin;

class Map extends Widget
{
    public $isDefault = false;
    public $icon = 'fa-globe';
    public $element = 'map';
    public $width = 4;
    public $height = 16;

    public function __construct(\Controller $controller, $QueryCache)
    {
        parent::__construct($controller, $QueryCache);
        $this->typeId = 14;
        $this->title = __('Map');
    }

    public function setData($widgetData)
    {
        if (!CakePlugin::loaded('MapModule')) {
            throw new NotFoundException(__('MapModule not loaded'));
        }

        $mapsListForWidget = $this->Controller->Map->find('all', [
            'conditions' => [
                'MapsToContainers.container_id' => $this->Controller->MY_RIGHTS,
            ],
            'fields'     => [
                'Map.id',
                'Map.name',
                'MapsToContainers.container_id',
            ],
            'joins'      => [
                [
                    'table'      => 'maps_to_containers',
                    'alias'      => 'MapsToContainers',
                    'type'       => 'LEFT',
                    'conditions' => [
                        'MapsToContainers.map_id = Map.id',
                    ],
                ],
            ],
        ]);

        $this->Controller->viewVars['widgetMaps'][$widgetData['Widget']['id']] = [
            'Widget' => $widgetData,
        ];
        $this->Controller->set('mapsListForWidget', $mapsListForWidget);
    }

    public function refresh($widget)
    {
        $this->setData($widget);

        return [
            'element' => 'Dashboard'.DS.$this->element,
        ];
    }

}
