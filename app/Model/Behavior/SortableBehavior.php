<?php
App::uses('ModelBehavior', 'Model');

/**
 * Handles sorting for a model. Can be scoped via setting.
 * @package default
 */
class SortableBehavior extends ModelBehavior
{

    /**
     * Default behavior settings
     * @var array
     */
    protected $_defaultSettings = [
        // an optional array of conditions, which restricts the behavior to only
        // re-sort records matching these conditions.
        'scope'     => [],
        // The field which stores the numeric sort value
        'sortField' => 'sort',
    ];

    /**
     * @param Model $model
     * @param array $settings
     *
     * @return void
     */
    public function setup(Model $model, $settings = [])
    {
        if (!isset($this->settings[$model->alias])) {
            $this->settings[$model->alias] = $this->_defaultSettings;
        }
        $this->settings[$model->alias] = Hash::merge($this->settings[$model->alias], $settings);
    }

    /**
     * Restores sorting across a model.
     *
     * @param Model  $model
     * @param string $order       The order for retrieving all records,
     *                            to base the new sorting on. sortField ASC will be used if no $order is given
     * @param array  $scope       Conditions for retrieving the records. settings.scope will be used if no $scope is
     *                            given
     *
     * @return array            An array containing all records which have been updated.
     */
    public function restoreSorting(Model $model, $order = null, $scope = null)
    {
        if (!$order) {
            $order = $model->alias.'.'.$this->settings[$model->alias]['sortField'].' ASC';
        }
        if (!$scope) {
            $scope = $this->settings[$model->alias]['scope'];
        }
        $records = $model->find('all', [
            'conditions' => $scope,
            'order'      => $order,
            'contain'    => false,
            'fields'     => [
                $model->alias.'.'.$model->primaryKey,
            ],
        ]);

        foreach ($records as $n => $record) {
            $sort = ($n + 1);
            $model->updateAll([
                $model->alias.'.'.$this->settings[$model->alias]['sortField'] => $sort,
            ], [
                $model->alias.'.'.$model->primaryKey => $record[$model->alias]['id'],
            ]);
        }

        return $records;
    }

    /**
     * Hook to manage sorting afterSave()
     *
     * @param Model $model
     * @param bool  $created
     * @param array $options
     *
     * @return bool
     */
    public function afterSave(Model $model, $created, $options = [])
    {
        $record = $model->find('first', [
            'conditions' => [
                $model->alias.'.'.$model->primaryKey => $model->id,
            ],
            'fields'     => [
                $model->alias.'.'.$model->primaryKey,
                $model->alias.'.'.$this->settings[$model->alias]['sortField'],
            ],
        ]);
        $sortKey = $model->alias.'.'.$this->settings[$model->alias]['sortField'];
        $sortPosition = $record[$model->alias][$this->settings[$model->alias]['sortField']];
        $primaryKey = $model->alias.'.'.$model->primaryKey;

        if (!empty($sortPosition)) {
            $conditions = Hash::merge([
                $sortKey => $sortPosition,
            ], $this->settings[$model->alias]['scope']);

            $overlappingRow = $model->find('first', [
                'conditions' => $conditions,
                'fields'     => [$primaryKey],
                'contain'    => false,
            ]);

            if (!empty($overlappingRow)) {
                $model->updateAll([
                    $sortKey => $sortKey.' + 1',
                ], Hash::merge([
                    $sortKey.' >=' => $sortPosition,
                    $primaryKey.' != '.$model->id,
                ], $this->settings[$model->alias]['scope']));
            }
        } else {
            $newPosition = 1;
            $highestRecord = $model->find('first', [
                'order'      => $sortKey.' DESC',
                'conditions' => $this->settings[$model->alias]['scope'],
                'fields'     => [
                    $sortKey,
                ],
                'contain'    => false,
            ]);
            if (!empty($highestRecord)) {
                $newPosition = ($highestRecord[$model->alias][$this->settings[$model->alias]['sortField']] + 1);
                $model->updateAll([
                    $sortKey => $newPosition,
                ], [
                    $primaryKey => $model->id,
                ]);
            }
        }

        return true;
    }
}