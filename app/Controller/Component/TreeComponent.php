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



/**
 * Class TreeComponent
 * @property Container Container
 */
class TreeComponent extends Component {

	/**
	 * Creates the TreeComponent class
	 * Loading:
	 * Container Model => $this->Container
	 * Constants Component => $this->Constants
	 */
	function __construct(){
		//Loading the Container model
		$this->Container = ClassRegistry::init('Container');
		App::import('Component', 'Constants');
		$this->Constants = new ConstantsComponent();
	}

	/**
	 * Creates an HTML progressbar
	 * This function can be called with a parrent id of an container and will fatch all childrens of the container
	 * and return you a path to the childrens
	 * This is mostly used to get the children of a tenant or all childs of the ROOT container
	 * Example:
	 * $id = 1
	 * Return: [
	 * 1 => /root
	 * 2 => /root/tenant/
	 * 3 => /root/tenant/location
	 * ]
	 *
	 * ### Options
	 *
	 * - `delimiter`   The delimiter for the path (default /)
	 * - `order` of the retun array asc|desc (default asc)
	 *
	 * @param integer $id of the parent container (normaly root or a tenant) you would get all child containers with path
	 * @param array $options Array of options
	 * @param array $options with container type constants that should be considered
	 * @return array with all path [$id] => $path
	 */
	function path($id = null, $options = [], $valide_types = [CT_GLOBAL, CT_TENANT, CT_LOCATION, CT_NODE]){
			$_options = array(
				'delimiter' => '/',
				'valide_types' => $valide_types,
				'order' => 'asc'
			);
			$options = Hash::merge($_options, $options);
			if($this->Container->hasAny()){
				$parent = $this->Container->find('all', [
					'recursive' => -1,
					'conditions' => [
						'id' => $id
					]
				]);

				//Merging parent and childs into one array
				/*foreach($this->Container->children($id) as $child){
					$parent[] = $child;
				}*/

				$paths = [];
				foreach($parent as $container){
					if(in_array($container['Container']['containertype_id'], $options['valide_types'])){
						$paths[$container['Container']['id']] = '/'.$this->treePath($container['Container']['id'], $options);
					}
				}

				// some basic php sort functions, because Hash::sort will drop the key => value association
				if($options['order'] === 'asc'){
					asort($paths);
				}

				if($options['order'] === 'desc'){
					arsort($paths);
				}

				return $paths;
			}
			throw new NotFoundException(__('tenant.notfound'));
	}

	/**
	 * Returns tha path to a single node in the tree
	 *
	 * ### Options
	 *
	 * - `delimiter`   The delimiter for the path (default /)
	 * - `order` of the retun array asc|desc (default asc)
	 *
	 * @param integer $id of the container
	 * @param array $options Array of options
	 * @return string with the path to the container
	 */
	public function treePath($id = null, $options = []){
		$tree = $this->Container->getPath($id);
		$path = '';
		$ContainerNames = [];
		foreach($tree as $parent){
			$ContainerNames[] = $parent['Container']['name'];
			//$path.= $parent['Container']['name'].$options['delimiter'];
		}

		return implode($options['delimiter'], $ContainerNames);
	}

	/**
	 * Is a wrapper function for $this->path for an easy access and call
	 * This is mostly used to create selectboxes
	 *
	 * ### Options
	 * please check the wrapped function $this->path
	 *
	 * @param integer $id of the parent container (normaly root or a tenant) you would get all child containers with path
	 * @param array $options with container type constants that should be considered
	 * @param array $options Array of options
	 * @param bool $hasRootPrivilges of the user session
	 * @param int/array a list or id of Container Types you want to explude from result
	 * @return array with all path [$id] => $path
	 */
	public function easyPath($id = null, $ObjectsByConstancName = [], $options = [], $hasRootPrivileges = false, $exclude = []){
		if($hasRootPrivileges == false){
			if(is_array($id)){
				// User has no root privileges so we need to delete the root container if it $id array
				if(($key = array_search(ROOT_CONTAINER, $id)) !== false){
					unset($id[$key]);
				}
			}else{
				if($id == ROOT_CONTAINER){
					throw new ForbiddenException(__('You need root privileges'));
				}
			}
		}

		//debug($this->Constants->containerProperties($ObjectsByConstancName));
		if(!empty($ObjectsByConstancName)){
			return $this->path($id, $options, $this->Constants->containerProperties($ObjectsByConstancName, $exclude));
		}
		return [];
	}

	/**
	 * @param int|int[] $containerIds
	 * @param bool      $resolveRoot
	 * @return int[]
	 */
	public function resolveChildrenOfContainerIds($containerIds, $resolveRoot = false, $includeContainerTypes = []){
		if(!is_array($containerIds)){
			$containerIds = [$containerIds];
		}

		$containerIds = array_unique($containerIds);
		$result = [ROOT_CONTAINER];
		foreach($containerIds as $containerId){
			$containerId = (int) $containerId;
			if($containerId === ROOT_CONTAINER && $resolveRoot === false){
				continue;
			}
			$tmpResult = $this->Container->children($containerId, false, ['id', 'containertype_id']);
			if(!empty($includeContainerTypes)){
				//$tmpResult = Hash::extract($tmpResult, '{n}.Container[containertype_id=/^(?!('.implode('|', $includeContainerTypes).')$)/].id');
				$tmpResult = Hash::extract($tmpResult, '{n}.Container[containertype_id=/^('.implode('|', $includeContainerTypes).')$/].id');
			}else{
				$tmpResult = Hash::extract($tmpResult, '{n}.Container.id');	
			}
			$result = array_merge($result, $tmpResult);
			$result[] = $containerId;
		}
		return array_unique($result);
	}
}
