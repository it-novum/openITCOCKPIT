<?php

namespace Statusengine2Module\Model\Entity;

use Cake\ORM\Entity;

/**
 * Servicestatus Entity
 *
 * @property int $servicestatus_id
 * @property int $instance_id
 * @property int $service_object_id
 * @property \Cake\I18n\FrozenTime $status_update_time
 * @property string|null $output
 * @property string|null $long_output
 * @property string|null $perfdata
 * @property int $current_state
 * @property int $has_been_checked
 * @property int $should_be_scheduled
 * @property int $current_check_attempt
 * @property int $max_check_attempts
 * @property \Cake\I18n\FrozenTime $last_check
 * @property \Cake\I18n\FrozenTime $next_check
 * @property int $check_type
 * @property \Cake\I18n\FrozenTime $last_state_change
 * @property \Cake\I18n\FrozenTime $last_hard_state_change
 * @property int $last_hard_state
 * @property \Cake\I18n\FrozenTime $last_time_ok
 * @property \Cake\I18n\FrozenTime $last_time_warning
 * @property \Cake\I18n\FrozenTime $last_time_unknown
 * @property \Cake\I18n\FrozenTime $last_time_critical
 * @property int $state_type
 * @property \Cake\I18n\FrozenTime $last_notification
 * @property \Cake\I18n\FrozenTime $next_notification
 * @property int $no_more_notifications
 * @property int $notifications_enabled
 * @property int $problem_has_been_acknowledged
 * @property int $acknowledgement_type
 * @property int $current_notification_number
 * @property int $passive_checks_enabled
 * @property int $active_checks_enabled
 * @property int $event_handler_enabled
 * @property int $flap_detection_enabled
 * @property int $is_flapping
 * @property float $percent_state_change
 * @property float $latency
 * @property float $execution_time
 * @property int $scheduled_downtime_depth
 * @property int $failure_prediction_enabled
 * @property int $process_performance_data
 * @property int $obsess_over_service
 * @property int $modified_service_attributes
 * @property string|null $event_handler
 * @property string $check_command
 * @property float $normal_check_interval
 * @property float $retry_check_interval
 * @property int $check_timeperiod_object_id
 *
 * @property \Statusengine2Module\Model\Entity\Object $object
 */
class Servicestatus extends Entity {

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
        'instance_id'                   => false,
        'service_object_id'             => false,
        'status_update_time'            => false,
        'output'                        => false,
        'long_output'                   => false,
        'perfdata'                      => false,
        'current_state'                 => false,
        'has_been_checked'              => false,
        'should_be_scheduled'           => false,
        'current_check_attempt'         => false,
        'max_check_attempts'            => false,
        'last_check'                    => false,
        'next_check'                    => false,
        'check_type'                    => false,
        'last_state_change'             => false,
        'last_hard_state_change'        => false,
        'last_hard_state'               => false,
        'last_time_ok'                  => false,
        'last_time_warning'             => false,
        'last_time_unknown'             => false,
        'last_time_critical'            => false,
        'state_type'                    => false,
        'last_notification'             => false,
        'next_notification'             => false,
        'no_more_notifications'         => false,
        'notifications_enabled'         => false,
        'problem_has_been_acknowledged' => false,
        'acknowledgement_type'          => false,
        'current_notification_number'   => false,
        'passive_checks_enabled'        => false,
        'active_checks_enabled'         => false,
        'event_handler_enabled'         => false,
        'flap_detection_enabled'        => false,
        'is_flapping'                   => false,
        'percent_state_change'          => false,
        'latency'                       => false,
        'execution_time'                => false,
        'scheduled_downtime_depth'      => false,
        'failure_prediction_enabled'    => false,
        'process_performance_data'      => false,
        'obsess_over_service'           => false,
        'modified_service_attributes'   => false,
        'event_handler'                 => false,
        'check_command'                 => false,
        'normal_check_interval'         => false,
        'retry_check_interval'          => false,
        'check_timeperiod_object_id'    => false,
        'instance'                      => false,
        'service_object'                => false,
        'check_timeperiod_object'       => false
    ];
}
