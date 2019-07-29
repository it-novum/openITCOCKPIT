<?php

namespace Statusengine2Module\Model\Table;

use App\Lib\Interfaces\DowntimehistoryHostsTableInterface;
use App\Lib\Traits\PaginationAndScrollIndexTrait;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use itnovum\openITCOCKPIT\Core\DowntimeHostConditions;
use itnovum\openITCOCKPIT\Database\PaginateOMat;

/**
 * DowntimeHostsTable Model
 *
 * @link http://nagios.sourceforge.net/docs/ndoutils/NDOUtils_DB_Model.pdf
 *
 * @property \Statusengine2Module\Model\Table\ObjectsTable|\Cake\ORM\Association\BelongsTo $Objects
 *
 * @method \Statusengine2Module\Model\Entity\DowntimeHost get($primaryKey, $options = [])
 * @method \Statusengine2Module\Model\Entity\DowntimeHost newEntity($data = null, array $options = [])
 * @method \Statusengine2Module\Model\Entity\DowntimeHost[] newEntities(array $data, array $options = [])
 * @method \Statusengine2Module\Model\Entity\DowntimeHost|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Statusengine2Module\Model\Entity\DowntimeHost saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Statusengine2Module\Model\Entity\DowntimeHost patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Statusengine2Module\Model\Entity\DowntimeHost[] patchEntities($entities, array $data, array $options = [])
 * @method \Statusengine2Module\Model\Entity\DowntimeHost findOrCreate($search, callable $callback = null, $options = [])
 */
class DowntimeHostsTable extends Table implements DowntimehistoryHostsTableInterface {

    /*****************************************************/
    /*                         !!!                       */
    /*           If you add a method to this table       */
    /*   define it in the implemented interface first!   */
    /*                         !!!                       */
    /*****************************************************/

    use PaginationAndScrollIndexTrait;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->setTable('nagios_downtimehistory');
        $this->setDisplayField('downtimehistory_id');
        $this->setPrimaryKey('downtimehistory_id');

        $this->belongsTo('Objects', [
            'foreignKey' => 'object_id',
            'joinType'   => 'INNER',
            'className'  => 'Statusengine2Module.Objects'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator) {
        //Readonly table
        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules) {
        //Readonly table
        return $rules;
    }

    /**
     * @param DowntimeHostConditions $DowntimeHostConditions
     * @param PaginateOMat|null $PaginateOMat
     * @return array|void
     */
    public function getDowntimes(DowntimeHostConditions $DowntimeHostConditions, $PaginateOMat = null) {
        $query = $this->find()
            ->select([
                'DowntimeHosts.author_name',
                'DowntimeHosts.comment_data',
                'DowntimeHosts.entry_time',
                'DowntimeHosts.scheduled_start_time',
                'DowntimeHosts.scheduled_end_time',
                'DowntimeHosts.duration',
                'DowntimeHosts.was_started',
                'DowntimeHosts.internal_downtime_id',
                'DowntimeHosts.downtimehistory_id',
                'DowntimeHosts.was_cancelled',

                'Hosts.id',
                'Hosts.uuid',
                'Hosts.name',

                'HostsToContainers.container_id',
            ])
            ->innerJoin(
                ['Objects' => 'nagios_objects'],
                ['Objects.object_id = DowntimeHosts.object_id', 'DowntimeHosts.downtime_type = 2'] //Downtime.downtime_type = 2 Host downtime
            )
            ->innerJoin(
                ['Hosts' => 'hosts'],
                ['Objects.name1 = Hosts.uuid']
            )
            ->leftJoin(
                ['HostsToContainers' => 'hosts_to_containers'],
                ['HostsToContainers.host_id = Hosts.id']
            )
            ->where([
                'DowntimeHosts.scheduled_start_time >' => date('Y-m-d H:i:s', $DowntimeHostConditions->getFrom()),
                'DowntimeHosts.scheduled_start_time <' => date('Y-m-d H:i:s', $DowntimeHostConditions->getTo()),
            ])
            ->order($DowntimeHostConditions->getOrder())
            ->group('DowntimeHosts.downtimehistory_id');


        if ($DowntimeHostConditions->hasContainerIds()) {
            $query->andWhere([
                'HostsToContainers.container_id IN' => $DowntimeHostConditions->getContainerIds()
            ]);
        }


        if ($DowntimeHostConditions->hideExpired()) {
            $query->andWhere([
                'DowntimeHosts.scheduled_end_time >' => date('Y-m-d H:i:s', time())
            ]);
        }

        if ($DowntimeHostConditions->hasConditions()) {
            $query->andWhere($DowntimeHostConditions->getConditions());
        }

        if ($DowntimeHostConditions->isRunning()) {
            $query->andWhere([
                'DowntimeHosts.scheduled_end_time >' => date('Y-m-d H:i:s', time()),
                'DowntimeHosts.was_started'          => 1,
                'DowntimeHosts.was_cancelled'        => 0
            ]);
        }

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
