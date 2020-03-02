<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\I18n\FrozenTime;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Agenthostscache Model
 *
 * @method \App\Model\Entity\Agenthostscache newEmptyEntity()
 * @method \App\Model\Entity\Agenthostscache newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Agenthostscache[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Agenthostscache get($primaryKey, $options = [])
 * @method \App\Model\Entity\Agenthostscache findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Agenthostscache patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Agenthostscache[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Agenthostscache|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Agenthostscache saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Agenthostscache[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Agenthostscache[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Agenthostscache[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Agenthostscache[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AgenthostscacheTable extends Table {
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void {
        parent::initialize($config);

        $this->setTable('agenthostscache');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Hosts', [
            'foreignKey' => 'hostuuid',
            'bindingKey' => 'uuid'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('hostuuid')
            ->maxLength('hostuuid', 255)
            ->requirePresence('hostuuid', 'create')
            ->notEmptyString('hostuuid');

        $validator
            ->scalar('checkdata')
            ->allowEmptyString('checkdata');

        return $validator;
    }

    /**
     * @param string $uuid
     * @return bool
     */
    public function existsByHostuuid($uuid) {
        return $this->exists(['hostuuid' => $uuid]);
    }

    /**
     * @param string $uuid
     * @return array|\Cake\Datasource\EntityInterface|null
     */
    public function getByHostUuid($uuid) {
        $query = $this->find()
            ->where([
                'hostuuid' => $uuid,
            ])->first();
        return $query;
    }

    /**
     * @param string $hostuuid
     * @param string $checkdata
     */
    public function saveCacheData(string $hostuuid, string $checkdata) {
        if ($this->existsByHostuuid($hostuuid)) {
            $Agenthostscache = $this->getByHostUuid($hostuuid);
            $Agenthostscache = $this->patchEntity($Agenthostscache, ['checkdata' => $checkdata, 'modified' => FrozenTime::now()]);
        } else {
            $Agenthostscache = $this->newEntity([
                'hostuuid'  => $hostuuid,
                'checkdata' => $checkdata,
                'modified'  => FrozenTime::now(),
                'created'   => FrozenTime::now()
            ]);
        }
        $this->save($Agenthostscache);
    }
}
