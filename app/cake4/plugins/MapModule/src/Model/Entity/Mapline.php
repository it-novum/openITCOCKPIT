<?php
declare(strict_types=1);

namespace MapModule\Model\Entity;

use Cake\ORM\Entity;

/**
 * Mapline Entity
 *
 * @property int $id
 * @property int $map_id
 * @property int $startX
 * @property int $startY
 * @property int $endX
 * @property int $endY
 * @property int|null $limit
 * @property string|null $iconset
 * @property string $type
 * @property int|null $object_id
 * @property int $z_index
 * @property int $show_label
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \MapModule\Model\Entity\Map $map
 * @property \MapModule\Model\Entity\Object $object
 */
class Mapline extends Entity
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
        'startX' => true,
        'startY' => true,
        'endX' => true,
        'endY' => true,
        'limit' => true,
        'iconset' => true,
        'type' => true,
        'object_id' => true,
        'z_index' => true,
        'show_label' => true,
        'created' => true,
        'modified' => true,
        'map' => true,
        'object' => true,
    ];
}
