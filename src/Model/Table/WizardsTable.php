<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;

/**
 * Wizards Model
 *
 *
 * @method \App\Model\Entity\Wizard get($primaryKey, $options = [])
 * @method \App\Model\Entity\Wizard newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Wizard[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Wizard|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Wizard saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Wizard patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Wizard[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Wizard findOrCreate($search, callable $callback = null, $options = [])
 *
 */
class WizardsTable extends Table {

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
                'type_id'     => 1,
                'title'       => __('Linux (SSH)'),
                'description' => __('Monitoring via Secure Shell (SSH) enables you to gather performance and system data from many Linux and Unix distributions'),
                'image'       => 'linux.svg',
                'directive'   => 'linux-ssh', //AngularJS directive
                'category'    => ['linux']
            ],
            [
                'type_id'     => 2,
                'title'       => __('Linux (SNMP)'),
                'description' => __('Monitoring Linux devices via SNMP'),
                'image'       => 'linux.svg',
                'directive'   => 'linux-snmp', //AngularJS directive
                'category'    => ['linux']
            ],
            [
                'type_id'     => 3,
                'title'       => __('Windows (SNMP)'),
                'description' => __('Linux Monitoring from a Windows device using SNMP'),
                'image'       => 'Windows.svg',
                'directive'   => 'windows-snmp', //AngularJS directive
                'category'    => ['windows']
            ],
            [
                'type_id'     => 4,
                'title'       => __('Windows (NSClient++)'),
                'description' => __('NSClient++ (nscp) aims to be a simple yet powerful and flexible monitoring daemon'),
                'image'       => 'nsclient-logo-300x75.png',
                'directive'   => 'windows-nsclient', //AngularJS directive
                'category'    => ['windows']
            ],
            [
                'type_id'     => 5,
                'title'       => __('Mysql'),
                'description' => __('Track MySQL Query Throughput, Execution Performance, Connections, And Buffer Pool Usage'),
                'image'       => 'MySQL_logo.svg',
                'directive'   => 'mysql', //AngularJS directive
                'category'    => ['linux', 'mysql']
            ],
            [
                'type_id'     => 6,
                'title'       => __('Docker'),
                'description' => __('Instantly monitor & troubleshoot issues within containers'),
                'image'       => 'docker.png',
                'directive'   => 'docker', //AngularJS directive
                'category'    => ['linux', 'docker']
            ]
        ];

        return $wizards;
    }

}
