<?php

namespace Statusengine2Module\Model\Entity;

use Cake\ORM\Entity;

/**
 * NagiosHostcheck Entity
 *
 * @property int $hostcheck_id
 * @property int $instance_id
 * @property int $host_object_id
 * @property int $check_type
 * @property int $is_raw_check
 * @property int $current_check_attempt
 * @property int $max_check_attempts
 * @property int $state
 * @property int $state_type
 * @property \Cake\I18n\FrozenTime $start_time
 * @property int $start_time_usec
 * @property \Cake\I18n\FrozenTime $end_time
 * @property int $end_time_usec
 * @property int $command_object_id
 * @property string|null $command_args
 * @property string|null $command_line
 * @property int $timeout
 * @property int $early_timeout
 * @property float $execution_time
 * @property float $latency
 * @property int $return_code
 * @property string|null $output
 * @property string|null $long_output
 * @property string|null $perfdata
 *
 * @property \Statusengine2Module\Model\Entity\Hostcheck $hostcheck
 * @property \Statusengine2Module\Model\Entity\Instance $instance
 * @property \Statusengine2Module\Model\Entity\HostObject $host_object
 * @property \Statusengine2Module\Model\Entity\CommandObject $command_object
 */
class Hostcheck extends Entity {
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
        'host_object_id'        => true,
        'check_type'            => true,
        'is_raw_check'          => true,
        'current_check_attempt' => true,
        'max_check_attempts'    => true,
        'state'                 => true,
        'state_type'            => true,
        'start_time_usec'       => true,
        'end_time'              => true,
        'end_time_usec'         => true,
        'command_object_id'     => true,
        'command_args'          => true,
        'command_line'          => true,
        'timeout'               => true,
        'early_timeout'         => true,
        'execution_time'        => true,
        'latency'               => true,
        'return_code'           => true,
        'output'                => true,
        'long_output'           => true,
        'perfdata'              => true,
        'hostcheck'             => true,
        'instance'              => true,
        'host_object'           => true,
        'command_object'        => true
    ];
}
