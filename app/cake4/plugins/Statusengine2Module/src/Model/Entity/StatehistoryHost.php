<?php

namespace Statusengine2Module\Model\Entity;

use Cake\ORM\Entity;

/**
 * NagiosStatehistory Entity
 *
 * @property int $statehistory_id
 * @property int $instance_id
 * @property \Cake\I18n\FrozenTime $state_time
 * @property int $state_time_usec
 * @property int $object_id
 * @property int $state_change
 * @property int $state
 * @property int $state_type
 * @property int $current_check_attempt
 * @property int $max_check_attempts
 * @property int $last_state
 * @property int $last_hard_state
 * @property string|null $output
 * @property string|null $long_output
 *
 * @property \Statusengine2Module\Model\Entity\StatehistoryHost $statehistory
 * @property \Statusengine2Module\Model\Entity\ObjectEntity $object
 */
class StatehistoryHost extends Entity {
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
        'instance_id'           => true,
        'state_time_usec'       => true,
        'object_id'             => true,
        'state_change'          => true,
        'state'                 => true,
        'state_type'            => true,
        'current_check_attempt' => true,
        'max_check_attempts'    => true,
        'last_state'            => true,
        'last_hard_state'       => true,
        'output'                => true,
        'long_output'           => true,
        'statehistory'          => true,
        'instance'              => true,
        'object'                => true
    ];
}
