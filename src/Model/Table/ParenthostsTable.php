<?php

namespace App\Model\Table;

use App\Lib\Traits\PaginationAndScrollIndexTrait;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

/**
 * Class ParenthostsTable
 * @package App\Model\Table
 */
class ParenthostsTable extends Table {

    use PaginationAndScrollIndexTrait;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void {
        parent::initialize($config);

        $this->setTable('hosts_to_parenthosts');
        $this->setPrimaryKey('id');

        $this->belongsToMany('Hosts', [
            'joinTable' => 'hosts_to_parenthosts'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator {
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
        return $rules;
    }

    /**
     * @param array $containerIds
     * @param array $where
     * @return array
     */
    public function getParenthostsForDashboard($containerIds = [], $where = []) {
        if (!is_array($containerIds)) {
            $containerIds = [$containerIds];
        }

        $joins = [
            [
                'table'      => 'hosts',
                'type'       => 'INNER',
                'alias'      => 'Hosts',
                'conditions' => 'Parenthosts.parenthost_id = Hosts.id'
            ]
        ];
        if (!empty($containerIds)) {
            $joins[] = [
                'table'      => 'hosts_to_containers',
                'alias'      => 'HostsToContainers',
                'type'       => 'LEFT',
                'conditions' => [
                    'HostsToContainers.host_id = Hosts.id',
                ],
            ];
            $query['conditions'][''] = $containerIds;
        }


        $query = $this->find()
            ->select([
                'Hosts.id',
                'Hosts.uuid',
                'Hosts.name',
                //'Parenthosts.parenthost_id'
            ])
            ->distinct('Hosts.uuid')
            ->join($joins);


        if (!empty($where)) {
            $query->where($where);
        }

        if (!empty($containerIds)) {
            $query->andWhere([
                'HostsToContainers.container_id IN' => $containerIds
            ]);
        }

        $query->disableHydration();
        $query->all();

        return $query->toArray();
    }
}
