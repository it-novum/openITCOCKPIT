<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Lib\Traits\Cake2ResultTableTrait;
use App\Lib\Traits\CustomValidationTrait;
use App\Lib\Traits\PaginationAndScrollIndexTrait;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\I18n\FrozenTime;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use itnovum\openITCOCKPIT\Agent\AgentCertificateData;
use itnovum\openITCOCKPIT\Core\FileDebugger;
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

    /**
     * @param string $hostuuid
     * @return array|\Cake\Datasource\EntityInterface|null
     */
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
     * @param array $hostuuid
     * @return bool
     */
    public function isTrustedFromUserAndSaveAgentconnectorIfMissing(array $request) {
        $hostUuid = $request['hostuuid'];
        $checksum = $request['checksum'];

        try {
            $query = $this->find()
                ->where([
                    'hostuuid' => $hostUuid,
                    'trusted'  => 1
                ])->firstOrFail();
            return (!empty($query));
        } catch (RecordNotFoundException $e) {
            //No agent connector config found. Store the checksum of the agent cert into the database

            $result = $this->find()
                ->where([
                    'hostuuid' => $hostUuid,
                ])->first();

            if(empty($result)){
                //No record for given agent

                $AgentCertificateData = new AgentCertificateData();

                $record = $this->newEntity([
                    //'hostuuid'             => $hostUuid,
                    //'checksum'             => $checksum,
                    //'ca_checksum'          => $AgentCertificateData->getCaChecksum(),
                    //'generation_date'      => FrozenTime::now(),
                    //'remote_addr'          => $_SERVER['REMOTE_ADDR'] ?? null,
                    //'http_x_forwarded_for' => $_SERVER['HTTP_X_FORWARDED_FOR'] ?? null,
                    //'trusted'              => 0,

                    'hostuuid'             => $hostUuid,
                    'checksum'             => null,
                    'ca_checksum'          => null,
                    'generation_date'      => null,
                    'remote_addr'          => $_SERVER['REMOTE_ADDR'] ?? null,
                    'http_x_forwarded_for' => $_SERVER['HTTP_X_FORWARDED_FOR'] ?? null,
                    'trusted'              => 0
                ]);

                $this->save($record);
            }

            //Agent is not trusted yet
            return false;
        }
    }


    /**
     * @param string $hostuuid
     * @return bool
     */
    public function certificateNotYetGenerated(string $hostuuid) {
        return $this->exists(['hostuuid' => $hostuuid, 'checksum IS' => null]);
    }

    /**
     * @param string $hostuuid
     * @param null $remote_addr
     * @param null $http_x_forwarded_for
     * @return bool
     */
    public function addAgent(string $hostuuid, $remote_addr = null, $http_x_forwarded_for = null) {
        $AgentConnectionEntity = $this->newEntity([
            'hostuuid'             => $hostuuid,
            'checksum'             => null,
            'ca_checksum'          => null,
            'generation_date'      => null,
            'remote_addr'          => $remote_addr,
            'http_x_forwarded_for' => $http_x_forwarded_for,
            'trusted'              => 0
        ]);
        $this->save($AgentConnectionEntity);

        return $AgentConnectionEntity->hasErrors();
    }
}
