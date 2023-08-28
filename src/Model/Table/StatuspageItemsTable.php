<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use itnovum\openITCOCKPIT\Core\DbBackend;

/**
 * StatuspageItems Model
 *
 * @property \App\Model\Table\StatuspagesTable&\Cake\ORM\Association\BelongsTo $Statuspages
 *
 * @method \App\Model\Entity\StatuspageItem newEmptyEntity()
 * @method \App\Model\Entity\StatuspageItem newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\StatuspageItem[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\StatuspageItem get($primaryKey, $options = [])
 * @method \App\Model\Entity\StatuspageItem findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\StatuspageItem patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\StatuspageItem[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\StatuspageItem|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\StatuspageItem saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\StatuspageItem[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\StatuspageItem[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\StatuspageItem[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\StatuspageItem[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class StatuspageItemsTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('statuspage_items');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Statuspages', [
            'foreignKey' => 'statuspage_id',
            'joinType' => 'INNER',
        ]);

        $this->belongsTo('Hosts', [
            'foreignKey' => 'object_id',
            'joinType'   => 'INNER',
            'conditions' => [
                'type' => 'host'
            ]
        ]);

        $this->belongsTo('Services', [
            'foreignKey' => 'object_id',
            'joinType'   => 'INNER',
            'conditions' => [
                'type' => 'service'
            ]
        ]);

        $this->belongsTo('Hostgroups', [
            'foreignKey' => 'object_id',
            'joinType'   => 'INNER',
            'conditions' => [
                'type' => 'hostgroup'
            ]
        ]);

        $this->belongsTo('Servicegroups', [
            'foreignKey' => 'object_id',
            'joinType'   => 'INNER',
            'conditions' => [
                'type' => 'servicegroup'
            ]
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('statuspage_id')
            ->notEmptyString('statuspage_id');

        $validator
            ->scalar('type')
            ->maxLength('type', 20)
            ->requirePresence('type', 'create')
            ->notEmptyString('type');

        $validator
            ->scalar('object_id')
            ->requirePresence('object_id', 'create')
            ->notEmptyString('object_id');

        $validator
            ->scalar('display_text')
            ->maxLength('display_text', 255)
            ->allowEmptyString('display_text');

        return $validator;
    }

    /**
     * @param $id
     * @return bool
     */
    public function existsById($id) {
        return $this->exists(['StatuspageItems.id' => $id]);
    }


    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn('statuspage_id', 'Statuspages'), ['errorField' => 'statuspage_id']);

        return $rules;
    }
}
