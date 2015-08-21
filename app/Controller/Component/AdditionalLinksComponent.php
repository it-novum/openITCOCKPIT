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


App::uses('Component', 'Controller');
App::uses('Session', 'Component');
App::uses('Html', 'Helper');

/**
 * Creates additional menu entries for different locations. These can be configured outside of this
 * class in the "Config" folder. Therefore, the file "additional_links.php" needs to be in that
 * directory.
 *
 * @author Patrick Nawracay <patrick.nawracay@it-novum.com>
 */
class AdditionalLinksComponent extends Component{
	public $additionalLinks = [];

	/**
	 * Constructor
	 *
	 * @param ComponentCollection $collection A ComponentCollection this component can use to lazy load its components
	 * @param array $settings Array of configuration settings.
	 */
	public function __construct(ComponentCollection $collection, $settings = array()) {
		parent::__construct($collection, $settings);
		$this->additionalLinks = $this->_loadAdditionalLinksConfiguration();
		$this->additionalContent = $this->_loadAdditionalContentConfiguration();
	}

	protected function _loadAdditionalLinksConfiguration(){
		$menuName = 'additional_links';

		$modulePlugins = array_filter(CakePlugin::loaded(), function($value){
			return strpos($value, 'Module') !== false;
		});
		foreach($modulePlugins as $pluginName){
			Configure::load($pluginName . '.' . $menuName, 'silent', 'false');
		}

		$additionalLinks = Configure::read($menuName);
		if(!is_array($additionalLinks)){
			return [];
		}
		usort($additionalLinks, [$this, 'linkSort']);

		return $additionalLinks;
	}
	
	protected function _loadAdditionalContentConfiguration(){
		$menuName = 'additional_content';

		$modulePlugins = array_filter(CakePlugin::loaded(), function($value){
			return strpos($value, 'Module') !== false;
		});
		foreach($modulePlugins as $pluginName){
			Configure::load($pluginName . '.' . $menuName, 'silent', 'false');
		}

		$additionalLinks = Configure::read($menuName);
		if(!is_array($additionalLinks)){
			return [];
		}
		usort($additionalLinks, [$this, 'linkSort']);

		return $additionalLinks;
	}

	/**
	 * Fetches the data out of the configuration array and returns the sorted link elements
	 * as array.
	 *
	 * @param String $controller
	 * @param String $action
	 * @param String|String[] $viewPosition
	 *
	 * @return String[] The menu entries
	 */
	public function fetchLinkData($controller, $action, $viewPosition){
		$result = [];

		foreach($this->additionalLinks as $link){
			$linkPosition = $link['positioning'];

			$hasTitle = isset($link['link']['title']);
			$controllerMatches = $linkPosition['controller'] === $controller;
			$actionMatches = $linkPosition['action'] === $action;
			if(!$hasTitle || !$controllerMatches || !$actionMatches){
				continue;
			}

			$_defaults = [
				'url' => null,
				'options' => [],
				'confirmMessage' => false,
			];
			$linkData = Hash::merge($_defaults, $link['link']);

			if(is_string($viewPosition) && $linkPosition['viewPosition'] === $viewPosition){
				$result[] = $linkData;
			}elseif(is_array($viewPosition) && in_array($linkPosition['viewPosition'], $viewPosition)){
				if (!isset($result[$linkPosition['viewPosition']]) || !is_array($result[$linkPosition['viewPosition']])){
					$result[$linkPosition['viewPosition']] = [];
				}
				$result[$linkPosition['viewPosition']][] = $linkData;
			}else{
				continue;
			}
		}

		if(is_array($viewPosition)){
			foreach($viewPosition as $position){
				if(!isset($result[$position])){
					$result[$position] = []; // Set the key, even if there is no value
				}
			}
		}

		return $result;
	}
	
	/**
	 * Fetches the data out of the configuration array and returns the sorted link elements
	 * as array.
	 *
	 * @param String $controller
	 * @param String $action
	 * @param String|String[] $viewPosition
	 *
	 * @return String[] The menu entries
	 */
	public function fetchContentData($controller, $action, $viewPosition){
		$result = [];

		foreach($this->additionalContent as $elementArray){
			$linkPosition = $elementArray['positioning'];

			$controllerMatches = $linkPosition['controller'] === $controller;
			$actionMatches = $linkPosition['action'] === $action;
			if(!$controllerMatches || !$actionMatches){
				continue;
			}

			$element =  $elementArray['element'];

			if(is_string($viewPosition) && $linkPosition['viewPosition'] === $viewPosition){
				$result[] = $element;
			}elseif(is_array($viewPosition) && in_array($linkPosition['viewPosition'], $viewPosition)){
				if (!isset($result[$linkPosition['viewPosition']]) || !is_array($result[$linkPosition['viewPosition']])){
					$result[$linkPosition['viewPosition']] = [];
				}
				$result[$linkPosition['viewPosition']][] = $element;
			}else{
				continue;
			}
		}

		if(is_array($viewPosition)){
			foreach($viewPosition as $position){
				if(!isset($result[$position])){
					$result[$position] = []; // Set the key, even if there is no value
				}
			}
		}

		return $result;
	}

	protected function linkSort($a, $b){
		// First sort by controller
		if($a['positioning']['controller'] > $b['positioning']['controller']){
			return 1;
		}elseif($a['positioning']['controller'] < $b['positioning']['controller']){
			return -1;
		}

		// Then sort by action
		if($a['positioning']['action'] > $b['positioning']['action']){
			return 1;
		}elseif($a['positioning']['action'] < $b['positioning']['action']){
			return -1;
		}

		// At last sort by the value from the 'sorting' field
		if($a['positioning']['sorting'] > $b['positioning']['sorting']){
			return 1;
		}elseif($a['positioning']['sorting'] < $b['positioning']['sorting']){
			return -1;
		}

		return 0;
	}
}
