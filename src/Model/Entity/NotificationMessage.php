<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * NotificationMessage Entity
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $message
 * @property string|null $date
 * @property string|null $time
 * @property \Cake\I18n\FrozenTime $created
 */
class NotificationMessage extends Entity
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
        'name' => true,
        'message' => true,
        'date' => true,
        'time' => true,
        'created' => true,
    ];
}
