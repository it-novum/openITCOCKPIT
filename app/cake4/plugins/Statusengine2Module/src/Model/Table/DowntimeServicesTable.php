<?php

namespace Statusengine2Module\Model\Table;

use App\Lib\Interfaces\DowntimehistoryServicesTableInterface;
use App\Lib\Traits\PaginationAndScrollIndexTrait;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use itnovum\openITCOCKPIT\Core\DowntimeServiceConditions;
use itnovum\openITCOCKPIT\Database\PaginateOMat;

/**
 * DowntimeServicesTable Model
 *
 * @link http://nagios.sourceforge.net/docs/ndoutils/NDOUtils_DB_Model.pdf
 *
 * @property \Statusengine2Module\Model\Table\ObjectsTable|\Cake\ORM\Association\BelongsTo $Objects
 *
 * @method \Statusengine2Module\Model\Entity\DowntimeService get($primaryKey, $options = [])
 * @method \Statusengine2Module\Model\Entity\DowntimeService newEntity($data = null, array $options = [])
 * @method \Statusengine2Module\Model\Entity\DowntimeService[] newEntities(array $data, array $options = [])
 * @method \Statusengine2Module\Model\Entity\DowntimeService|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Statusengine2Module\Model\Entity\DowntimeService saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Statusengine2Module\Model\Entity\DowntimeService patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Statusengine2Module\Model\Entity\DowntimeService[] patchEntities($entities, array $data, array $options = [])
 * @method \Statusengine2Module\Model\Entity\DowntimeService findOrCreate($search, callable $callback = null, $options = [])
 */
class DowntimeServicesTable extends Table implements DowntimehistoryServicesTableInterface {

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
     * @param DowntimeServiceConditions $DowntimeServiceConditions
     * @param PaginateOMat|null $PaginateOMat
     * @return array|void
     */
    public function getDowntimes(DowntimeServiceConditions $DowntimeServiceConditions, $PaginateOMat = null) {
        $query = $this->find();
        $query->select([
            'DowntimeServices.author_name',
            'DowntimeServices.comment_data',
            'DowntimeServices.entry_time',
            'DowntimeServices.scheduled_start_time',
            'DowntimeServices.scheduled_end_time',
            'DowntimeServices.duration',
            'DowntimeServices.was_started',
            'DowntimeServices.internal_downtime_id',
            'DowntimeServices.downtimehistory_id',
            'DowntimeServices.was_cancelled',

            'Services.id',
            'Services.uuid',
            'Services.name',
            'Services.servicetemplate_id',

            'Servicetemplates.id',
            'Servicetemplates.name',

            'Hosts.id',
            'Hosts.uuid',
            'Hosts.name',

            'HostsToContainers.container_id',

            'servicename' => $query->newExpr('IF(Services.name IS NULL, Servicetemplates.name, Services.name)'),
        ])
            ->innerJoin(
                ['Objects' => 'nagios_objects'],
                ['Objects.object_id = DowntimeServices.object_id', 'DowntimeServices.downtime_type = 1'] //Downtime.downtime_type = 1 Service downtime
            )
            ->innerJoin(
                ['Services' => 'services'],
                ['Services.uuid = Objects.name2']
            )
            ->innerJoin(
                ['Servicetemplates' => 'servicetemplates'],
                ['Servicetemplates.id = Services.servicetemplate_id']
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
                'DowntimeServices.scheduled_start_time >' => date('Y-m-d H:i:s', $DowntimeServiceConditions->getFrom()),
                'DowntimeServices.scheduled_start_time <' => date('Y-m-d H:i:s', $DowntimeServiceConditions->getTo()),
            ])
            ->order($DowntimeServiceConditions->getOrder())
            ->group('DowntimeServices.downtimehistory_id');


        if ($DowntimeServiceConditions->hasContainerIds()) {
            $query->andWhere([
                'HostsToContainers.container_id IN' => $DowntimeServiceConditions->getContainerIds()
            ]);
        }


        if ($DowntimeServiceConditions->hideExpired()) {
            $query->andWhere([
                'DowntimeServices.scheduled_end_time >' => date('Y-m-d H:i:s', time())
            ]);
        }

        if ($DowntimeServiceConditions->hasConditions()) {

            $where = $DowntimeServiceConditions->getConditions();
            $having = null;
            if (isset($where['servicename LIKE'])) {
                $having = [
                    'servicename LIKE' => $where['servicename LIKE']
                ];
                unset($where['servicename LIKE']);
            }

            if (!empty($where))
                $query->andWhere($where);

            if (!empty($having)) {
                $query->having($having);
            }
        }

        if ($DowntimeServiceConditions->isRunning()) {
            $query->andWhere([
                'DowntimeServices.scheduled_end_time >' => date('Y-m-d H:i:s', time()),
                'DowntimeServices.was_started'          => 1,
                'DowntimeServices.was_cancelled'        => 0
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
