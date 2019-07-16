<?php

namespace App\Model\Table;

use App\Lib\Traits\Cake2ResultTableTrait;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ConfigurationFiles Model
 *
 * @method \App\Model\Entity\ConfigurationFile get($primaryKey, $options = [])
 * @method \App\Model\Entity\ConfigurationFile newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ConfigurationFile[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ConfigurationFile|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ConfigurationFile saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ConfigurationFile patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ConfigurationFile[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ConfigurationFile findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ConfigurationFilesTable extends Table {

    use Cake2ResultTableTrait;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->setTable('configuration_files');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator) {
        $validator
            ->nonNegativeInteger('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('config_file')
            ->maxLength('config_file', 255)
            ->requirePresence('config_file', 'create')
            ->notEmptyFile('config_file');

        $validator
            ->scalar('key')
            ->maxLength('key', 2000)
            ->requirePresence('key', 'create')
            ->notEmptyString('key');

        $validator
            ->scalar('value')
            ->maxLength('value', 2000)
            ->requirePresence('value', 'create')
            ->notEmptyString('value');

        return $validator;
    }

    /**
     * @param string $configFile
     * @return array|null
     */
    public function getConfigValuesByConfigFile($configFile) {
        $query = $this->find()
            ->where([
                'ConfigurationFiles.config_file' => $configFile
            ])
            ->disableHydration()
            ->all();

        return $this->formatResultAsCake2($query->toArray(), false);
    }
}
