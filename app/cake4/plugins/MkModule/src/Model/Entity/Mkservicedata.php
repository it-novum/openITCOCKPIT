<?php
namespace MkModule\Model\Entity;

use Cake\ORM\Entity;

/**
 * Mkservicedata Entity
 *
 * @property int $id
 * @property int $service_id
 * @property int $host_id
 * @property int $is_process
 * @property string $check_name
 * @property string $check_item
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \MkModule\Model\Entity\Service $service
 * @property \MkModule\Model\Entity\Host $host
 */
class Mkservicedata extends Entity
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
        'service_id' => true,
        'host_id' => true,
        'is_process' => true,
        'check_name' => true,
        'check_item' => true,
        'created' => true,
        'modified' => true,
        'service' => true,
        'host' => true
    ];
}
