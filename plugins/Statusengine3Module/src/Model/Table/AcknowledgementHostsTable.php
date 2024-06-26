<?php
// Copyright (C) <2015>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//	This program is free software: you can redistribute it and/or modify
//	it under the terms of the GNU General Public License as published by
//	the Free Software Foundation, version 3 of the License.
//
//	This program is distributed in the hope that it will be useful,
//	but WITHOUT ANY WARRANTY; without even the implied warranty of
//	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//	GNU General Public License for more details.
//
//	You should have received a copy of the GNU General Public License
//	along with this program.  If not, see <http://www.gnu.org/licenses/>.
//

// 2.
//	If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//	under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//	License agreement and license key will be shipped with the order
//	confirmation.

declare(strict_types=1);

namespace Statusengine3Module\Model\Table;

use App\Lib\Interfaces\AcknowledgementHostsTableInterface;
use App\Lib\Traits\PaginationAndScrollIndexTrait;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use itnovum\openITCOCKPIT\Core\AcknowledgedHostConditions;
use itnovum\openITCOCKPIT\Database\PaginateOMat;
use Statusengine3Module\Model\Entity\AcknowledgementHost;

/**
 * AcknowledgementHostsTable Model
 *
 * @method \Statusengine3Module\Model\Entity\AcknowledgementHost newEmptyEntity()
 * @method \Statusengine3Module\Model\Entity\AcknowledgementHost newEntity(array $data, array $options = [])
 * @method \Statusengine3Module\Model\Entity\AcknowledgementHost[] newEntities(array $data, array $options = [])
 * @method \Statusengine3Module\Model\Entity\AcknowledgementHost get($primaryKey, $options = [])
 * @method \Statusengine3Module\Model\Entity\AcknowledgementHost findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \Statusengine3Module\Model\Entity\AcknowledgementHost patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Statusengine3Module\Model\Entity\AcknowledgementHost[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \Statusengine3Module\Model\Entity\AcknowledgementHost|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Statusengine3Module\Model\Entity\AcknowledgementHost saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Statusengine3Module\Model\Entity\AcknowledgementHost[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \Statusengine3Module\Model\Entity\AcknowledgementHost[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \Statusengine3Module\Model\Entity\AcknowledgementHost[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \Statusengine3Module\Model\Entity\AcknowledgementHost[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class AcknowledgementHostsTable extends Table implements AcknowledgementHostsTableInterface {

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
    public function initialize(array $config): void {
        parent::initialize($config);

        $this->setTable('statusengine_host_acknowledgements');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator {
        //Readonly table
        return $validator;
    }

    /**
     * @param string $uuid
     * @return array|AcknowledgementHost|null
     */
    public function byUuid($uuid) {
        return $this->byHostUuid($uuid);
    }

    /**
     * @param null $uuid
     * @return array|AcknowledgementHost|null
     */
    public function byHostUuid($uuid = null) {
        $query = $this->find()
            ->where([
                'hostname' => $uuid
            ])
            ->order([
                'entry_time' => 'DESC',
            ])
            ->first();

        return $query;
    }

    /**
     * @param AcknowledgedHostConditions $AcknowledgedHostConditions
     * @param PaginateOMat|null $PaginateOMat
     * @return array
     */
    public function getAcknowledgements(AcknowledgedHostConditions $AcknowledgedHostConditions, $PaginateOMat = null) {
        $query = $this->find()
            ->where([
                'hostname'     => $AcknowledgedHostConditions->getHostUuid(),
                'entry_time >' => $AcknowledgedHostConditions->getFrom(),
                'entry_time <' => $AcknowledgedHostConditions->getTo()
            ])
            ->order($AcknowledgedHostConditions->getOrder());

        if ($AcknowledgedHostConditions->hasConditions()) {
            $query->andWhere($AcknowledgedHostConditions->getConditions());
        }

        if (!empty($AcknowledgedHostConditions->getStates())) {
            $query->andWhere([
                'state IN' => $AcknowledgedHostConditions->getStates()
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

    /**
     * @param $uuids
     * @return array
     */
    public function byUuids($uuids) {
        if(empty($uuids)){
            return [];
        }

        if (!is_array($uuids)) {
            $uuids = [$uuids];
        }

        $query = $this->find()
            ->where([
                'hostname IN ' => $uuids
            ])
            ->order([
                'entry_time' => 'DESC',
            ])
            ->disableHydration()
            ->all();

        $acks = $query->toArray();

        $result = [];
        foreach ($acks as $ack) {
            if(!isset($result[$ack['hostname']])) {
                $result[$ack['hostname']] = $ack;
            }
        }

        return $result;
    }
}
