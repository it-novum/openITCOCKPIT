<?php
namespace MkModule\Model\Entity;

use Cake\ORM\Entity;

/**
 * Mkcheck Entity
 *
 * @property int $id
 * @property string $name
 * @property int|null $servicetemplate_id
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \MkModule\Model\Entity\Servicetemplate $servicetemplate
 */
class Mkcheck extends Entity
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
        'servicetemplate_id' => true,
        'created' => true,
        'modified' => true,
        'servicetemplate' => true
    ];
}
