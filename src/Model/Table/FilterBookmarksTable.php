<?php

namespace App\Model\Table;

use App\Model\Entity\FilterBookmark;
use App\Lib\Traits\PaginationAndScrollIndexTrait;
use Cake\Database\Query;
use Cake\Datasource\EntityInterface;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use itnovum\openITCOCKPIT\Core\UUID;

class FilterBookmarksTable extends Table {
    //use PaginationAndScrollIndexTrait;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void {
        parent::initialize($config);

        $this->setTable('filter_bookmarks');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
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
            ->scalar('uuid')
            ->maxLength('uuid', 37)
            ->requirePresence('uuid', 'create')
            ->allowEmptyString('uuid', null, false)
            ->add('uuid', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('filter_entity')
            ->maxLength('filter_entity', 255)
            ->requirePresence('filter_entity', 'create')
            ->allowEmptyString('filter_entity', null, false);

        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->allowEmptyString('name', null, false);

        return $validator;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function existsById($id) {
        return $this->exists(['FilterBookmarks.id' => $id]);
    }

    /**
     * @param int $userId
     * @param string $type
     * @return array
     */
    public function getFilterByUser(int $userId, string $type): array {
        $query = $this->find()
            ->where([
                'FilterBookmarks.filter_entity' => $type,
                'FilterBookmarks.user_id' => $userId
            ]);
        $result = $query->all();
        if (empty($result)) {
            return [];
        }
        return $result->toArray();
    }

    /**
     * @param int $userId
     * @param string $type
     * @return array|EntityInterface|null
     */
    public function getDefaultFilterByUser(int $userId , string $type) {
        $query = $this->find()
            ->where([
                'FilterBookmarks.filter_entity' => $type,
                'FilterBookmarks.user_id' => $userId,
                'FilterBookmarks.default' => true
            ])
            ->first();
        return $query;
    }

    /**
     * @param string $uuid
     * @return array|EntityInterface|null
     */
    public function getFilterByUuid(string $uuid) {
        $query = $this->find()
            ->where([
                //'FilterBookmarks.filter_entity' => 'host',
                'FilterBookmarks.uuid' => $uuid,
            ])
            ->first();
        return $query;
    }

}
