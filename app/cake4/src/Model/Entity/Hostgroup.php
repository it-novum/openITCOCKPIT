<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Hostgroup Entity
 *
 * @property int $id
 * @property string $uuid
 * @property int $container_id
 * @property string $description
 * @property string|null $hostgroup_url
 *
 * @property \App\Model\Entity\Container $container
 */
class Hostgroup extends Entity {

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
        'uuid'          => true,
        'container_id'  => true,
        'description'   => true,
        'hostgroup_url' => true,
        'container'     => true,
        'hosts'         => true,
        'hosttemplates' => true
    ];

    /**
     * @return array
     */
    /*public function getContainerIds() {
        $containerIds = [
            $this->hostgroup->container_id
        ];

        foreach ($this->hostgroup->hosts_to_containers_sharing as $container) {
            $containerIds[] = $container->get('id');
        }

        return array_unique($containerIds);
    }*/
}
