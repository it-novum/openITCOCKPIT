<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Systemsetting Entity
 *
 * @property int $id
 * @property string $key
 * @property string $value
 * @property string $info
 * @property string $section
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 */
class Systemsetting extends Entity
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
        'key' => true,
        'value' => true,
        'info' => true,
        'section' => true,
        'created' => true,
        'modified' => true
    ];
}
