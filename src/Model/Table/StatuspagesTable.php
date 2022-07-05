<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Lib\Traits\PaginationAndScrollIndexTrait;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use itnovum\openITCOCKPIT\Filter\StatuspagesFilter;

/**
 * Statuspages Model
 *
 * @property \App\Model\Table\StatuspagesToContainersTable&\Cake\ORM\Association\HasMany $StatuspagesToContainers
 * @property \App\Model\Table\StatuspagesToHostgroupsTable&\Cake\ORM\Association\HasMany $StatuspagesToHostgroups
 * @property \App\Model\Table\StatuspagesToHostsTable&\Cake\ORM\Association\HasMany $StatuspagesToHosts
 * @property \App\Model\Table\StatuspagesToServicegroupsTable&\Cake\ORM\Association\HasMany $StatuspagesToServicegroups
 * @property \App\Model\Table\StatuspagesToServicesTable&\Cake\ORM\Association\HasMany $StatuspagesToServices
 *
 * @method \App\Model\Entity\Statuspage newEmptyEntity()
 * @method \App\Model\Entity\Statuspage newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Statuspage[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Statuspage get($primaryKey, $options = [])
 * @method \App\Model\Entity\Statuspage findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Statuspage patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Statuspage[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Statuspage|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Statuspage saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Statuspage[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Statuspage[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Statuspage[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Statuspage[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class StatuspagesTable extends Table {
    use PaginationAndScrollIndexTrait;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void {
        parent::initialize($config);

        $this->setTable('statuspages');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->belongsToMany('Containers', [
            'className'        => 'Containers',
            'foreignKey'       => 'statuspage_id',
            'targetForeignKey' => 'container_id',
            'joinTable'        => 'statuspages_to_containers'
        ]);

        $this->belongsToMany('Hosts', [
            'className'        => 'Hosts',
            'foreignKey'       => 'statuspage_id',
            'targetForeignKey' => 'host_id',
            'joinTable'        => 'statuspages_to_hosts'
        ]);

        $this->belongsToMany('Services', [
            'className'        => 'Services',
            'foreignKey'       => 'statuspage_id',
            'targetForeignKey' => 'service_id',
            'joinTable'        => 'statuspages_to_services'
        ]);
        $this->belongsToMany('Hostgroups', [
            'className'        => 'Hostgroups',
            'foreignKey'       => 'statuspage_id',
            'targetForeignKey' => 'hostgroup_id',
            'joinTable'        => 'statuspages_to_hostgroups'
        ]);
        $this->belongsToMany('Servicegroups', [
            'className'        => 'Servicegroups',
            'foreignKey'       => 'statuspage_id',
            'targetForeignKey' => 'servicegroup_id',
            'joinTable'        => 'statuspages_to_servicegroups'
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
            ->requirePresence('description', 'create')
            ->notEmptyString('description');

        $validator
            ->boolean('public')
            ->notEmptyString('public');

        return $validator;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function existsById($id): bool {
        return $this->exists(['Statuspages.id' => $id]);
    }


    /**
     * @param StatuspagesFilter $StatuspagesFilter
     * @param $PaginateOMat
     * @param $MY_RIGHTS
     * @return array
     */
    public function getStatuspagesIndex(StatuspagesFilter $StatuspagesFilter, $PaginateOMat = null, $MY_RIGHTS = []) {
        $query = $this->find('all');
        $query->contain(['Containers']);
        $query->where($StatuspagesFilter->indexFilter());

        $query->innerJoinWith('Containers', function (Query $q) use ($MY_RIGHTS) {
            if (!empty($MY_RIGHTS)) {
                return $q->where(['Containers.id IN' => $MY_RIGHTS]);
            }
            return $q;
        });

        $query->distinct('Statuspages.id');

        $query->disableHydration();
        $query->order($StatuspagesFilter->getOrderForPaginator('Statuspages.name', 'asc'));

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
     * @param $id
     * @return array|void
     */
    public function getStatuspageObjects($id = null) {
        if (!$this->existsById($id)) {
            return;
        }

        $query = $this->find()
            ->contain('Hosts', function (Query $q) {
                return $q
                    ->select(['id', 'name']);
            })
            ->contain('Services', function (Query $q) {
                return $q
                    ->select([
                        'id',
                        'servicename' => $q->newExpr('IF(Services.name IS NULL, Servicetemplates.name, Services.name)'),
                    ])
                    ->innerJoin(['Servicetemplates' => 'servicetemplates'], [
                        'Servicetemplates.id = Services.servicetemplate_id'
                    ]);
            })
            ->contain('Hostgroups', function (Query $q) {
                return $q
                    ->select([
                        'id',
                        'Containers.name'
                    ])
                    ->innerJoin(['Containers' => 'containers'], [
                        'Containers.id = Hostgroups.container_id',
                        'Containers.containertype_id' => CT_HOSTGROUP
                    ]);
            })
            ->contain('Servicegroups', function (Query $q) {
                return $q
                    ->select([
                        'id',
                        'Containers.name'
                    ])
                    ->innerJoin(['Containers' => 'containers'], [
                        'Containers.id = Servicegroups.container_id',
                        'Containers.containertype_id' => CT_SERVICEGROUP
                    ]);
            })
            ->where([
                'Statuspages.id' => $id
            ])
            ->firstOrFail();
        $statuspage = $query->toArray();

        //debug($statuspage);
        return $statuspage;
    }

    public function getPublicStatuspages() {

    }
}
