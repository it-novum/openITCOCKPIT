<?php
declare(strict_types=1);

namespace MapModule\Model\Entity;

use Cake\ORM\Entity;

/**
 * Rotation Entity
 *
 * @property int $id
 * @property string $name
 * @property int $interval
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \MapModule\Model\Entity\MapsToRotation[] $maps_to_rotations
 * @property \MapModule\Model\Entity\RotationsToContainer[] $rotations_to_containers
 */
class Rotation extends Entity
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
        'interval' => true,
        'created' => true,
        'modified' => true,
        'maps_to_rotations' => true,
        'rotations_to_containers' => true,
    ];
}
