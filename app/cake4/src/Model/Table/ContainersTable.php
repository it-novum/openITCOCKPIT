<?php

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Containers Model
 *
 * @property \App\Model\Table\ContainertypesTable|\Cake\ORM\Association\BelongsTo $Containertypes
 * @property \App\Model\Table\ContainersTable|\Cake\ORM\Association\BelongsTo $ParentContainers
 * @property \App\Model\Table\AutomapsTable|\Cake\ORM\Association\HasMany $Automaps
 * @property \App\Model\Table\AutoreportsTable|\Cake\ORM\Association\HasMany $Autoreports
 * @property \App\Model\Table\CalendarsTable|\Cake\ORM\Association\HasMany $Calendars
 * @property \App\Model\Table\ChangelogsToContainersTable|\Cake\ORM\Association\HasMany $ChangelogsToContainers
 * @property \App\Model\Table\ContactgroupsTable|\Cake\ORM\Association\HasMany $Contactgroups
 * @property \App\Model\Table\ContactsToContainersTable|\Cake\ORM\Association\HasMany $ContactsToContainers
 * @property \App\Model\Table\ContainersTable|\Cake\ORM\Association\HasMany $ChildContainers
 * @property \App\Model\Table\GrafanaUserdashboardsTable|\Cake\ORM\Association\HasMany $GrafanaUserdashboards
 * @property \App\Model\Table\HostdependenciesTable|\Cake\ORM\Association\HasMany $Hostdependencies
 * @property \App\Model\Table\HostescalationsTable|\Cake\ORM\Association\HasMany $Hostescalations
 * @property \App\Model\Table\HostgroupsTable|\Cake\ORM\Association\HasMany $Hostgroups
 * @property \App\Model\Table\HostsTable|\Cake\ORM\Association\HasMany $Hosts
 * @property \App\Model\Table\HostsToContainersTable|\Cake\ORM\Association\HasMany $HostsToContainers
 * @property \App\Model\Table\HosttemplatesTable|\Cake\ORM\Association\HasMany $Hosttemplates
 * @property \App\Model\Table\IdoitObjectsTable|\Cake\ORM\Association\HasMany $IdoitObjects
 * @property \App\Model\Table\IdoitObjecttypesTable|\Cake\ORM\Association\HasMany $IdoitObjecttypes
 * @property \App\Model\Table\InstantreportsTable|\Cake\ORM\Association\HasMany $Instantreports
 * @property \App\Model\Table\LocationsTable|\Cake\ORM\Association\HasMany $Locations
 * @property \App\Model\Table\MapUploadsTable|\Cake\ORM\Association\HasMany $MapUploads
 * @property \App\Model\Table\MapsToContainersTable|\Cake\ORM\Association\HasMany $MapsToContainers
 * @property \App\Model\Table\MkagentsTable|\Cake\ORM\Association\HasMany $Mkagents
 * @property \App\Model\Table\NmapConfigurationsTable|\Cake\ORM\Association\HasMany $NmapConfigurations
 * @property \App\Model\Table\RotationsToContainersTable|\Cake\ORM\Association\HasMany $RotationsToContainers
 * @property \App\Model\Table\SatellitesTable|\Cake\ORM\Association\HasMany $Satellites
 * @property \App\Model\Table\ServicedependenciesTable|\Cake\ORM\Association\HasMany $Servicedependencies
 * @property \App\Model\Table\ServiceescalationsTable|\Cake\ORM\Association\HasMany $Serviceescalations
 * @property \App\Model\Table\ServicegroupsTable|\Cake\ORM\Association\HasMany $Servicegroups
 * @property \App\Model\Table\ServicetemplategroupsTable|\Cake\ORM\Association\HasMany $Servicetemplategroups
 * @property \App\Model\Table\ServicetemplatesTable|\Cake\ORM\Association\HasMany $Servicetemplates
 * @property \App\Model\Table\TenantsTable|\Cake\ORM\Association\HasMany $Tenants
 * @property \App\Model\Table\TimeperiodsTable|\Cake\ORM\Association\HasMany $Timeperiods
 * @property \App\Model\Table\UsersToContainersTable|\Cake\ORM\Association\HasMany $UsersToContainers
 *
 * @method \App\Model\Entity\Container get($primaryKey, $options = [])
 * @method \App\Model\Entity\Container newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Container[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Container|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Container|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Container patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Container[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Container findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TreeBehavior
 */
class ContainersTable extends Table {

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->setTable('containers');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Tree');

        //$this->belongsTo('ParentContainers', [
        //    'className' => 'Containers',
        //    'foreignKey' => 'parent_id'
        //]);

        /*
        $this->hasMany('Automaps', [
            'foreignKey' => 'container_id'
        ]);
        $this->hasMany('Autoreports', [
            'foreignKey' => 'container_id'
        ]);
        $this->hasMany('Calendars', [
            'foreignKey' => 'container_id'
        ]);
        $this->hasMany('ChangelogsToContainers', [
            'foreignKey' => 'container_id'
        ]);
        $this->hasMany('Contactgroups', [
            'foreignKey' => 'container_id'
        ]);
        $this->hasMany('ContactsToContainers', [
            'foreignKey' => 'container_id'
        ]);
        $this->hasMany('ChildContainers', [
            'className' => 'Containers',
            'foreignKey' => 'parent_id'
        ]);
        $this->hasMany('GrafanaUserdashboards', [
            'foreignKey' => 'container_id'
        ]);
        $this->hasMany('Hostdependencies', [
            'foreignKey' => 'container_id'
        ]);
        $this->hasMany('Hostescalations', [
            'foreignKey' => 'container_id'
        ]);
        $this->hasMany('Hostgroups', [
            'foreignKey' => 'container_id'
        ]);
        $this->hasMany('Hosts', [
            'foreignKey' => 'container_id'
        ]);
        $this->hasMany('HostsToContainers', [
            'foreignKey' => 'container_id'
        ]);
        $this->hasMany('Hosttemplates', [
            'foreignKey' => 'container_id'
        ]);
        $this->hasMany('IdoitObjects', [
            'foreignKey' => 'container_id'
        ]);
        $this->hasMany('IdoitObjecttypes', [
            'foreignKey' => 'container_id'
        ]);
        $this->hasMany('Instantreports', [
            'foreignKey' => 'container_id'
        ]);
        $this->hasMany('Locations', [
            'foreignKey' => 'container_id'
        ]);
        $this->hasMany('MapUploads', [
            'foreignKey' => 'container_id'
        ]);
        $this->hasMany('MapsToContainers', [
            'foreignKey' => 'container_id'
        ]);
        $this->hasMany('Mkagents', [
            'foreignKey' => 'container_id'
        ]);
        $this->hasMany('NmapConfigurations', [
            'foreignKey' => 'container_id'
        ]);
        $this->hasMany('RotationsToContainers', [
            'foreignKey' => 'container_id'
        ]);
        $this->hasMany('Satellites', [
            'foreignKey' => 'container_id'
        ]);
        $this->hasMany('Servicedependencies', [
            'foreignKey' => 'container_id'
        ]);
        $this->hasMany('Serviceescalations', [
            'foreignKey' => 'container_id'
        ]);
        $this->hasMany('Servicegroups', [
            'foreignKey' => 'container_id'
        ]);
        $this->hasMany('Servicetemplategroups', [
            'foreignKey' => 'container_id'
        ]);
        $this->hasMany('Servicetemplates', [
            'foreignKey' => 'container_id'
        ]);
        $this->hasMany('Tenants', [
            'foreignKey' => 'container_id'
        ]);
        $this->hasMany('Timeperiods', [
            'foreignKey' => 'container_id'
        ]);
        $this->hasMany('UsersToContainers', [
            'foreignKey' => 'container_id'
        ]);
        */
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator) {
        $validator
            ->integer('id')
            ->allowEmptyString('id', 'create');

        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->allowEmptyString('name', false);

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
        $rules->add($rules->existsIn(['parent_id'], 'ParentContainers'));

        return $rules;
    }
}
