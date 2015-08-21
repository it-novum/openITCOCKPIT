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


App::uses('Base', 'Admin.Lib');
App::uses('Welcome', 'Admin.Lib/Widgets');
App::uses('Tacho', 'Admin.Lib/Widgets');
App::uses('TrafficLight', 'Admin.Lib/Widgets');
App::uses('PieChartServices', 'Admin.Lib/Widgets');
App::uses('DowntimeServices', 'Admin.Lib/Widgets');
App::uses('ParentOutages', 'Admin.Lib/Widgets');
App::uses('PieChartHosts', 'Admin.Lib/Widgets');
App::uses('DowntimeHosts', 'Admin.Lib/Widgets');
App::uses('ServicesStatusList', 'Admin.Lib/Widgets');
App::uses('HostsStatusList', 'Admin.Lib/Widgets');


class WidgetFactory{
	/**
	 * @param int               $id
	 * @param string            $title
	 * @param string            $color
	 * @param int               $typeId
	 * @param Model[]           $models
	 * @return null|DowntimeHosts|DowntimeServices|ParentOutages|PieChartHosts|PieChartServices|WidgetTacho|TrafficLight|Welcome
	 */
	public static function create($id, $title, $color, $typeId, $models){

		/** @var WidgetBase $widget */
		$widget = null;

		switch($typeId){
			case WidgetBase::WIDGET_DOWNTIMEHOSTS:
				$widget = new DowntimeHosts($id, $title, $color, $typeId, $models);
				break;

			case WidgetBase::WIDGET_PIECHARTHOSTS:
				$widget =  new PieChartHosts($id, $title, $color, $typeId, $models);
				break;

			case WidgetBase::WIDGET_PARENTOUTAGES:
				$widget =  new ParentOutages($id, $title, $color, $typeId, $models);
				break;

			case WidgetBase::WIDGET_DOWNTIMESERVICES:
				$widget =  new DowntimeServices($id, $title, $color, $typeId, $models);
				break;

			case WidgetBase::WIDGET_PIECHARTSERVICES:
				$widget =  new PieChartServices($id, $title, $color, $typeId, $models);
				break;

			case WidgetBase::WIDGET_TRAFFICLIGHT:
				$widget =  new TrafficLight($id, $title, $color, $typeId, $models);
				break;

			case WidgetBase::WIDGET_TACHO:
				$widget =  new Tacho($id, $title, $color, $typeId, $models);
				break;

			case WidgetBase::WIDGET_WELCOME:
				$widget =  new Welcome($id, $title, $color, $typeId, $models);
				break;

			case WidgetBase::WIDGET_SERVICESSTATUSLIST:
				$widget =  new ServicesStatusList($id, $title, $color, $typeId, $models);
				break;

			case WidgetBase::WIDGET_HOSTSSTATUSLIST:
				$widget =  new HostsStatusList($id, $title, $color, $typeId, $models);
				break;
		}


		return $widget;
	}
}
