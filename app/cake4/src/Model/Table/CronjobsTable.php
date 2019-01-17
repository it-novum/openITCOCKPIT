<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
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
class CronjobsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
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
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', 'create');

        $validator
            ->scalar('task')
            ->maxLength('task', 255)
            ->requirePresence('task', 'create')
            ->allowEmptyString('task', false);

        $validator
            ->scalar('plugin')
            ->maxLength('plugin', 255)
            ->requirePresence('plugin', 'create')
            ->allowEmptyString('plugin', false);

        $validator
            ->integer('interval')
            ->allowEmptyString('interval');

        $validator
            ->boolean('enabled')
            ->requirePresence('enabled', 'create')
            ->allowEmptyString('enabled', false);

        return $validator;
    }

    /**
     * @return array|\Cake\Datasource\ResultSetInterface
     */
    public function getCronjobs(){
        $query = $this->find()->contain([
            'cronschedules'
        ]);
        if(!is_null($query)){
            return $query->toArray();
        }
        return $query;
    }



    public function fetchPlugins() {
        $plugins = [];
        $plugins['Core'] = 'Core';
        $modulePlugins = array_filter(CakePlugin::loaded(), function ($value) {
            return strpos($value, 'Module') !== false;
        });
        foreach ($modulePlugins as $pluginName) {
            $plugins[$pluginName] = $pluginName;
        }

        return $plugins;
    }

    public function fetchTasks($pluginName) {
        $return = [];
        if ($pluginName == 'Core') {
            if (is_dir(OLD_APP . 'Console/Command/Task/')) {
                $result = scandir(OLD_APP . 'Console/Command/Task/');
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
            if (is_dir(OLD_APP . 'Plugin/' . $pluginName . '/Console/Command/Task/')) {
                $result = scandir(OLD_APP . 'Plugin/' . $pluginName . '/Console/Command/Task/');
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
}
