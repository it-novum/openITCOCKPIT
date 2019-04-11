<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Servicegroup Entity
 *
 * @property int $id
 * @property string $uuid
 * @property int $container_id
 * @property string $description
 * @property string|null $servicegroup_url
 *
 * @property \App\Model\Entity\Container $container
 * @property \App\Model\Entity\ServicegroupsToServicedependency[] $servicegroups_to_servicedependencies
 * @property \App\Model\Entity\ServicegroupsToServiceescalation[] $servicegroups_to_serviceescalations
 * @property \App\Model\Entity\ServicesToServicegroup[] $services_to_servicegroups
 * @property \App\Model\Entity\Servicetemplate[] $servicetemplates
 */
class Servicegroup extends Entity {
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'uuid'                                 => true,
        'container_id'                         => true,
        'description'                          => true,
        'servicegroup_url'                     => true,
        'container'                            => true,
        'instantreports_to_servicegroups'      => true,
        'nagios_servicegroup_members'          => true,
        'nagios_servicegroups'                 => true,
        'servicegroups_to_servicedependencies' => true,
        'servicegroups_to_serviceescalations'  => true,
        'services_to_servicegroups'            => true,
        'servicetemplates'                     => true
    ];
}
