<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * TableConfig Entity
 *
 * @property int $id
 * @property int $user_id
 * @property int $custom_last_change
 * @property int $custom_last_check
 * @property int $custom_host_output
 * @property int $custom_instance
 * @property int $custom_service_summery
 *
 * @property \App\Model\Entity\User $user
 */
class TableConfig extends Entity
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
        'custom_last_change' => true,
        'custom_last_check' => true,
        'custom_host_output' => true,
        'custom_instance' => true,
        'custom_service_summery' => true,
        'user' => true,
    ];
}
