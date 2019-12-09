<?php

namespace App\Model\Table;

use App\Lib\PluginManager;
use App\Lib\Traits\Cake2ResultTableTrait;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use CakePlugin;

/**
 * Cronjobs Model
 *
 * @property \App\Model\Table\CronschedulesTable|\Cake\ORM\Association\HasMany $Cronschedules
 *
 * @method \App\Model\Entity\Cronjob get($primaryKey, $options = [])
 * @method \App\Model\Entity\Cronjob newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Cronjob[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Cronjob|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Cronjob|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Cronjob patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Cronjob[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Cronjob findOrCreate($search, callable $callback = null, $options = [])
 */
class CronjobsTable extends Table {

    use Cake2ResultTableTrait;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) :void {
        parent::initialize($config);

        $this->setTable('cronjobs');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->hasOne('Cronschedules', [
            'foreignKey' => 'cronjob_id'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator) :Validator {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('task')
            ->maxLength('task', 255)
            ->requirePresence('task', 'create')
            ->allowEmptyString('task', null, false);

        $validator
            ->scalar('plugin')
            ->maxLength('plugin', 255)
            ->requirePresence('plugin', 'create')
            ->allowEmptyString('plugin', null, false);

        $validator
            ->integer('interval')
            ->allowEmptyString('interval');

        $validator
            ->boolean('enabled')
            ->requirePresence('enabled', 'create')
            ->allowEmptyString('enabled', null, false);

        return $validator;
    }

    /**
     * @return array
     */
    public function getCronjobs() {
        $query = $this->find()->contain([
            'cronschedules'
        ]);
        if (!is_null($query)) {
            return $this->formatResultAsCake2($query->disableHydration()->toArray());
        }
        return [];
    }

    /**
     * @param null $id Cronjob ID
     * @return array
     */
    public function getCronjob($id = null) {
        $query = $this->get($id, [
            'contain' => [
                'cronschedules'
            ]
        ]);
        if (!is_null($query)) {
            return $this->formatFirstResultAsCake2($query->toArray());
        }
        return [];
    }

    /**
     * @return array
     */
    public function getEnabledCronjobs() {
        $query = $this->find()
            ->where(['enabled' => 1])
            ->contain('cronschedules')
            ->disableHydration();

        return $this->formatResultAsCake2($query->toArray());
    }


    /**
     * @return array
     */
    public function fetchPlugins() {
        $plugins = [];
        $plugins['Core'] = 'Core';

        foreach ( PluginManager::getAvailablePlugins() as $pluginName) {
            $plugins[$pluginName] = $pluginName;
        }

        return $plugins;
    }

    /**
     * @param $pluginName
     * @return array
     */
    public function fetchTasks($pluginName) {
        $return = [];
        if ($pluginName == 'Core') {
            if (is_dir(APP . 'Shell/Task')) {
                $result = scandir(APP . 'Shell/Task/');
                if (!empty($result) && is_array($result)) {
                    foreach ($result as $file) {
                        if ($file != '.' && $file != '..' && $file != 'empty') {
                            $taskName = str_replace('Task.php', '', $file);
                            $return[$taskName] = $taskName;
                        }
                    }
                }
            }
        } else {
            if (is_dir(APP . 'plugins/' . $pluginName . '/src/Shell/Task')) {
                $result = scandir(OLD_APP . 'Plugin/' . $pluginName . '/src/Shell/Task/');
                if (!empty($result) && is_array($result)) {
                    foreach ($result as $file) {
                        if ($file != '.' && $file != '..' && $file != 'empty') {
                            $taskName = str_replace('Task.php', '', $file);
                            $return[$taskName] = $taskName;
                        }
                    }
                }
            }
        }

        return $return;
    }

    /**
     * @param $task
     * @param string $plugin
     * @return bool
     */
    public function checkForCronjob($task, $plugin = 'Core') {
        $query = $this->find()
            ->where([
                'task'   => $task,
                'plugin' => $plugin
            ])
            ->first();
        if (!is_null($query)) {
            return true;
        }
        return false;
    }
}
