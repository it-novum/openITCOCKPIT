<?php
declare(strict_types=1);

namespace ChangecalendarModule\Model\Table;

use App\itnovum\openITCOCKPIT\Filter\ChangecalendarsFilter;
use App\Lib\Traits\Cake2ResultTableTrait;
use App\Lib\Traits\PaginationAndScrollIndexTrait;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use itnovum\openITCOCKPIT\Database\PaginateOMat;
use itnovum\openITCOCKPIT\Filter\CalendarFilter;

/**
 * Changecalendars Model
 *
 * @property \ChangecalendarModule\Model\Table\ContainersTable&\Cake\ORM\Association\BelongsTo $Containers
 * @property \ChangecalendarModule\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @property \ChangecalendarModule\Model\Table\ChangecalendarEventsTable&\Cake\ORM\Association\HasMany $ChangecalendarEvents
 *
 * @method \ChangecalendarModule\Model\Entity\Changecalendar newEmptyEntity()
 * @method \ChangecalendarModule\Model\Entity\Changecalendar newEntity(array $data, array $options = [])
 * @method \ChangecalendarModule\Model\Entity\Changecalendar[] newEntities(array $data, array $options = [])
 * @method \ChangecalendarModule\Model\Entity\Changecalendar get($primaryKey, $options = [])
 * @method \ChangecalendarModule\Model\Entity\Changecalendar findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \ChangecalendarModule\Model\Entity\Changecalendar patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \ChangecalendarModule\Model\Entity\Changecalendar[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \ChangecalendarModule\Model\Entity\Changecalendar|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \ChangecalendarModule\Model\Entity\Changecalendar saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \ChangecalendarModule\Model\Entity\Changecalendar[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \ChangecalendarModule\Model\Entity\Changecalendar[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \ChangecalendarModule\Model\Entity\Changecalendar[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \ChangecalendarModule\Model\Entity\Changecalendar[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ChangecalendarsTable extends Table {

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

        $this->setTable('changecalendars');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Containers', [
            'foreignKey' => 'container_id',
            'joinType'   => 'INNER',
            'className'  => 'ChangecalendarModule.Containers',
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType'   => 'INNER',
            'className'  => 'ChangecalendarModule.Users',
        ]);
        $this->hasMany('ChangecalendarEvents', [
            'foreignKey' => 'changecalendar_id',
            'className'  => 'ChangecalendarModule.ChangecalendarEvents',
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
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->scalar('description')
            ->maxLength('description', 255)
            ->allowEmptyString('description');

        $validator
            ->scalar('colour')
            ->maxLength('colour', 7)
            ->allowEmptyString('colour');

        $validator
            ->integer('container_id')
            ->notEmptyString('container_id');

        $validator
            ->integer('user_id')
            ->notEmptyString('user_id');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker {
        $rules->add($rules->existsIn('container_id', 'Containers'), ['errorField' => 'container_id']);
        $rules->add($rules->existsIn('user_id', 'Users'), ['errorField' => 'user_id']);

        return $rules;
    }


    /**
     * @param ChangecalendarsFilter $CalendarFilter
     * @param PaginateOMat|null $PaginateOMat
     * @param array $MY_RIGHTS
     * @return array
     */
    public function getChangecalendarsIndex(ChangecalendarsFilter $CalendarFilter, $PaginateOMat = null, $MY_RIGHTS = []) {
        $query = $this->find();

        $where = $CalendarFilter->indexFilter();
        if (!empty($MY_RIGHTS)) {
            $where['Calendars.container_id IN'] = $MY_RIGHTS;
        }
        $query->where($where);

        $query->order($CalendarFilter->getOrderForPaginator('Changecalendars.name', 'asc'));

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

    /**
     * @param int $id
     * @return bool
     */
    public function existsById($id) {
        return $this->exists(['Changecalendars.id' => $id]);
    }

    public function getCalendarByIdForEdit($id) {
        $result = $this->find()
            ->contain([
                'ChangecalendarEvents'
            ])
            ->where([
                'Changecalendars.id' => $id
            ])
            ->disableHydration()
            ->firstOrFail();

        return $result;
    }
}
