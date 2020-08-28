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
                'type_id'   => 1,
                'title'     => __('Linux (SSH)'),
                'image'     => 'fas fa-comment',
                'directive' => 'linux-ssh' //AngularJS directive
            ],
            [
                'type_id'   => 2,
                'title'     => __('Linux (SNMP)'),
                'image'     => 'fas fa-comment',
                'directive' => 'linux-snmp' //AngularJS directive
            ],
            [
                'type_id'   => 3,
                'title'     => __('Windows (SNMP)'),
                'image'     => 'fas fa-comment',
                'directive' => 'windows-snmp' //AngularJS directive
            ],
            [
                'type_id'   => 4,
                'title'     => __('Windows (NSClient++)'),
                'image'     => 'fas fa-comment',
                'directive' => 'windows-nsclient' //AngularJS directive
            ],
            [
                'type_id'   => 5,
                'title'     => __('Mysql'),
                'image'     => 'fas fa-comment',
                'directive' => 'mysql' //AngularJS directive
            ],
            [
                'type_id'   => 6,
                'title'     => __('Docker'),
                'image'     => 'fas fa-comment',
                'directive' => 'docker' //AngularJS directive
            ]
        ];

        return $wizards;
    }

}
