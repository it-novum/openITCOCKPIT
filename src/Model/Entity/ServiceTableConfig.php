<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ServiceTableConfig Entity
 *
 * @property int $id
 * @property int $user_id
 * @property int $custom_state
 * @property int $custom_acknowledgement
 * @property int $custom_indowntime
 * @property int $custom_grapher
 * @property int $custom_passive
 * @property int $custom_priority
 * @property int $custom_servicename
 * @property int $custom_last_change
 * @property int $custom_last_check
 * @property int $custom_next_check
 * @property int $custom_service_output
 * @property int $custom_instance
 * @property int $custom_description
 * @property int $custom_container_name
 *
 * @property \App\Model\Entity\User $user
 */
class ServiceTableConfig extends Entity
{
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
        'user_id' => true,
        'custom_state' => true,
        'custom_acknowledgement' => true,
        'custom_indowntime' => true,
        'custom_grapher' => true,
        'custom_passive' => true,
        'custom_priority' => true,
        'custom_servicename' => true,
        'custom_last_change' => true,
        'custom_last_check' => true,
        'custom_next_check' => true,
        'custom_service_output' => true,
        'custom_instance' => true,
        'custom_description' => true,
        'custom_container_name' => true,
        'user' => true,
    ];
}
