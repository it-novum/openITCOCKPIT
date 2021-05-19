<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * DynamicTableConfig Entity
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $json_data
 * @property string|null $table_name
 *
 * @property \App\Model\Entity\User $user
 */
class DynamicTableConfig extends Entity
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
        'json_data' => true,
        'table_name' => true,
        'user' => true,
    ];
}
