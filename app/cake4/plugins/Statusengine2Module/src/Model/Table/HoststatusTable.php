<?php

namespace Statusengine2Module\Model\Table;

use App\Lib\Exceptions\InvalidArgumentException;
use App\Lib\Interfaces\HoststatusTableInterface;
use App\Lib\Traits\Cake2ResultTableTrait;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use itnovum\openITCOCKPIT\Core\HoststatusConditions;
use itnovum\openITCOCKPIT\Core\HoststatusFields;

/**
 * Hoststatus Model
 *
 * Bake command: bin/cake bake model -p Statusengine2Module Hoststatus
 *
 * @property \Statusengine2Module\Model\Table\ObjectsTable|\Cake\ORM\Association\BelongsTo $Object
 *
 * @method \Statusengine2Module\Model\Entity\Hoststatus get($primaryKey, $options = [])
 * @method \Statusengine2Module\Model\Entity\Hoststatus newEntity($data = null, array $options = [])
 * @method \Statusengine2Module\Model\Entity\Hoststatus[] newEntities(array $data, array $options = [])
 * @method \Statusengine2Module\Model\Entity\Hoststatus|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Statusengine2Module\Model\Entity\Hoststatus|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Statusengine2Module\Model\Entity\Hoststatus patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Statusengine2Module\Model\Entity\Hoststatus[] patchEntities($entities, array $data, array $options = [])
 * @method \Statusengine2Module\Model\Entity\Hoststatus findOrCreate($search, callable $callback = null, $options = [])
 */
class HoststatusTable extends Table implements HoststatusTableInterface {

    use Cake2ResultTableTrait;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->setTable('nagios_hoststatus');
        $this->setDisplayField('hoststatus_id');
        $this->setPrimaryKey('hoststatus_id');

        $this->belongsTo('Objects', [
            'foreignKey' => 'host_object_id',
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
     * @param null|string|array $uuid
     * @param HoststatusFields $HoststatusFields
     * @param HoststatusConditions|null $HoststatusConditions
     * @return array
     */
    public function byUuidMagic($uuid, HoststatusFields $HoststatusFields, $HoststatusConditions = null) {
        if ($uuid === null || empty($uuid)) {
            return [];
        }

        $select = $HoststatusFields->getFields();

        $query = $this->find()
            ->select($select)
            ->contain([
                'Objects' => [
                    'fields' => [
                        'Objects.name1'
                    ]
                ]
            ]);

        $where = [];
        if ($HoststatusConditions !== null) {
            if ($HoststatusConditions->hasConditions()) {
                $where = $HoststatusConditions->getConditions();
            }
        }

        $where['Objects.objecttype_id'] = 1;
        $findType = 'all';
        if (is_array($uuid)) {
            $where['Objects.name1 IN'] = $uuid;
        } else {
            $where['Objects.name1'] = $uuid;
            $findType = 'first';
        }

        $query->where($where);
        $query->disableHydration();

        if ($findType === 'all') {
            $result = $query->all();
            $result = $this->formatResultAsCake2($result->toArray());

            $return = [];
            foreach ($result as $record) {
                $return[$record['Hoststatus']['object']['name1']] = $record;
            }

            return $return;
        }

        if ($findType === 'first') {
            $result = $query->first();
            return $this->formatFirstResultAsCake2($result);
        }

        return [];
    }

    /**
     * @param $uuid
     * @param HoststatusFields $HoststatusFields
     * @param HoststatusConditions|null $HoststatusConditions
     * @return array|string
     */
    public function byUuid($uuid, HoststatusFields $HoststatusFields, $HoststatusConditions = null) {
        return $this->byUuidMagic($uuid, $HoststatusFields, $HoststatusConditions);
    }


    /**
     * @param $uuids
     * @param HoststatusFields $HoststatusFields
     * @param HoststatusConditions|null $HoststatusConditions
     * @throws \App\Lib\Exceptions\InvalidArgumentException
     * @return array
     */
    public function byUuids($uuids, HoststatusFields $HoststatusFields, $HoststatusConditions = null) {
        if (!is_array($uuids)) {
            throw new InvalidArgumentException('$uuids need to be an array!');
        }
        return $this->byUuidMagic($uuids, $HoststatusFields, $HoststatusConditions);
    }

}
