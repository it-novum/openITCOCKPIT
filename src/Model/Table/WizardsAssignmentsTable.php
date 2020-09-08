<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Lib\PluginManager;
use Cake\ORM\Table;
use itnovum\openITCOCKPIT\Core\Wizards\ModuleWizardsInterface;

/**
 * Wizards Model
 *
 *
 * @method \App\Model\Entity\WizardAssignment get($primaryKey, $options = [])
 * @method \App\Model\Entity\WizardAssignment newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\WizardAssignment[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\WizardAssignment|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\WizardAssignment saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\WizardAssignment patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\WizardAssignment[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\WizardAssignment findOrCreate($search, callable $callback = null, $options = [])
 *
 */
class WizardsAssignmentsTable extends Table {

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void {
        parent::initialize($config);
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
                'title'                   => __('Mysql'),
                'description'             => __('Track MySQL Query Throughput, Execution Performance, Connections, And Buffer Pool Usage'),
                'image'                   => 'MySQL_logo.svg',
                'directive'               => 'mysql', //AngularJS directive
                'category'                => ['linux', 'mysql'],
                'necessity_of_assignment' => true
            ],
            [
                'type_id'                 => 'docker',
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
        return $this->exists(['WizardAssignments.uuid' => $uuid, 'WizardAssignments.typeId' => $typeId]);
    }
}
