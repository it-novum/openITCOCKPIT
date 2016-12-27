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

class Graphgenerator extends Widget
{
    public $isDefault = false;
    public $icon = 'fa-area-chart';
    public $element = 'graphgenerator';
    public $width = 12;
    public $height = 29;

    public function __construct(\Controller $controller, $QueryCache)
    {
        parent::__construct($controller, $QueryCache);
        $this->typeId = 15;
        $this->title = __('Graphgenerator');
    }

    public function setData($widgetData)
    {

        $graphListForWidget = $this->Controller->GraphgenTmpl->find('all', [
            'fields' => [
                'GraphgenTmpl.id',
                'GraphgenTmpl.name',
            ],
        ]);

        $this->Controller->viewVars['widgetMaps'][$widgetData['Widget']['id']] = [
            'Widget' => $widgetData,
        ];
        $this->Controller->set('graphListForWidget', $graphListForWidget);
    }

    public function refresh($widget)
    {
        $this->setData($widget);

        return [
            'element' => 'Dashboard'.DS.$this->element,
        ];
    }

}
