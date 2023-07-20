<?php
declare(strict_types=1);

namespace ChangecalendarModule\Model\Entity;

use Cake\ORM\Entity;

/**
 * ChangecalendarEvent Entity
 *
 * @property int $id
 * @property string $name
 * @property \Cake\I18n\FrozenTime $begin
 * @property \Cake\I18n\FrozenTime $end
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property int $changecalendar_id
 * @property int $user_id
 * @property string|null $uid
 * @property array|null $context
 * @property string|null $description
 *
 * @property \ChangecalendarModule\Model\Entity\Changecalendar $changecalendar
 * @property \ChangecalendarModule\Model\Entity\User $user
 */
class ChangecalendarEvent extends Entity
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
        'begin' => true,
        'end' => true,
        'created' => true,
        'modified' => true,
        'changecalendar_id' => true,
        'user_id' => true,
        'uid' => true,
        'context' => true,
        'description' => true,
        'changecalendar' => true,
        'user' => true,
    ];
}
