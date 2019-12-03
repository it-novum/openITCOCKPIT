<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * DashboardTabs Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\SourceTabsTable&\Cake\ORM\Association\BelongsTo $SourceTabs
 * @property \App\Model\Table\WidgetsTable&\Cake\ORM\Association\HasMany $Widgets
 *
 * @method \App\Model\Entity\DashboardTab get($primaryKey, $options = [])
 * @method \App\Model\Entity\DashboardTab newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\DashboardTab[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\DashboardTab|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\DashboardTab saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\DashboardTab patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\DashboardTab[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\DashboardTab findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class DashboardTabsTable extends Table
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

        $this->setTable('dashboard_tabs');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('SourceTabs', [
            'foreignKey' => 'source_tab_id',
        ]);
        $this->hasMany('Widgets', [
            'foreignKey' => 'dashboard_tab_id',
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
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->integer('position')
            ->requirePresence('position', 'create')
            ->notEmptyString('position');

        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->boolean('shared')
            ->notEmptyString('shared');

        $validator
            ->integer('check_for_updates')
            ->allowEmptyString('check_for_updates');

        $validator
            ->integer('last_update')
            ->allowEmptyString('last_update');

        $validator
            ->boolean('locked')
            ->notEmptyString('locked');

        return $validator;
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
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        $rules->add($rules->existsIn(['source_tab_id'], 'SourceTabs'));

        return $rules;
    }
}
