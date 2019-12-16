<?php
declare(strict_types=1);

namespace MapModule\Model\Entity;

use Cake\ORM\Entity;

/**
 * Mapicon Entity
 *
 * @property int $id
 * @property int $map_id
 * @property int $x
 * @property int $y
 * @property string $icon
 * @property int $z_index
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \MapModule\Model\Entity\Map $map
 */
class Mapicon extends Entity
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
        'map_id' => true,
        'x' => true,
        'y' => true,
        'icon' => true,
        'z_index' => true,
        'created' => true,
        'modified' => true,
        'map' => true,
    ];
}
