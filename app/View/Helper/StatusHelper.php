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

class StatusHelper extends AppHelper{

	/**
	 * Returns the complete status array of a host object:
	 * state == nagios state (0, 1 or 2)
	 * human_state == a text that humans can read
	 * html == the hml code to create the icon for the status
	 *
	 * @param string $uuid of the object
	 * @param string $href the url of the HTML link
	 * @param array $hoststatus, if not given the $hoststatus array of the current view will be used (default)
	 * @return array with host status for humans and HTML code generation
	 * @author Daniel Ziegler <daniel.ziegler@it-novum.com>
	 * @since 3.0
	 */
	public function humanHostStatus($uuid, $href='javascript:void(0)', $hoststatus = null, $style = ''){
		if($hoststatus === null){
			$hoststatus = $this->_View->viewVars['hoststatus'];
		}
		if(isset($hoststatus[$uuid]) && $hoststatus[$uuid]['Hoststatus']['current_state'] !== null){
			switch($hoststatus[$uuid]['Hoststatus']['current_state']){
				case 0:
					return array('state' => 0, 'human_state' => __('Up'), 'html_icon' => '<a href="'.$href.'" class="btn btn-success status-circle" style="padding:0;'.$style.'"></a>', 'icon' => 'glyphicon glyphicon-ok');
					break;

				case 1:
					return array('state' => 1, 'human_state' => __('Down'), 'html_icon' => '<a href="'.$href.'" class="btn btn-danger status-circle" style="padding:0;'.$style.'"></a>', 'icon' => 'fa fa-exclamation');
					break;

				default:
					return array('state' => 2, 'human_state' => __('Unreachable'), 'html_icon' => '<a href="'.$href.'" class="btn btn-default status-circle" style="padding:0;'.$style.'"></a>', 'icon' => 'fa fa-warning');
			}
		}else{
			return array('state' => 2, 'human_state' => __('Not found in monitoring'), 'html_icon' => '<a href="'.$href.'" class="btn btn-primary status-circle" style="padding:0;'.$style.'"></a>', 'icon' => 'fa fa-question-circle');
		}
	}

	/**
	 * Returns the host output
	 *
	 * @param string $uuid of the object
	 * @param array $hoststatus, if not given the $hoststatus array of the current view will be used (default)
	 * @return string with the hostoutput or error message if host not found in monitoring $hoststatus
	 * @author Daniel Ziegler <daniel.ziegler@it-novum.com>
	 * @since 3.0
	 */
	public function hostOutput($uuid, $hoststatus = null){

		if($hoststatus === null){
			$hoststatus = $this->_View->viewVars['hoststatus'];
		}
		if(isset($hoststatus[$uuid])){
			return $hoststatus[$uuid]['Hoststatus']['output'];
		}

		return '<span class="text-primary italic">'.__('Not found in monitoring').'</span>';
	}

	/**
	 * Returns the complete status array of a service object:
	 * state == nagios state (0, 1, 2 or 3)
	 * human_state == a text that humans can read
	 * html == the hml code to create the icon for the status
	 *
	 * @param string $uuid of the object
	 * @param string $href the url of the HTML link
	 * @param array $servicestatus, if not given the $servicestatus array of the current view will be used (default)
	 * @return array with service status for humans and HTML code generation
	 * @author Daniel Ziegler <daniel.ziegler@it-novum.com>
	 * @since 3.0
	 */
	public function humanServiceStatus($uuid, $href='javascript:void(0)', $servicestatus = null, $content = '', $style = ''){
		if($servicestatus === null){
			$servicestatus = $this->_View->viewVars['servicestatus'];
		}
		if(isset($servicestatus[$uuid]['Servicestatus']['current_state']) && !in_array($servicestatus[$uuid]['Servicestatus']['current_state'], [null, 'null'])){
			switch($servicestatus[$uuid]['Servicestatus']['current_state']){
				case 0:
					return array('state' => 0, 'human_state' => __('Ok'), 'html_icon' => '<a href="'.$href.'" class="btn btn-success btn-xs status-circle" style="'.$style.'">'.$content.'</a>', 'icon' => 'glyphicon glyphicon-ok');
					break;

				case 1:
					return array('state' => 1, 'human_state' => __('Warning'), 'html_icon' => '<a href="'.$href.'" class="btn btn-warning btn-xs status-circle" style="'.$style.'">'.$content.'</a>', 'icon' => 'fa fa-exclamation');
					break;
				case 2:
					return array('state' => 2, 'human_state' => __('Critical'), 'html_icon' => '<a href="'.$href.'" class="btn btn-danger btn-xs status-circle" style="'.$style.'">'.$content.'</a>', 'icon' => 'fa fa-exclamation');
					break;
				default:
					return array('state' => 3, 'human_state' => __('Unknown'), 'html_icon' => '<a href="'.$href.'" class="btn btn-default btn-xs status-circle" style="'.$style.'">'.$content.'</a>', 'icon' => 'fa fa-warning');
			}
		}else{
			return array('state' => 3, 'human_state' => __('Not found in monitoring'), 'html_icon' => '<a href="'.$href.'" class="btn btn-primary btn-xs status-circle" style="'.$style.'">'.$content.'</a>', 'icon' => 'fa fa-question-circle');
		}
	}

	/**
	 * Return the CSS class for the current host status
	 * <span class="<?php echo $this->HostStatusColor($uuid); ?>"></span>
	 *
	 * @param string $uuid of the object
	 * @param array $hoststatus, if not given the $hoststatus array of the current view will be used (default)
	 * @return string CSS class of the color
	 * @author Daniel Ziegler <daniel.ziegler@it-novum.com>
	 * @since 3.0
	 */
	function HostStatusColor($uuid, $hoststatus = null){
		if($hoststatus === null){
			$hoststatus = $this->_View->viewVars['hoststatus'];
		}

		if(isset($hoststatus[$uuid])){
			switch($hoststatus[$uuid]['Hoststatus']['current_state']){
				case 0:
				return 'txt-color-green';

				case 1:
				return 'txt-color-red';

				default:
				return 'txt-color-blueDark';
			}
		}
		//no status found in database
		return 'text-primary';
	}

	/**
	 * Return the CSS class for the current service status
	 * <span class="<?php echo $this->ServiceStatusColor($uuid); ?>"></span>
	 *
	 * @param string $uuid of the object
	 * @param array $servicestauts, if not given the $servicestatus array of the current view will be used (default)
	 * @return string CSS class of the color
	 * @author Daniel Ziegler <daniel.ziegler@it-novum.com>
	 * @since 3.0
	 */
	function ServiceStatusColor($uuid, $servicestatus = null){
		if($servicestatus === null){
			$servicestatus = $this->_View->viewVars['servicestatus'];
		}

		if(isset($servicestatus[$uuid])){
			switch($servicestatus[$uuid]['Servicestatus']['current_state']){
				case 0:
				return 'txt-color-green';

				case 1:
				return 'warning';

				case 2:
				return 'txt-color-red';

				default:
				return 'txt-color-blueDark';
			}
		}
		//no status found in database
		return 'text-primary';
	}

	/**
	 * Return the status color for a Service
	 * @param int $state the current status of a Service
	 * @return array which contains the human state and the css class
	 * @author Maximilian Pappert <maximilian.pappert@it-novum.com>
	 * @since 3.0
	 */
	function ServiceStatusColorSimple($state){
		if(isset($state)){
			switch ((int)$state) {
				case 0:
					return ['human_state' => __('Ok'),
							'class' => 'btn-success',
							'hexColor' => '#5CB85C'];
				case 1:
					return ['human_state' => __('Warning'),
							'class' => 'btn-warning',
							'hexColor' => '#f0ad4e'];
				case 2:
					return ['human_state' => __('Critical'),
							'class' => 'btn-danger',
							'hexColor' => '#d9534f'];
				case 3:
					return ['human_state' => __('Unreachable'),
							'class' => 'btn-unknown',
							'hexColor' => '#4C4F53'];
				default:
					return ['human_state' => __('Not Found'),
							'class' => 'btn-primary',
							'hexColor' => '#337ab7'];
			}
		}
	}

	/**
	 * Return the status background color for a Host
	 * @param int $state the current status of a Host
	 * @return array which contains the human state and the css class
	 */
	function HostStatusBackgroundColor($state = 2){
		$state = ($state === null)?2:$state;
		$background_color = [
			0 => 'bg-color-green',
			1 => 'bg-color-red',
			2 => 'bg-color-blueLight',
		];
		return $background_color[$state];
	}

	/**
	 * Return the status background color for a Service
	 * @param int $state the current status of a Service
	 * @return array which contains the human state and the css class
	 */
	function ServiceStatusBackgroundColor($state = 2){
		$state = ($state === null)?3:$state;
		$background_color = [
			0 => 'bg-color-green',
			1 => 'bg-color-orange',
			2 => 'bg-color-red',
			3 =>'bg-color-blueLight',
		];
		return $background_color[$state];
	}

	/**
	 * Return the status color for a Host
	 * @param int $state the current status of a Host
	 * @return array which contains the human state and the css class
	 * @author Maximilian Pappert <maximilian.pappert@it-novum.com>
	 * @since 3.0
	 */
	function HostStatusColorSimple($state){
		if(isset($state)){
			switch ((int)$state) {
				case 0:
					return ['human_state' => __('Ok'),
							'class' => 'btn-success',
							'hexColor' => '#5CB85C'];
				case 1:
					return ['human_state' => __('Down'),
							'class' => 'btn-danger',
							'hexColor' => '#d9534f'];
				case 2:
					return ['human_state' => __('Unreachable'),
							'class' => 'btn-unknown',
							'hexColor' => '#4C4F53'];
				default:
					return ['human_state' => __('Not Found'),
							'class' => 'btn-primary',
							'hexColor' => '#337ab7'];
			}
		}
	}

	/**
	 * Wrapper function of $this->get();
	 * Returns the value of a database field from Hostatus model
	 *
	 * @param string $uuid of the object
	 * @param string $field the database field of host/service status you want to get the value of
	 * @param mixed $default the default return value, if the requested field does not exists
	 * @param array $hoststatus, if not given the $hoststatus array of the current view will be used (default)
	 * @return string the value of the given database field from model
	 * @author Daniel Ziegler <daniel.ziegler@it-novum.com>
	 * @since 3.0
	 */
	public function hget($uuid, $field, $default = null, $hoststatus = null){
		return $this->get($uuid, $field, $defaul, 'Hoststatus', $hoststatus);
	}

	/**
	 * Wrapper function of $this->get();
	 * Returns the value of a database field from Servicestatus model
	 *
	 * @param string $uuid of the object
	 * @param string $field the database field of host/service status you want to get the value of
	 * @param mixed $default the default return value, if the requested field does not exists
	 * @param array $servicestatus, if not given the $servicestatus array of the current view will be used (default)
	 * @return string the value of the given database field from model
	 * @author Daniel Ziegler <daniel.ziegler@it-novum.com>
	 * @since 3.0
	 */
	public function sget($uuid, $field, $default = null, $servicestatus = null){
		return $this->get($uuid, $field, $default = null, 'Servicestatus', null, $servicestatus);
	}

	/**
	 * Returns the value of a database field from Hostatus or Servicestatus model
	 *
	 * @param string $uuid of the object
	 * @param string $field the database field of host/service status you want to get the value of
	 * @param mixed $default the default return value, if the requested field does not exists
	 * @param string $ModelName the name of the host/service status model ('Hoststatus' or 'Servicestatus')
	 * @param array $hoststatus, if not given the $hoststatus array of the current view will be used (default)
	 * @param array $servicestatus, if not given the $servicestatus array of the current view will be used (default)
	 * @return string the value of the given database field from model
	 * @author Daniel Ziegler <daniel.ziegler@it-novum.com>
	 * @since 3.0
	 *
	 */
	public function get($uuid, $field, $default = null, $ModelName = 'Hoststatus', $hoststatus = null, $servicestatus = null){
		if($ModelName == 'Hoststatus'){
			if($hoststatus === null){
				$hoststatus = $this->_View->viewVars['hoststatus'];
			}

			if(isset($hoststatus[$uuid]) && isset($hoststatus[$uuid]['Hoststatus'][$field])){
				return $hoststatus[$uuid]['Hoststatus'][$field];
			}
		}else{
			if($servicestatus === null){
				$servicestatus = $this->_View->viewVars['servicestatus'];
			}
			if(isset($servicestatus[$uuid]) && isset($servicestatus[$uuid]['Servicestatus'][$field])){
				return $servicestatus[$uuid]['Servicestatus'][$field];
			}
		}
		return $default;
	}

	/**
	 * Retuns the state_type of an service as string
	 *
	 * @param int $state_type of an service
	 * @return string given state type fopr humans
	 * @author Daniel Ziegler <daniel.ziegler@it-novum.com>
	 * @since 3.0
	 *
	 */
	public function humanServiceStateType($state_type){
		if($state_type === 0 || $state_type === '0'){
			return __('soft');
		}

		if($state_type === 1 || $state_type === '1'){
			return __('hard');
		}

		return '';
	}

	function HostStatusTextColor($state = 2){
		switch($state){
			case 0:
			return 'txt-color-green';

			case 1:
			return 'txt-color-red';

			default:
			return 'txt-color-blueLight';
		}
	}
	function ServiceStatusTextColor($state = 2){
		switch($state){
			case 0:
			return 'txt-color-green';

			case 1:
			return 'warning';

			case 2:
			return 'txt-color-red';

			default:
			return 'txt-color-blueLight';
		}
	}

	/**
	 * Returns human_state for service
	 *
	 * @param integer $hoststatus
	 * @return string host status for humans
	 * @author Irina Bering <irina.bering@it-novum.com>
	 * @since 3.0
	 */
	public function humanSimpleHostStatus($hoststatus = 0){
		switch($hoststatus){
			case 0:
				return __('Up');
			case 1:
				return __('Down');
			case 2:
				return __('Unreachable');
		}
	}
		/**
	 * Returns human_state for service
	 *
	 * @param integer $hoststatus
	 * @return string host status for humans
	 * @author Irina Bering <irina.bering@it-novum.com>
	 * @since 3.0
	 */
	public function humanSimpleServiceStatus($servicestatus = 0){
		switch($servicestatus){
			case 0:
				return __('Ok');
			case 1:
				return __('Warning');
			case 2:
				return __('Critical');
			case 3:
				return __('Unknown');
		}
	}
	
	public function automapIcon($servicestatus){
		$stateClasses = [
			0 => 'ok',
			1 => 'warning',
			2 => 'critical',
			3 => 'unknown'
		];
		
		$html = '<i class="fa fa-square '.$stateClasses[$servicestatus['Servicestatus']['current_state']].'"></i>';
		
		if($servicestatus['Servicestatus']['problem_has_been_acknowledged'] > 0){
			$html = '<i class="fa fa-user '.$stateClasses[$servicestatus['Servicestatus']['current_state']].'"></i>';
		}
		
		if($servicestatus['Servicestatus']['scheduled_downtime_depth'] > 0){
			$html = '<i class="fa fa-power-off '.$stateClasses[$servicestatus['Servicestatus']['current_state']].'"></i>';
		}
		return $html;
	}
}