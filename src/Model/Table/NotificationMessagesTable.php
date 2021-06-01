<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Lib\Traits\Cake2ResultTableTrait;
use App\Lib\Traits\PaginationAndScrollIndexTrait;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use itnovum\openITCOCKPIT\Database\PaginateOMat;
use itnovum\openITCOCKPIT\Filter\MessagesFilter;

/**
 * NotificationMessages Model
 *
 * @method \App\Model\Entity\NotificationMessage newEmptyEntity()
 * @method \App\Model\Entity\NotificationMessage newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\NotificationMessage[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\NotificationMessage get($primaryKey, $options = [])
 * @method \App\Model\Entity\NotificationMessage findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\NotificationMessage patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\NotificationMessage[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\NotificationMessage|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\NotificationMessage saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\NotificationMessage[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\NotificationMessage[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\NotificationMessage[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\NotificationMessage[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class NotificationMessagesTable extends Table {
    use Cake2ResultTableTrait;
    use PaginationAndScrollIndexTrait;
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void {
        parent::initialize($config);

        $this->setTable('notification_messages');
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
            ->scalar('name')
            ->maxLength('name', 255)
            ->allowEmptyString('name', null, false);

        $validator
            ->scalar('message')
            ->maxLength('message', 255)
            ->allowEmptyString('message', null, false);

        $validator
            ->scalar('date')
            ->maxLength('date', 255)
            ->allowEmptyString('date', null, false);

        $validator
            ->scalar('time')
            ->maxLength('time', 255)
            ->allowEmptyString('time', null, false);

        return $validator;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function existsById($id) {
        return $this->exists(['NotificationMessages.id' => $id]);
    }


    /**
     * @param MessagesFilter $MessagesFilter
     * @param null|PaginateOMat $PaginateOMat
     * @return array
     */
    public function showAllMessages(MessagesFilter $MessagesFilter, $PaginateOMat = null) {
        $query = $this->find('all');
        $query->where($MessagesFilter->indexFilter());
        $query->order(['id' => 'DESC']);

        $query->disableHydration();
        if ($PaginateOMat === null) {
            //Just execute query
            $result = $this->formatResultAsCake2($query->toArray(), false);
        } else {
            if ($PaginateOMat->useScroll()) {
                $result = $this->scroll($query, $PaginateOMat->getHandler(), false);
            } else {
                $result = $this->paginate($query, $PaginateOMat->getHandler(), false);
            }
        }

        return $result;
    }

    /**
     * @return array
     */
    public function messagesForWidget() {
        $query = $this->find('all');
        $result = $query->select(['message'])->where(['date' => date('d.m.Y')])->order(['id' => 'DESC']);
        return $result->toArray();
    }


}
