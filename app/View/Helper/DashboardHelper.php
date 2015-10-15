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

class DashboardHelper extends AppHelper{
	
	public $helpers = [
		'Acl',
	];
	
	public function render($widget){
		$html = $this->_header($widget);
		$html .= $this->_body($widget);
		$html .= $this->_end($widget);
		return $html;
	}
	
	private function _header($widget){
		$html = '<div data-gs-height="'.h($widget['Widget']['height']).'" data-gs-width="'.h($widget['Widget']['width']).'" data-gs-x="'.h($widget['Widget']['row']).'" data-gs-y="'.h($widget['Widget']['col']).'" data-widget-id="'.h($widget['Widget']['id']).'" data-widget-type-id="'.h($widget['Widget']['type_id']).'" class="grid-stack-item ui-draggable ui-resizable">';
			$html .= '<div class="grid-stack-item-content">';
				$html .= '<div id="widget-color-'.h($widget['Widget']['id']).'"" class="jarviswidget '.h($widget['Widget']['color']).'">';
					$html .= '<header class="ui-draggable-handle" role="heading">';
						$html .= '<div role="menu" class="jarviswidget-ctrls">';
							$html .= $this->_headerMenu($widget);
						$html .= '</div>';
						$html .= '<h2><i class="fa '.h($widget['Settings']['icon']).'"></i> <span id="widget-title-'.h($widget['Widget']['id']).'">'.h($widget['Widget']['title']).'</span></h2>';
					$html .= '</header>';
		return $html;
	}
	
	private function _body($widget){
		$html = '<div class="widget-body padding-0">';
			$html .= $this->_View->element($widget['Settings']['element'], ['widget' => $widget]);
		$html .= '</div>';
		return $html;
	}
	
	private function _end($widget){
				$html = '</div>';
			$html .= '</div>';
		$html .= '</div>';
		return $html;
	}
	
	private function _headerMenu($widget){
		$html = '
			<a data-original-title="'.__('Edit title').'" data-placement="left" rel="tooltip" class="button-icon jarviswidget-edit-btn changeTitle" href="javascript:void(0);" data-widget-id="'.h($widget['Widget']['id']).'"><i class="fa fa-cog "></i></a>
			<div class="widget-toolbar pull-left" style="border:0px;" role="menu">
				<a href="javascript:void(0);" class="dropdown-toggle color-box selector '.h($widget['Widget']['color']).'" style="vertical-align: baseline;" id="currentColor" color="#404040" current-color="'.h($widget['Widget']['color']).'" data-toggle="dropdown" data-widget-id="'.h($widget['Widget']['id']).'"></a>
				<ul class="dropdown-menu arrow-box-up-right pull-right color-select">
					<li style="display: inline-block; margin:0; float: none;"><span data-original-title="'.__('Green Grass').'" data-placement="left" rel="tooltip" data-widget-setstyle="jarviswidget-color-green" select-color="true" color="#356E35" class="bg-color-green"></span></li>
					<li style="display: inline-block; margin:0; float: none;"><span data-original-title="'.__('Dark Green').'" data-placement="top" rel="tooltip" data-widget-setstyle="jarviswidget-color-greenDark" select-color="true" color="#496949" class="bg-color-greenDark"></span></li>
					<li style="display: inline-block; margin:0; float: none;"><span data-original-title="'.__('Light Green').'" data-placement="top" rel="tooltip" data-widget-setstyle="jarviswidget-color-greenLight" select-color="true" color="#71843F" class="bg-color-greenLight"></span></li>
					<li style="display: inline-block; margin:0; float: none;"><span data-original-title="'.__('Purple').'" data-placement="top" rel="tooltip" data-widget-setstyle="jarviswidget-color-purple" select-color="true" color="#6E587A" class="bg-color-purple"></span></li>
					<li style="display: inline-block; margin:0; float: none;"><span data-original-title="'.__('Magenta').'" data-placement="top" rel="tooltip" data-widget-setstyle="jarviswidget-color-magenta" select-color="true" color="#6E3671" class="bg-color-magenta"></span></li>
					<li style="display: inline-block; margin:0; float: none;"><span data-original-title="'.__('Pink').'" data-placement="left" rel="tooltip" data-widget-setstyle="jarviswidget-color-pink" select-color="true" color="#AC5287" class="bg-color-pink"></span></li>
					<li style="display: inline-block; margin:0; float: none;"><span data-original-title="'.__('Fade Pink').'" data-placement="left" rel="tooltip" data-widget-setstyle="jarviswidget-color-pinkDark" select-color="true" color="#A8829F" class="bg-color-pinkDark"></span></li>
					<li style="display: inline-block; margin:0; float: none;"><span data-original-title="'.__('Light Blue').'" data-placement="top" rel="tooltip" data-widget-setstyle="jarviswidget-color-blueLight" select-color="true" color="#92A2A8" class="bg-color-blueLight"></span></li>
					<li style="display: inline-block; margin:0; float: none;"><span data-original-title="'.__('Teal').'" data-placement="top" rel="tooltip" data-widget-setstyle="jarviswidget-color-teal" select-color="true" color="#568A89" class="bg-color-teal"></span></li>
					<li style="display: inline-block; margin:0; float: none;"><span data-original-title="'.__('Ocean Blue').'" data-placement="top" rel="tooltip" data-widget-setstyle="jarviswidget-color-blue" select-color="true" color="#57889C" class="bg-color-blue"></span></li>
					<li style="display: inline-block; margin:0; float: none;"><span data-original-title="'.__('Night Sky').'" data-placement="top" rel="tooltip" data-widget-setstyle="jarviswidget-color-blueDark" select-color="true" color="#4C4F53" class="bg-color-blueDark"></span></li>
					<li style="display: inline-block; margin:0; float: none;"><span data-original-title="'.__('Night').'" data-placement="left" rel="tooltip" data-widget-setstyle="jarviswidget-color-darken" select-color="true" color="#404040" class="bg-color-darken"></span></li>
					<li style="display: inline-block; margin:0; float: none;"><span data-original-title="'.__('Day Light').'" data-placement="left" rel="tooltip" data-widget-setstyle="jarviswidget-color-yellow" select-color="true" color="#B09B5B" class="bg-color-yellow"></span></li>
					<li style="display: inline-block; margin:0; float: none;"><span data-original-title="'.__('Orange').'" data-placement="bottom" rel="tooltip" data-widget-setstyle="jarviswidget-color-orange" select-color="true" color="#C79121" class="bg-color-orange"></span></li>
					<li style="display: inline-block; margin:0; float: none;"><span data-original-title="'.__('Dark Orange').'" data-placement="bottom" rel="tooltip" data-widget-setstyle="jarviswidget-color-orangeDark" select-color="true" color="#A57225" class="bg-color-orangeDark"></span></li>
					<li style="display: inline-block; margin:0; float: none;"><span data-original-title="'.__('Red Rose').'" data-placement="bottom" rel="tooltip" data-widget-setstyle="jarviswidget-color-red" select-color="true" color="#A90329" class="bg-color-red"></span></li>
					<li style="display: inline-block; margin:0; float: none;"><span data-original-title="'.__('Light Red').'" data-placement="bottom" rel="tooltip" data-widget-setstyle="jarviswidget-color-redLight" select-color="true" color="#A65858" class="bg-color-redLight"></span></li>
					<li style="display: inline-block; margin:0; float: none;"><span data-original-title="'.__('Purity').'" data-placement="left" rel="tooltip" data-widget-setstyle="jarviswidget-color-white" select-color="true" color="#FFFFFF" class="bg-color-white"></span></li>
				</ul>
			</div>
			<a data-original-title="'.__('Delete').'" data-placement="left" rel="tooltip" class="button-icon jarviswidget-delete-btn deleteWidget" href="javascript:void(0);" data-widget-id="'.h($widget['Widget']['id']).'">
				<i class="fa fa-times"></i>
			</a>
			';
		return $html;
	}
}
