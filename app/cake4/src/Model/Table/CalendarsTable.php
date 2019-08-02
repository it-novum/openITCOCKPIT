<?php

namespace App\Model\Table;

use App\Lib\Traits\Cake2ResultTableTrait;
use App\Lib\Traits\PaginationAndScrollIndexTrait;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use itnovum\openITCOCKPIT\Filter\CalendarFilter;

/**
 * Commands Model
 **
 * @method \App\Model\Entity\Calendar get($primaryKey, $options = [])
 * @method \App\Model\Entity\Calendar newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Calendar[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Calendar|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Calendar|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Calendar patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Calendar[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Calendar findOrCreate($search, callable $callback = null, $options = [])
 */
class CalendarsTable extends Table {

    use Cake2ResultTableTrait;
    use PaginationAndScrollIndexTrait;


    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->setTable('calendars');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany('CalendarHolidays', [
            'foreignKey'   => 'calendar_id',
            'saveStrategy' => 'replace'
        ])->setDependent(true);

    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator) {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->allowEmptyString('name', false)
            ->add('name', 'unique', [
                'rule'     => 'validateUnique',
                'provider' => 'table',
                'message'  => __('This command name has already been taken.')
            ]);

        $validator
            ->scalar('container_id')
            ->allowEmptyString('container_id', null, false)
            ->greaterThan('container_id', 0);

        $validator
            ->scalar('description')
            ->allowEmptyString('description');

        return $validator;
    }

    /**
     * @param CalendarFilter $CalendarFilter
     * @param null $PaginateOMat
     * @param array $MY_RIGHTS
     * @return array
     */
    public function getCalendarsIndex(CalendarFilter $CalendarFilter, $PaginateOMat = null, $MY_RIGHTS = []) {
        $query = $this->find();

        $where = $CalendarFilter->indexFilter();
        if (!empty($MY_RIGHTS)) {
            $where['Calendars.container_id IN'] = $MY_RIGHTS;
        }
        $query->where($where);

        $query->order($CalendarFilter->getOrderForPaginator('Calendars.name', 'asc'));

        if ($PaginateOMat === null) {
            //Just execute query
            $result = $this->emptyArrayIfNull($query->toArray());
        } else {
            if ($PaginateOMat->useScroll()) {
                $result = $this->scrollCake4($query, $PaginateOMat->getHandler());
            } else {
                $result = $this->paginateCake4($query, $PaginateOMat->getHandler());
            }
        }

        return $result;
    }
}
