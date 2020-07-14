<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Lib\Traits\Cake2ResultTableTrait;
use App\Lib\Traits\CustomValidationTrait;
use App\Lib\Traits\PaginationAndScrollIndexTrait;
use Cake\I18n\FrozenTime;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use itnovum\openITCOCKPIT\Database\PaginateOMat;
use itnovum\openITCOCKPIT\Filter\AgenthostscacheFilter;

/**
 * Agenthostscache Model
 *
 * @method \App\Model\Entity\Agenthostscache newEmptyEntity()
 * @method \App\Model\Entity\Agenthostscache newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Agenthostscache[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Agenthostscache get($primaryKey, $options = [])
 * @method \App\Model\Entity\Agenthostscache findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Agenthostscache patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Agenthostscache[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Agenthostscache|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Agenthostscache saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Agenthostscache[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Agenthostscache[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Agenthostscache[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Agenthostscache[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AgenthostscacheTable extends Table {

    use Cake2ResultTableTrait;
    use PaginationAndScrollIndexTrait;
    use CustomValidationTrait;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void {
        parent::initialize($config);

        $this->setTable('agenthostscache');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Hosts', [
            'foreignKey' => 'hostuuid',
            'bindingKey' => 'uuid'
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
            ->scalar('hostuuid')
            ->maxLength('hostuuid', 255)
            ->requirePresence('hostuuid', 'create')
            ->notEmptyString('hostuuid');

        $validator
            ->scalar('checkdata')
            ->allowEmptyString('checkdata');

        return $validator;
    }

    /**
     * @param string $uuid
     * @return bool
     */
    public function existsByHostuuid($uuid) {
        return $this->exists(['hostuuid' => $uuid]);
    }

    /**
     * @param $id
     * @return bool
     */
    public function existsById($id) {
        return $this->exists(['id' => $id]);
    }

    /**
     * @param string $uuid
     * @return array|\Cake\Datasource\EntityInterface|null
     */
    public function getByHostUuid($uuid) {
        $query = $this->find()
            ->where([
                'hostuuid' => $uuid,
            ])->first();
        return $query;
    }

    /**
     * @param string $hostuuid
     * @param string $checkdata
     */
    public function saveCacheData(string $hostuuid, string $checkdata) {
        if ($this->existsByHostuuid($hostuuid)) {
            $Agenthostscache = $this->getByHostUuid($hostuuid);
            $Agenthostscache = $this->patchEntity($Agenthostscache, ['checkdata' => $checkdata, 'modified' => FrozenTime::now()]);
        } else {
            $Agenthostscache = $this->newEntity([
                'hostuuid'  => $hostuuid,
                'checkdata' => $checkdata,
                'modified'  => FrozenTime::now(),
                'created'   => FrozenTime::now()
            ]);
        }
        $this->save($Agenthostscache);
    }

    public function getForList(AgenthostscacheFilter $AgenthostscacheFilter, PaginateOMat $PaginateOMat = null) {
        $query = $this->find('all')
            ->contain([
                'Hosts'
            ])
            ->where($AgenthostscacheFilter->indexFilter())
            ->order($AgenthostscacheFilter->getOrderForPaginator('Agenthostscache.id', 'desc'))
            ->disableHydration();

        if ($PaginateOMat === null) {
            //Just execute query
            if (empty($query)) {
                return [];
            }
            $result = $query->toArray();
        } else {
            if ($PaginateOMat->useScroll()) {
                $result = $this->scroll($query, $PaginateOMat->getHandler(), false);
            } else {
                $result = $this->paginate($query, $PaginateOMat->getHandler(), false);
            }
        }

        return $result;
    }
}
