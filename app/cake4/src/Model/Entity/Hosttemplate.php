<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Hosttemplate Entity
 *
 * @property int $id
 * @property string $uuid
 * @property string $name
 * @property string $description
 * @property int $hosttemplatetype_id
 * @property int $command_id
 * @property string $check_command_args
 * @property int $eventhandler_command_id
 * @property int $timeperiod_id
 * @property int $check_interval
 * @property int $retry_interval
 * @property int $max_check_attempts
 * @property float $first_notification_delay
 * @property float $notification_interval
 * @property int $notify_on_down
 * @property int $notify_on_unreachable
 * @property int $notify_on_recovery
 * @property int $notify_on_flapping
 * @property int $notify_on_downtime
 * @property int $flap_detection_enabled
 * @property int $flap_detection_on_up
 * @property int $flap_detection_on_down
 * @property int $flap_detection_on_unreachable
 * @property float $low_flap_threshold
 * @property float $high_flap_threshold
 * @property int $process_performance_data
 * @property int $freshness_checks_enabled
 * @property int|null $freshness_threshold
 * @property int $passive_checks_enabled
 * @property int $event_handler_enabled
 * @property int $active_checks_enabled
 * @property int $retain_status_information
 * @property int $retain_nonstatus_information
 * @property int $notifications_enabled
 * @property string $notes
 * @property int $priority
 * @property int $check_period_id
 * @property int $notify_period_id
 * @property string $tags
 * @property int $container_id
 * @property string|null $host_url
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Hosttemplatetype $hosttemplatetype
 * @property \App\Model\Entity\Command $command
 * @property \App\Model\Entity\EventhandlerCommand $eventhandler_command
 * @property \App\Model\Entity\Timeperiod $timeperiod
 * @property \App\Model\Entity\CheckPeriod $check_period
 * @property \App\Model\Entity\NotifyPeriod $notify_period
 * @property \App\Model\Entity\Container $container
 * @property \App\Model\Entity\ContactgroupsToHosttemplate[] $contactgroups_to_hosttemplates
 * @property \App\Model\Entity\ContactsToHosttemplate[] $contacts_to_hosttemplates
 * @property \App\Model\Entity\DeletedHost[] $deleted_hosts
 * @property \App\Model\Entity\Host[] $hosts
 * @property \App\Model\Entity\Hosttemplatecommandargumentvalue[] $hosttemplatecommandargumentvalues
 * @property \App\Model\Entity\HosttemplatesToHostgroup[] $hosttemplates_to_hostgroups
 * @property \App\Model\Entity\IdoitObject[] $idoit_objects
 * @property \App\Model\Entity\IdoitObjecttype[] $idoit_objecttypes
 * @property \App\Model\Entity\NmapConfiguration[] $nmap_configurations
 */
class Hosttemplate extends Entity
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
        'uuid' => true,
        'name' => true,
        'description' => true,
        'hosttemplatetype_id' => true,
        'command_id' => true,
        'check_command_args' => true,
        'eventhandler_command_id' => true,
        'timeperiod_id' => true,
        'check_interval' => true,
        'retry_interval' => true,
        'max_check_attempts' => true,
        'first_notification_delay' => true,
        'notification_interval' => true,
        'notify_on_down' => true,
        'notify_on_unreachable' => true,
        'notify_on_recovery' => true,
        'notify_on_flapping' => true,
        'notify_on_downtime' => true,
        'flap_detection_enabled' => true,
        'flap_detection_on_up' => true,
        'flap_detection_on_down' => true,
        'flap_detection_on_unreachable' => true,
        'low_flap_threshold' => true,
        'high_flap_threshold' => true,
        'process_performance_data' => true,
        'freshness_checks_enabled' => true,
        'freshness_threshold' => true,
        'passive_checks_enabled' => true,
        'event_handler_enabled' => true,
        'active_checks_enabled' => true,
        'retain_status_information' => true,
        'retain_nonstatus_information' => true,
        'notifications_enabled' => true,
        'notes' => true,
        'priority' => true,
        'check_period_id' => true,
        'notify_period_id' => true,
        'tags' => true,
        'container_id' => true,
        'host_url' => true,
        'created' => true,
        'modified' => true,
        'hosttemplatetype' => true,
        'command' => true,
        'eventhandler_command' => true,
        'timeperiod' => true,
        'check_period' => true,
        'notify_period' => true,
        'container' => true,
        'contactgroups_to_hosttemplates' => true,
        'contacts_to_hosttemplates' => true,
        'deleted_hosts' => true,
        'hosts' => true,
        'hosttemplatecommandargumentvalues' => true,
        'hosttemplates_to_hostgroups' => true,
        'idoit_objects' => true,
        'idoit_objecttypes' => true,
        'nmap_configurations' => true
    ];
}
