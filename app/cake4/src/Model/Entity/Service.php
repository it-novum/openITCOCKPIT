<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Service Entity
 *
 * @property int $id
 * @property string $uuid
 * @property int $servicetemplate_id
 * @property int $host_id
 * @property string|null $name
 * @property string|null $description
 * @property int|null $command_id
 * @property string $check_command_args
 * @property int|null $eventhandler_command_id
 * @property int|null $notify_period_id
 * @property int|null $check_period_id
 * @property float|null $check_interval
 * @property float|null $retry_interval
 * @property int|null $max_check_attempts
 * @property float|null $first_notification_delay
 * @property float|null $notification_interval
 * @property int|null $notify_on_warning
 * @property int|null $notify_on_unknown
 * @property int|null $notify_on_critical
 * @property int|null $notify_on_recovery
 * @property int|null $notify_on_flapping
 * @property int|null $notify_on_downtime
 * @property int|null $is_volatile
 * @property int|null $flap_detection_enabled
 * @property int|null $flap_detection_on_ok
 * @property int|null $flap_detection_on_warning
 * @property int|null $flap_detection_on_unknown
 * @property int|null $flap_detection_on_critical
 * @property float|null $low_flap_threshold
 * @property float|null $high_flap_threshold
 * @property int|null $process_performance_data
 * @property int|null $freshness_checks_enabled
 * @property int|null $freshness_threshold
 * @property int|null $passive_checks_enabled
 * @property int|null $event_handler_enabled
 * @property int|null $active_checks_enabled
 * @property int|null $notifications_enabled
 * @property string|null $notes
 * @property int|null $priority
 * @property string|null $tags
 * @property int|null $own_contacts
 * @property int|null $own_contactgroups
 * @property int|null $own_customvariables
 * @property string|null $service_url
 * @property int $service_type
 * @property int|null $disabled
 * @property int $usage_flag
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Host $host
 * @property \App\Model\Entity\Servicetemplate $servicetemplate
 * @property \MkModule\Model\Entity\Mkservicedata $mkservicedata
 * @property \NewModule\Model\Entity\Servicecommandargumentvalue[] $servicecommandargumentvalues
 */
class Service extends Entity {
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
        'uuid'                         => true,
        'servicetemplate_id'           => true,
        'host_id'                      => true,
        'name'                         => true,
        'description'                  => true,
        'command_id'                   => true,
        'check_command_args'           => true,
        'eventhandler_command_id'      => true,
        'notify_period_id'             => true,
        'check_period_id'              => true,
        'check_interval'               => true,
        'retry_interval'               => true,
        'max_check_attempts'           => true,
        'first_notification_delay'     => true,
        'notification_interval'        => true,
        'notify_on_warning'            => true,
        'notify_on_unknown'            => true,
        'notify_on_critical'           => true,
        'notify_on_recovery'           => true,
        'notify_on_flapping'           => true,
        'notify_on_downtime'           => true,
        'is_volatile'                  => true,
        'flap_detection_enabled'       => true,
        'flap_detection_on_ok'         => true,
        'flap_detection_on_warning'    => true,
        'flap_detection_on_unknown'    => true,
        'flap_detection_on_critical'   => true,
        'low_flap_threshold'           => true,
        'high_flap_threshold'          => true,
        'process_performance_data'     => true,
        'freshness_checks_enabled'     => true,
        'freshness_threshold'          => true,
        'passive_checks_enabled'       => true,
        'event_handler_enabled'        => true,
        'active_checks_enabled'        => true,
        'notifications_enabled'        => true,
        'notes'                        => true,
        'priority'                     => true,
        'tags'                         => true,
        'own_contacts'                 => true,
        'own_contactgroups'            => true,
        'own_customvariables'          => true,
        'service_url'                  => true,
        'service_type'                 => true,
        'disabled'                     => true,
        'usage_flag'                   => true,
        'created'                      => true,
        'modified'                     => true,
        'host'                         => true,
        'servicetemplate'              => true,
        'mkservicedata'                => true,
        'servicecommandargumentvalues' => true
    ];
}
