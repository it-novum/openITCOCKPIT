<?php

namespace App\Model\Table;

use App\Lib\Traits\CustomValidationTrait;
use App\Lib\Traits\PaginationAndScrollIndexTrait;
use App\Model\Entity\Agentconfig;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use itnovum\openITCOCKPIT\Core\FileDebugger;
use itnovum\openITCOCKPIT\Database\PaginateOMat;
use itnovum\openITCOCKPIT\Filter\AgentconfigsFilter;

/**
 * Agentconfigs Model
 *
 * @property \App\Model\Table\HostsTable|\Cake\ORM\Association\BelongsTo $Hosts
 *
 * @method \App\Model\Entity\Agentconfig get($primaryKey, $options = [])
 * @method \App\Model\Entity\Agentconfig newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Agentconfig[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Agentconfig|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Agentconfig saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Agentconfig patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Agentconfig[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Agentconfig findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 * @deprecated
 */
class AgentconfigsTable extends Table {

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

        $this->setTable('agentconfigs');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Hosts', [
            'foreignKey' => 'host_id'
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
            ->integer('port')
            ->notEmptyString('port', null, false);

        $validator
            ->boolean('use_https')
            ->notEmptyString('use_https');

        $validator
            ->boolean('insecure')
            ->notEmptyString('insecure');

        $validator
            ->boolean('basic_auth')
            ->notEmptyString('basic_auth');

        $validator
            ->allowEmptyString('username', __('Password can not be blank if basic auth is enabled.'), function ($context) {
                return $this->notEmptyIfBasicAuth(null, $context);
            })
            ->add('username', 'custom', [
                'rule'    => [$this, 'notEmptyIfBasicAuth'],
                'message' => __('Password can not be blank if basic auth is enabled.')
            ]);

        $validator
            ->allowEmptyString('password', __('Password can not be blank if basic auth is enabled.'), function ($context) {
                return $this->notEmptyIfBasicAuth(null, $context);
            })
            ->add('password', 'custom', [
                'rule'    => [$this, 'notEmptyIfBasicAuth'],
                'message' => __('Password can not be blank if basic auth is enabled.')
            ]);

        $validator
            ->boolean('push_noticed');

        return $validator;
    }

    public function notEmptyIfBasicAuth($value, $context) {
        if (!isset($context['data']['basic_auth'])) {
            //Basic auth missing in request
            return false;
        }

        if ($context['data']['basic_auth'] === 0 || $context['data']['basic_auth'] === false) {
            //Basic auth disabled - ok
            return true;
        }

        if (!isset($context['data']['username']) || !isset($context['data']['password'])) {
            //Username or password is not in request
            return false;
        }

        if (!empty($context['data']['username']) && !empty($context['data']['password'])) {
            //User name and password is not empty
            return true;
        }

        return false;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker {
        $rules->add($rules->existsIn(['host_id'], 'Hosts'));

        return $rules;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function existsById($id) {
        return $this->exists(['Agentconfigs.id' => $id]);
    }

    /**
     * @param int $hostId
     * @return bool
     */
    public function existsByHostId($hostId) {
        return $this->exists(['Agentconfigs.host_id' => $hostId]);
    }

    /**
     * @param $hostId
     * @return Agentconfig|\Cake\Datasource\EntityInterface
     */
    public function getConfigByHostId($hostId){
        return $this->find()
            ->where([
                'Agentconfigs.host_id' => $hostId
            ])
            ->firstOrFail();
    }

    /**
     * @param int $hostId
     * @param bool $defaultIfNoConfig
     * @return array|\Cake\Datasource\EntityInterface|null
     * @deprecated
     */
    public function getConfigByHostIdOld($hostId, $defaultIfNoConfig = true) {
        /** @var ProxiesTable $ProxiesTable */
        $ProxiesTable = TableRegistry::getTableLocator()->get('Proxies');
        $proxySettings = $ProxiesTable->getSettings();

        $isSystemsettingsProxyEnabled = false;
        if ($proxySettings['enabled']) {
            $isSystemsettingsProxyEnabled = true;
        }

        $default = [
            'port'         => 3333,
            'use_https'    => 0,
            'insecure'     => 1,
            'basic_auth'   => 0,
            'proxy'        => $isSystemsettingsProxyEnabled,
            'username'     => '',
            'password'     => '',
            'push_noticed' => 0
        ];

        $record = $this->find()
            ->where([
                'Agentconfigs.host_id' => $hostId
            ])
            ->first();

        if ($record !== null) {
            return [
                'id'           => (int)$record->get('id'),
                'host_id'      => (int)$record->get('host_id'),
                'port'         => (int)$record->get('port'),
                'use_https'    => (int)$record->get('use_https'),
                'insecure'     => (int)$record->get('insecure'),
                'basic_auth'   => (int)$record->get('basic_auth'),
                'proxy'        => $record->get('proxy'),
                'username'     => $record->get('username'),
                'password'     => $record->get('password'),
                'push_noticed' => (int)$record->get('push_noticed'),
            ];
        } else {
            if ($defaultIfNoConfig) {
                return $default;
            }
        }

        return $record;
    }

    /**
     * @param int $hostId
     * @return \App\Model\Entity\Agentconfig|array|\Cake\Datasource\EntityInterface|null
     * @deprecated
     */
    public function getConfigOrEmptyEntity($hostId) {
        $record = $this->find()
            ->where([
                'Agentconfigs.host_id' => $hostId
            ])
            ->first();

        if ($record === null) {
            return $this->newEmptyEntity();
        }

        return $record;
    }

    /**
     * @param $hostId
     * @return bool
     * @deprecated
     */
    public function pushNoticedForHost($hostId) {
        $query = $this->find()
            ->where([
                'host_id'      => $hostId,
                'push_noticed' => 1
            ])
            ->first();
        return !empty($query);
    }

    /**
     * @param $hostUuid
     * @param bool $pushNoticed
     * @deprecated
     */
    public function updatePushNoticedForHostIfConfigExists($hostUuid, $pushNoticed = true) {
        /** @var HostsTable $HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');

        try {
            $hostId = $HostsTable->getHostIdByUuid($hostUuid);

            $query = $this->find()
                ->where([
                    'host_id' => $hostId,
                ])
                ->firstOrFail();

            $query->set('push_noticed', (int)$pushNoticed);
            $this->save($query);

        } catch (\Exception $e) {
            //do nothing
        }
    }

    /**
     * @param AgentconfigsFilter $AgentconfigsFilter
     * @param PaginateOMat|null $PaginateOMat
     * @return array
     * @deprecated
     */
    public function getForList(AgentconfigsFilter $AgentconfigsFilter, PaginateOMat $PaginateOMat = null) {
        $query = $this->find('all')
            ->contain([
                'Hosts'
            ])
            ->where($AgentconfigsFilter->indexFilter())
            ->order($AgentconfigsFilter->getOrderForPaginator('Agentconfigs.id', 'desc'))
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
