<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Changelog Entity
 *
 * @property int $id
 * @property string $model
 * @property string $action
 * @property int $object_id
 * @property int|null $objecttype_id
 * @property int|null $user_id
 * @property string $data
 * @property string $name
 * @property \Cake\I18n\FrozenTime $created
 *
 * @property \App\Model\Entity\Object $object
 * @property \App\Model\Entity\Objecttype $objecttype
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\ChangelogsToContainer[] $changelogs_to_containers
 */
class Changelog extends Entity {
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
        'model'                    => true,
        'action'                   => true,
        'object_id'                => true,
        'objecttype_id'            => true,
        'user_id'                  => true,
        'data'                     => true,
        'name'                     => true,
        'created'                  => true,
        'object'                   => true,
        'objecttype'               => true,
        'user'                     => true,
        'changelogs_to_containers' => true
    ];
}
