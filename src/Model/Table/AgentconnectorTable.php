<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Lib\Traits\Cake2ResultTableTrait;
use App\Lib\Traits\CustomValidationTrait;
use App\Lib\Traits\PaginationAndScrollIndexTrait;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use itnovum\openITCOCKPIT\Database\PaginateOMat;
use itnovum\openITCOCKPIT\Filter\AgentconnectorAgentsFilter;

/**
 * Agentconnector Model
 *
 * @method \App\Model\Entity\Agentconnector get($primaryKey, $options = [])
 * @method \App\Model\Entity\Agentconnector newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Agentconnector[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Agentconnector|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Agentconnector saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Agentconnector patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Agentconnector[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Agentconnector findOrCreate($search, callable $callback = null, $options = [])
 */
class AgentconnectorTable extends Table {

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

        $this->setTable('agentconnector');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
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
            ->allowEmptyString('checksum');

        $validator
            ->allowEmptyString('ca_checksum');

        $validator
            ->allowEmptyString('generation_date');

        $validator
            ->scalar('remote_addr')
            ->maxLength('remote_addr', 255)
            ->allowEmptyString('remote_addr');

        $validator
            ->scalar('http_x_forwarded_for')
            ->maxLength('http_x_forwarded_for', 255)
            ->allowEmptyString('http_x_forwarded_for');

        $validator
            ->boolean('trusted')
            ->notEmptyString('trusted');

        return $validator;
    }

    /**
     * @param $id
     * @return bool
     */
    public function existsById($id) {
        return $this->exists(['Agentconnector.id' => $id]);
    }

    /**
     * @param string $uuid
     * @return bool
     */
    public function existsByHostuuid($uuid) {
        return $this->exists(['Agentconnector.hostuuid' => $uuid]);
    }

    /**
     * @param AgentconnectorAgentsFilter $AgentconnectorAgentsFilter
     * @param PaginateOMat|null $PaginateOMat
     * @return array
     */
    public function getAgentsIndex(AgentconnectorAgentsFilter $AgentconnectorAgentsFilter, PaginateOMat $PaginateOMat = null) {
        $query = $this->find('all')
            ->where($AgentconnectorAgentsFilter->indexFilter())
            ->order($AgentconnectorAgentsFilter->getOrderForPaginator('Agentconnector.id', 'desc'))
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

    public function getByHostUuid(string $hostuuid) {
        $query = $this->find()
            ->where([
                'hostuuid' => $hostuuid,
            ])->first();
        return $query;
    }

    /**
     * @param string $checksum
     * @param string $hostuuid
     * @return bool
     */
    public function trustIsValid(string $checksum, string $hostuuid) {
        $query = $this->find()
            ->where([
                'checksum' => $checksum,
                'hostuuid' => $hostuuid,
                'trusted'  => 1
            ])->first();
        return (!empty($query));
    }

    /**
     * @param string $hostuuid
     * @return bool
     */
    public function isTrustedFromUser(string $hostuuid) {
        $query = $this->find()
            ->where([
                'hostuuid' => $hostuuid,
                'trusted'  => 1
            ])->first();
        return (!empty($query));
    }

    /**
     * @param string $hostuuid
     * @return bool
     */
    public function certificateNotYetGenerated(string $hostuuid) {
        return $this->exists(['hostuuid' => $hostuuid, 'checksum IS' => null]);
    }
}
