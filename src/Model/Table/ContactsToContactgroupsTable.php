<?php

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;


/**
 * Class __ContactsToContactgroupsTable
 * @package App\Model\Table
 *
 * This is a Table Object for an linking table xxx_to_yyy
 * Only use this Table object for find()->count() operations!
 */
class ContactsToContactgroupsTable extends Table {

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) :void {
        parent::initialize($config);

        $this->setTable('contacts_to_contactgroups');
        $this->setPrimaryKey('id');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator) :Validator {
        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules) :RulesChecker {
        return $rules;
    }


    /**
     * @param int $contactgroupId
     * @return int
     */
    public function getContactsCountByContactgroupId($contactgroupId) {
        $count = $this->find()
            ->where(['contactgroup_id' => $contactgroupId])
            ->count();

        if ($count === null) {
            return 0;
        }

        return $count;
    }
}
