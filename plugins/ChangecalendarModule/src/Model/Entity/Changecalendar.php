<?php
declare(strict_types=1);

namespace ChangecalendarModule\Model\Entity;

use Cake\ORM\Entity;

/**
 * Changecalendar Entity
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string|null $colour
 * @property int $container_id
 * @property int $user_id
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \ChangecalendarModule\Model\Entity\Container $container
 * @property \ChangecalendarModule\Model\Entity\User $user
 * @property \ChangecalendarModule\Model\Entity\ChangecalendarEvent[] $changecalendar_events
 */
class Changecalendar extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected $_accessible = [
        'name' => true,
        'description' => true,
        'colour' => true,
        'container_id' => true,
        'user_id' => true,
        'created' => true,
        'modified' => true,
        'container' => true,
        'user' => true,
        'changecalendar_events' => true,
    ];
}
