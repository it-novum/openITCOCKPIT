<?php

namespace App\Model\Table;

use Acl\Model\Table\AcosTable;
use App\Lib\Traits\Cake2ResultTableTrait;
use App\Lib\Traits\PaginationAndScrollIndexTrait;
use Cake\Core\Configure;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use itnovum\openITCOCKPIT\Database\PaginateOMat;
use itnovum\openITCOCKPIT\Filter\GenericFilter;

/**
 * Usergroups Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\HasMany $Users
 *
 * @method \App\Model\Entity\Usergroup get($primaryKey, $options = [])
 * @method \App\Model\Entity\Usergroup newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Usergroup[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Usergroup|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Usergroup|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Usergroup patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Usergroup[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Usergroup findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UsergroupsTable extends Table {
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

        $this->setTable('usergroups');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->addBehavior('Acl.Acl', ['requester']);

        $this->hasMany('Users', [
            'foreignKey' => 'usergroup_id'
        ]);

        $this->hasOne('Aros', [
            'className'  => 'Acl.Aros',
            'foreignKey' => 'foreign_key',
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
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->allowEmptyString('name', null, false)
            ->add('name', 'unique', [
                'rule'     => 'validateUnique',
                'provider' => 'table',
                'message'  => __('This user role name has already been taken.')
            ]);

        $validator
            ->scalar('description')
            ->maxLength('description', 255)
            ->allowEmptyString('description');

        return $validator;
    }

    /**
     * @return array
     */
    public function getUsergroupsList() {
        $query = $this->find('list');
        return $query->toArray();
    }

    /**
     * @param int $id
     * @return bool
     */
    public function existsById($id) {
        return $this->exists(['Usergroups.id' => $id]);
    }

    /**
     * @param PaginateOMat|null $PaginateOMat
     * @param GenericFilter $GenericFilter
     * @return array
     */
    public function getUsergroups($PaginateOMat, GenericFilter $GenericFilter) {
        $query = $this->find()
            ->order($GenericFilter->getOrderForPaginator('Usergroups.name', 'asc'))
            ->disableHydration();


        if (!empty($GenericFilter->genericFilters())) {
            $query->where($GenericFilter->genericFilters());
        }

        if ($PaginateOMat === null) {
            //Just execute query
            $result = $this->formatResultAsCake2($query->toArray(), false);
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
     * @return array|\Cake\Datasource\EntityInterface|null
     */
    public function getUsergroupById($id) {
        $query = $this->find('all')
            ->disableHydration()
            ->where([
                'id' => $id
            ]);
        if (is_null($query)) {
            return [];
        }
        return $query->first();
    }

    /**
     * @param bool $useAcoIdAsKey
     * @return array
     */
    public function getAllAcosAsList($useAcoIdAsKey = true) {
        /** @var AcosTable $AcosTable */
        $AcosTable = TableRegistry::getTableLocator()->get('Acl.Acos');

        $acosAsList = [];
        $acos = $AcosTable->find('threaded')
            ->disableHydration()
            ->all();

        $acos = $acos->toArray();

        foreach ($acos[0]['children'] as $controller) {
            if (substr($controller['alias'], -6) === 'Module') {
                $module = $controller;
                foreach ($module['children'] as $moduleController) {
                    foreach ($moduleController['children'] as $moduleAction) {
                        if ($useAcoIdAsKey === true) {
                            $acosAsList[$moduleAction['id']] = $module['alias'] . '/' . $moduleController['alias'] . '/' . $moduleAction['alias'];
                        } else {
                            $key = $module['alias'] . '/' . $moduleController['alias'] . '/' . $moduleAction['alias'];
                            $acosAsList[$key] = $moduleAction['id'];
                        }
                    }
                }
            } else {
                //Core Controller
                foreach ($controller['children'] as $action) {
                    if ($useAcoIdAsKey === true) {
                        $acosAsList[$action['id']] = $controller['alias'] . '/' . $action['alias'];
                    } else {
                        $key = $controller['alias'] . '/' . $action['alias'];
                        $acosAsList[$key] = $action['id'];
                    }
                }
            }
        }

        return $acosAsList;
    }
}
