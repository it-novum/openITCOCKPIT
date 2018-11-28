<?php
namespace NewModule\Model\Entity;

use Cake\ORM\Entity;

/**
 * Servicecommandargumentvalue Entity
 *
 * @property int $id
 * @property int $commandargument_id
 * @property int $service_id
 * @property string $value
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \NewModule\Model\Entity\Commandargument $commandargument
 * @property \NewModule\Model\Entity\Service $service
 */
class Servicecommandargumentvalue extends Entity
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
        'commandargument_id' => true,
        'service_id' => true,
        'value' => true,
        'created' => true,
        'modified' => true,
        'commandargument' => true,
        'service' => true
    ];
}
