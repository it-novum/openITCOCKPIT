<?php
declare(strict_types=1);

namespace MapModule\Model\Entity;

use Cake\I18n\FrozenTime;
use Cake\ORM\Entity;

/**
 * Mapgadget Entity
 *
 * @property int $id
 * @property int $map_id
 * @property int $x
 * @property int $y
 * @property int $size_x
 * @property int $size_y
 * @property int|null $limit
 * @property string|null $gadget
 * @property string $type
 * @property int $object_id
 * @property int $transparent_background
 * @property int $show_label
 * @property int $font_size
 * @property int $z_index
 * @property string|null $metric
 * @property string|null $output_type
 * @property FrozenTime $created
 * @property FrozenTime $modified
 *
 * @property Map $map
 * @property Object $object
 */
class Mapgadget extends Entity {
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
        'map_id'                 => true,
        'x'                      => true,
        'y'                      => true,
        'size_x'                 => true,
        'size_y'                 => true,
        'limit'                  => true,
        'gadget'                 => true,
        'type'                   => true,
        'object_id'              => true,
        'transparent_background' => true,
        'show_label'             => true,
        'font_size'              => true,
        'z_index'                => true,
        'metric'                 => true,
        'output_type'            => true,
        'created'                => true,
        'modified'               => true,
        'map'                    => true,
        'object'                 => true,
    ];
}
