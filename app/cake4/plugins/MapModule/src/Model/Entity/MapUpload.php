<?php
declare(strict_types=1);

namespace MapModule\Model\Entity;

use Cake\I18n\FrozenTime;
use Cake\ORM\Entity;

/**
 * MapUpload Entity
 *
 * @property int $id
 * @property int|null $upload_type
 * @property string $upload_name
 * @property string $saved_name
 * @property int|null $user_id
 * @property int|null $container_id
 * @property FrozenTime $created
 *
 * @property User $user
 * @property Container $container
 */
class MapUpload extends Entity {
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
        'upload_type'  => true,
        'upload_name'  => true,
        'saved_name'   => true,
        'user_id'      => true,
        'container_id' => true,
        'created'      => true,
        'user'         => true,
        'container'    => true,
    ];
}
