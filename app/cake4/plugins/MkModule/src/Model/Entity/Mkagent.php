<?php
namespace MkModule\Model\Entity;

use Cake\ORM\Entity;

/**
 * Mkagent Entity
 *
 * @property int $id
 * @property int $container_id
 * @property string $name
 * @property string $description
 * @property string $command_line
 *
 * @property \MkModule\Model\Entity\Container $container
 */
class Mkagent extends Entity
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
        'container_id' => true,
        'name' => true,
        'description' => true,
        'command_line' => true,
        'container' => true
    ];
}
