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
class Widget
{
    //Integer value of the dashboard type
    public $typeId = null;
    //Is this a default widget (restorDefault)
    public $isDefault = false;
    //Default color
    public $defaultColor = 'jarviswidget-color-blueDark';
    //Default icon of the widget
    public $icon = 'fa-question';
    //Element to render
    public $element = '404';
    //Default row
    public $row = 0;
    //Default col
    public $col = 0;
    //Default width
    public $width = 5;
    //Default height
    public $height = 11;
    //Is ther a initical configuration we want to save into the database
    public $hasInitialConfig = false;


    public $Controller = null;

    public function __construct(\Controller $controller, $QueryCache)
    {
        $this->Controller = $controller;
        $this->QueryCache = $QueryCache;
    }

    //This function will set all required variables for the view
    public function setData($widgetData)
    {

    }

    public function refresh($widget)
    {

    }

    public function getElement($widget)
    {
        return $this->element;
    }
}
