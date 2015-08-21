<?php
App::uses('ModelBehavior', 'Model');

/**
 * Handles sorting for a model. Can be scoped via setting.
 *
 * @package default
 */
class SortableBehavior extends ModelBehavior {
	
/**
 * Default behavior settings
 *
 * @var array
 */
	protected $_defaultSettings = array(
		// an optional array of conditions, which restricts the behavior to only
		// re-sort records matching these conditions.
		'scope' => array(),
		// The field which stores the numeric sort value
		'sortField' => 'sort'
	);

/**
 * @param Model $model 
 * @param array $settings 
 * @return void
 */	
	public function setup(Model $model, $settings = array()) {
		if (!isset($this->settings[ $model->alias ])) {
			$this->settings[ $model->alias ] = $this->_defaultSettings;
		}
		$this->settings[ $model->alias ] = Hash::merge($this->settings[ $model->alias ], $settings);
	}

/**
 * Restores sorting across a model. 
 *
 * @param Model $model 
 * @param string $order 	The order for retrieving all records, 
 *							to base the new sorting on. sortField ASC will be used if no $order is given
 * @param array $scope 		Conditions for retrieving the records. settings.scope will be used if no $scope is given
 * @return array 			An array containing all records which have been updated.
 */	
	public function restoreSorting(Model $model, $order = null, $scope = null) {
		if(!$order) {
			$order = $model->alias . '.' . $this->settings[ $model->alias ]['sortField'] . ' ASC';
		}
		if(!$scope) {
			$scope = $this->settings[ $model->alias ]['scope'];
		}
		$records = $model->find('all', array(
			'conditions' => $scope,
			'order' => $order,
			'contain' => false,
			'fields' => array(
				$model->alias . '.' . $model->primaryKey
			)
		));

		foreach($records as $n => $record) {
			$sort = ($n + 1);
			$model->updateAll(array(
				$model->alias . '.' . $this->settings[ $model->alias ]['sortField'] => $sort
			), array(
				$model->alias . '.' . $model->primaryKey => $record[ $model->alias ]['id']
			));
		}
		return $records;
	}
	
/**
 * Hook to manage sorting afterSave()
 *
 * @param Model $model 
 * @param bool $created 
 * @param array $options 
 * @return bool
 */	
	public function afterSave(Model $model, $created, $options = array()) {
		$record = $model->find('first', array(
			'conditions' => array(
				$model->alias . '.' . $model->primaryKey => $model->id
			),
			'fields' => array(
				$model->alias . '.' . $model->primaryKey,
				$model->alias . '.' . $this->settings[ $model->alias ]['sortField']
			)
		));
		$sortKey = $model->alias . '.' . $this->settings[ $model->alias ]['sortField'];
		$sortPosition = $record[ $model->alias ][ $this->settings[ $model->alias ]['sortField'] ];
		$primaryKey = $model->alias . '.' . $model->primaryKey;
		
		if(!empty($sortPosition)) {
			$conditions = Hash::merge(array(
				$sortKey => $sortPosition
			), $this->settings[ $model->alias ]['scope']);

			$overlappingRow = $model->find('first', array(
				'conditions' => $conditions,
				'fields' => array($primaryKey),
				'contain' => false
			));

			if(!empty($overlappingRow)) {
				$model->updateAll(array(
					$sortKey => $sortKey . ' + 1'
				), Hash::merge(array(
					$sortKey . ' >=' => $sortPosition,
					$primaryKey . ' != ' . $model->id
				), $this->settings[ $model->alias ]['scope']));
			}
		} else {
			$newPosition = 1;
			$highestRecord = $model->find('first', array(
				'order' => $sortKey . ' DESC',
				'conditions' => $this->settings[ $model->alias ]['scope'],
				'fields' => array(
					$sortKey
				),
				'contain' => false
			));
			if(!empty($highestRecord)) {
				$newPosition = ($highestRecord[ $model->alias ][ $this->settings[ $model->alias ]['sortField'] ] + 1);
				$model->updateAll(array(
					$sortKey => $newPosition
				), array(
					$primaryKey => $model->id
				));
			}
		}
		return true;
	}
}