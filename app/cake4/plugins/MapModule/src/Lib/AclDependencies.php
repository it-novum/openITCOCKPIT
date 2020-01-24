<?php
/**
 * Statusengine Worker
 * Copyright (C) 2016-2020  Daniel Ziegler
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace MapModule\Lib;


use App\Lib\PluginAclDependencies;

class AclDependencies extends PluginAclDependencies {

    public function __construct() {
        parent::__construct();

        // Add actions that should always be allowed.
        $this
            ->allow('BackgroundUploads', 'upload')
            ->allow('BackgroundUploads', 'icon')
            ->allow('BackgroundUploads', 'deleteIcon')
            ->allow('BackgroundUploads', 'iconset');

        $this
            ->allow('Mapeditors', 'mapitem')
            ->allow('Mapeditors', 'mapitemMulti')
            ->allow('Mapeditors', 'getDependendMaps')
            ->allow('Mapeditors', 'mapline')
            ->allow('Mapeditors', 'mapicon')
            ->allow('Mapeditors', 'maptext')
            ->allow('Mapeditors', 'perfdatatext')
            ->allow('Mapeditors', 'mapsummaryitem')
            ->allow('Mapeditors', 'graph')
            ->allow('Mapeditors', 'tacho')
            ->allow('Mapeditors', 'cylinder')
            ->allow('Mapeditors', 'trafficlight')
            ->allow('Mapeditors', 'temperature')
            ->allow('Mapeditors', 'mapsummary')
            ->allow('Mapeditors', 'backgroundImages')
            ->allow('Mapeditors', 'getIconsets')
            ->allow('Mapeditors', 'loadMapsByString')
            ->allow('Mapeditors', 'getPerformanceDataMetrics')
            ->allow('Mapeditors', 'mapWidget')
            ->allow('Mapeditors', 'viewDirective')
            ->allow('Mapeditors', 'mapDetails')
            ->allow('Mapeditors', 'serviceOutput');

        $this
            ->allow('Maps', 'loadUsersForTenant')
            ->allow('Maps', 'loadContainers');

        $this
            ->allow('Rotations', 'loadMaps')
            ->allow('Rotations', 'loadContainers');

        ///////////////////////////////
        //    Add dependencies       //
        //////////////////////////////
        $this
            ->dependency('Mapeditors', 'edit', 'Mapeditors', 'saveItem')
            ->dependency('Mapeditors', 'edit', 'Mapeditors', 'deleteItem')
            ->dependency('Mapeditors', 'edit', 'Mapeditors', 'saveLine')
            ->dependency('Mapeditors', 'edit', 'Mapeditors', 'deleteLine')
            ->dependency('Mapeditors', 'edit', 'Mapeditors', 'saveGadget')
            ->dependency('Mapeditors', 'edit', 'Mapeditors', 'deleteGadget')
            ->dependency('Mapeditors', 'edit', 'Mapeditors', 'saveText')
            ->dependency('Mapeditors', 'edit', 'Mapeditors', 'deleteText')
            ->dependency('Mapeditors', 'edit', 'Mapeditors', 'saveIcon')
            ->dependency('Mapeditors', 'edit', 'Mapeditors', 'deleteIcon')
            ->dependency('Mapeditors', 'edit', 'Mapeditors', 'saveBackground')
            ->dependency('Mapeditors', 'edit', 'Mapeditors', 'getIcons')
            ->dependency('Mapeditors', 'edit', 'Mapeditors', 'saveSummaryitem')
            ->dependency('Mapeditors', 'edit', 'Mapeditors', 'deleteSummaryitem');
    }

}
