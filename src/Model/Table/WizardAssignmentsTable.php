<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Lib\PluginManager;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Utility\Hash;
use Cake\Validation\Validator;
use itnovum\openITCOCKPIT\Core\Wizards\ModuleWizardsInterface;

/**
 * WizardAssignments Model
 *
 * @property \App\Model\Table\ServicetemplatesToWizardAssignmentsTable&\Cake\ORM\Association\HasMany $ServicetemplatesToWizardAssignments
 *
 * @method \App\Model\Entity\WizardAssignment newEmptyEntity()
 * @method \App\Model\Entity\WizardAssignment newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\WizardAssignment[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\WizardAssignment get($primaryKey, $options = [])
 * @method \App\Model\Entity\WizardAssignment findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\WizardAssignment patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\WizardAssignment[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\WizardAssignment|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\WizardAssignment saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\WizardAssignment[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\WizardAssignment[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\WizardAssignment[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\WizardAssignment[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class WizardAssignmentsTable extends Table {
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void {
        parent::initialize($config);

        $this->setTable('wizard_assignments');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsToMany('Servicetemplates', [
            'className'        => 'Servicetemplates',
            'foreignKey'       => 'wizard_assignment_id',
            'targetForeignKey' => 'servicetemplate_id',
            'joinTable'        => 'servicetemplates_to_wizard_assignments',
            'saveStrategy'     => 'replace'
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
            ->scalar('uuid')
            ->maxLength('uuid', 37)
            ->requirePresence('uuid', 'create')
            ->notEmptyString('uuid')
            ->add('uuid', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

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
        $rules->add($rules->isUnique(['uuid']), ['errorField' => 'uuid']);
        return $rules;
    }

    /**
     * @param array $ACL_PERMISSIONS
     * @return array
     */
    public function getAvailableWizards($ACL_PERMISSIONS = []) {
        // Core Wizards
        if (!(isset($ACL_PERMISSIONS['hosts']['add']) && isset($ACL_PERMISSIONS['services']['add']))) {
            return [];
        }
        $wizards = [
            [
                'type_id'                 => 'linux-server-agent',
                'uuid'                    => '4bdca652-cf5c-4b85-ab4f-d75695ec725c',
                'title'                   => __('Linux Server'),
                'description'             => __('Monitoring for your Linux Server with openITCOCKPIT Agent'),
                'image'                   => 'linux.svg',
                'directive'               => 'linux-server', //AngularJS directive
                'category'                => ['linux'],
                'state'                   => 'AgentconnectorsConfig',
                'selected_os'             => 'linux',
                'necessity_of_assignment' => false

            ],
            [
                'type_id'                 => 'linux-server-ssh',
                'uuid'                    => 'b5b31647-6095-444b-ad05-22aac099ee45',
                'title'                   => __('Linux (SSH)'),
                'description'             => __('Monitoring via Secure Shell (SSH) enables you to gather performance and system data from many Linux and Unix distributions'),
                'image'                   => 'linux.svg',
                'directive'               => 'linux-ssh', //AngularJS directive
                'category'                => ['linux'],
                'state'                   => 'WizardsLinuxServerSsh',
                'selected_os'             => 'linux',
                'necessity_of_assignment' => true
            ],
            [
                'type_id'                 => 'linux-server-snmp',
                'uuid'                    => '2f172d07-ba32-4b9b-b67c-4c296bc5d14b',
                'title'                   => __('Linux (SNMP)'),
                'description'             => __('Monitoring Linux devices via SNMP'),
                'image'                   => 'linux.svg',
                'directive'               => 'linux-snmp', //AngularJS directive
                'category'                => ['linux'],
                'state'                   => 'WizardsLinuxServerSnmp',
                'selected_os'             => 'linux',
                'necessity_of_assignment' => true
            ],
            [
                'type_id'                 => 'windows-server-agent',
                'uuid'                    => 'e6b36290-9543-4aae-a074-09ff455bc8c9',
                'title'                   => __('Windows Server'),
                'description'             => __('Monitoring for your Windows Server with openITCOCKPIT Agent'),
                'image'                   => 'Windows.svg',
                'directive'               => 'windows', //AngularJS directive
                'category'                => ['windows'],
                'state'                   => 'AgentconnectorsConfig',
                'selected_os'             => 'windows',
                'necessity_of_assignment' => false
            ],
            [
                'type_id'                 => 'windows-server-snmp',
                'uuid'                    => '6b076d6d-296b-4959-a4e8-9ab242ae513f',
                'title'                   => __('Windows (SNMP)'),
                'description'             => __('Monitoring Windows server with SNMP'),
                'image'                   => 'Windows.svg',
                'directive'               => 'windows-snmp', //AngularJS directive
                'category'                => ['windows'],
                'state'                   => 'WizardsWindowsServerSnmp',
                'selected_os'             => 'windows',
                'necessity_of_assignment' => true
            ],
            [
                'type_id'                 => 'windows-server-nsclient',
                'uuid'                    => '9fc2cd53-9f44-4f78-b0a1-2b40b1161009',
                'title'                   => __('Windows (NSClient++)'),
                'description'             => __('NSClient++ (nscp) aims to be a simple yet powerful and flexible monitoring daemon'),
                'image'                   => 'nsclient-logo-300x75.png',
                'directive'               => 'windows-nsclient', //AngularJS directive
                'category'                => ['windows'],
                'state'                   => 'WizardsWindowsServerNSClient',
                'selected_os'             => 'windows',
                'necessity_of_assignment' => true
            ],
            [
                'type_id'                 => 'macos-server',
                'uuid'                    => '784c4ee7-9d79-4cf3-b491-fffba497d175',
                'title'                   => __('macOS Server'),
                'description'             => __('Monitoring for your macOS Server with openITCOCKPIT Agent'),
                'image'                   => 'MacOS-Logo.svg',
                'directive'               => 'macos', //AngularJS directive
                'category'                => ['macos'],
                'state'                   => 'AgentconnectorsConfig',
                'selected_os'             => 'macos',
                'necessity_of_assignment' => false
            ],
            [
                'type_id'                 => 'mysql-server',
                'uuid'                    => '7fb02fac-1ac5-43cf-baf2-b5893f9f9aa8',
                'title'                   => __('Mysql'),
                'description'             => __('Track MySQL Query Throughput, Execution Performance, Connections, And Buffer Pool Usage'),
                'image'                   => 'MySQL_logo.svg',
                'directive'               => 'mysql', //AngularJS directive
                'category'                => ['linux', 'mysql'],
                'necessity_of_assignment' => true
            ],
            [
                'type_id'                 => 'docker',
                'uuid'                    => '18bf7126-2015-483f-882a-ca4bd55888da',
                'title'                   => __('Docker'),
                'description'             => __('Instantly monitor & troubleshoot issues within containers'),
                'image'                   => 'docker.png',
                'directive'               => 'docker', //AngularJS directive
                'category'                => ['linux', 'docker'],
                'necessity_of_assignment' => true
            ]
        ];
        $modules = PluginManager::getAvailablePlugins();
        foreach ($modules as $module) {
            $className = sprintf('\\%s\\Lib\\Wizards', $module);
            if (class_exists($className)) {

                /** @var ModuleWizardsInterface $PluginWizards */
                $PluginWizards = new $className($ACL_PERMISSIONS);

                foreach ($PluginWizards->getAvailableWizards() as $pluginWizard) {
                    $wizards[] = $pluginWizard;
                }
            }
        }

        return $wizards;
    }

    /**
     * @param $uuid
     * @param $typeId
     * @return bool
     */
    public function existsByUuidAndTypeId($uuid, $typeId) {
        return $this->exists(['WizardAssignments.uuid' => $uuid, 'WizardAssignments.type_id' => $typeId]);
    }

    /**
     * @param $uuid
     * @return array|\Cake\Datasource\EntityInterface|null
     */
    public function getWizardByUuidForEdit($uuid) {
        $query = $this->find()
            ->where(['WizardAssignments.uuid' => $uuid])
            ->contain([
                'Servicetemplates' => function (Query $query) {
                    $query->enableAutoFields(false)
                        ->select([
                            'Servicetemplates.id'
                        ]);
                    return $query;
                }
            ])
            ->disableHydration()
            ->first();


        $wizardAssignment = $query;
        $wizardAssignment['servicetemplates'] = [
            '_ids' => Hash::extract($query, 'servicetemplates.{n}.id')
        ];
        return $wizardAssignment;
    }
}
