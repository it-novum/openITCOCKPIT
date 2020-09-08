<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * WizardAssignment Entity
 *
 * @property int $id
 * @property int type_id
 * @property string $uuid
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Servicetemplate[] $servicetemplates
 * class WizardAssignment extends Entity {
 */
class WizardAssignment extends Entity {
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected
        $_accessible = [
        'type_id'          => true,
        'uuid'             => true,
        'servicetemplates' => true
    ];
}
