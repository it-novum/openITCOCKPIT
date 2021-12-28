<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Ldapgroups Model
 *
 * @property \App\Model\Table\UsercontainerrolesTable&\Cake\ORM\Association\HasMany $LdapgroupsToUsercontainerroles
 *
 * @method \App\Model\Entity\Ldapgroup newEmptyEntity()
 * @method \App\Model\Entity\Ldapgroup newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Ldapgroup[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Ldapgroup get($primaryKey, $options = [])
 * @method \App\Model\Entity\Ldapgroup findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Ldapgroup patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Ldapgroup[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Ldapgroup|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Ldapgroup saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Ldapgroup[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Ldapgroup[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Ldapgroup[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Ldapgroup[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class LdapgroupsTable extends Table {
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void {
        parent::initialize($config);

        $this->setTable('ldapgroups');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->hasMany('Usercontainerroles', [
            'foreignKey' => 'ldapgroup_id',
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
            ->scalar('cn')
            ->maxLength('cn', 255)
            ->requirePresence('cn', 'create')
            ->notEmptyString('cn');

        $validator
            ->scalar('dn')
            ->maxLength('dn', 512)
            ->requirePresence('dn', 'create')
            ->notEmptyString('dn');

        $validator
            ->scalar('description')
            ->maxLength('description', 512)
            ->allowEmptyString('description');

        return $validator;
    }

    public function getLdapgrous($type = 'all') {
        $query = $this->find()
            ->disableHydration();

        $result = $query->toArray();
        if (empty($result)) {
            return [];
        }

        if ($type === 'all') {
            return $result;
        }

        $list = [];
        foreach ($result as $row) {
            $list[$row['id']] = $row['cn'];
        }
        return $list;
    }
}
