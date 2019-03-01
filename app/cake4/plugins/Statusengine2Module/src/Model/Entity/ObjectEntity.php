<?php

namespace Statusengine2Module\Model\Entity;

use Cake\ORM\Entity;

/**
 * ObjectEntity Entity
 *
 * @property int $object_id
 * @property int $instance_id
 * @property int $objecttype_id
 * @property string $name1
 * @property string|null $name2
 * @property int $is_active
 *
 */
class ObjectEntity extends Entity {

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
        'instance_id'   => false,
        'objecttype_id' => false,
        'name1'         => false,
        'name2'         => false,
        'is_active'     => false
    ];
}
