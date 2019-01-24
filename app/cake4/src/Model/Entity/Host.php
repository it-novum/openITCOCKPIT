<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Host Entity
 *
 * @property int $id
 * @property string $uuid
 * @property int $container_id
 * @property string $name
 * @property string|null $description
 * @property int $hosttemplate_id
 * @property string $address
 * @property int|null $command_id
 * @property int|null $eventhandler_command_id
 * @property int|null $timeperiod_id
 * @property int|null $check_interval
 * @property int|null $retry_interval
 * @property int|null $max_check_attempts
 * @property float|null $first_notification_delay
 * @property float|null $notification_interval
 * @property int|null $notify_on_down
 * @property int|null $notify_on_unreachable
 * @property int|null $notify_on_recovery
 * @property int|null $notify_on_flapping
 * @property int|null $notify_on_downtime
 * @property int|null $flap_detection_enabled
 * @property int|null $flap_detection_on_up
 * @property int|null $flap_detection_on_down
 * @property int|null $flap_detection_on_unreachable
 * @property float|null $low_flap_threshold
 * @property float|null $high_flap_threshold
 * @property int|null $process_performance_data
 * @property int|null $freshness_checks_enabled
 * @property int|null $freshness_threshold
 * @property int|null $passive_checks_enabled
 * @property int|null $event_handler_enabled
 * @property int|null $active_checks_enabled
 * @property int|null $retain_status_information
 * @property int|null $retain_nonstatus_information
 * @property int|null $notifications_enabled
 * @property string|null $notes
 * @property int|null $priority
 * @property int|null $check_period_id
 * @property int|null $notify_period_id
 * @property string|null $tags
 * @property int $own_contacts
 * @property int $own_contactgroups
 * @property int $own_customvariables
 * @property string|null $host_url
 * @property int|null $satellite_id
 * @property int $host_type
 * @property int|null $disabled
 * @property int $usage_flag
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Container $container
 * @property \App\Model\Entity\Hosttemplate $hosttemplate
 * @property \App\Model\Entity\Command $command
 * @property \App\Model\Entity\EventhandlerCommand $eventhandler_command
 * @property \App\Model\Entity\Timeperiod $timeperiod
 * @property \App\Model\Entity\CheckPeriod $check_period
 * @property \App\Model\Entity\NotifyPeriod $notify_period
 * @property \App\Model\Entity\Satellite $satellite
 * @property \App\Model\Entity\ContactgroupsToHost[] $contactgroups_to_hosts
 * @property \App\Model\Entity\ContactsToHost[] $contacts_to_hosts
 * @property \App\Model\Entity\DeletedHost[] $deleted_hosts
 * @property \App\Model\Entity\DeletedService[] $deleted_services
 * @property \App\Model\Entity\Eventcorrelation[] $eventcorrelations
 * @property \App\Model\Entity\GrafanaDashboard[] $grafana_dashboards
 * @property \App\Model\Entity\GrafanaUserdashboardMetric[] $grafana_userdashboard_metrics
 * @property \App\Model\Entity\Hostcommandargumentvalue[] $hostcommandargumentvalues
 * @property \App\Model\Entity\HostsToAutoreport[] $hosts_to_autoreports
 * @property \App\Model\Entity\HostsToContainer[] $hosts_to_containers
 * @property \App\Model\Entity\HostsToHostdependency[] $hosts_to_hostdependencies
 * @property \App\Model\Entity\HostsToHostescalation[] $hosts_to_hostescalations
 * @property \App\Model\Entity\HostsToHostgroup[] $hosts_to_hostgroups
 * @property \App\Model\Entity\HostsToParenthost[] $hosts_to_parenthosts
 * @property \App\Model\Entity\IdoitHost[] $idoit_hosts
 * @property \App\Model\Entity\InstantreportsToHost[] $instantreports_to_hosts
 * @property \App\Model\Entity\LastUsedMkagent[] $last_used_mkagents
 * @property \App\Model\Entity\Mkservicedata[] $mkservicedata
 * @property \App\Model\Entity\Mksnmp[] $mksnmp
 * @property \App\Model\Entity\NagiosHostContactgroup[] $nagios_host_contactgroups
 * @property \App\Model\Entity\NagiosHostContact[] $nagios_host_contacts
 * @property \App\Model\Entity\NagiosHostParenthost[] $nagios_host_parenthosts
 * @property \App\Model\Entity\NagiosHost[] $nagios_hosts
 * @property \App\Model\Entity\Service[] $services
 * @property \App\Model\Entity\ServicesToAutoreport[] $services_to_autoreports
 * @property \App\Model\Entity\Widget[] $widgets
 */
class Host extends Entity
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
        'container_id' => true,
        'name' => true,
        'description' => true,
        'hosttemplate_id' => true,
        'address' => true,
        'command_id' => true,
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
        'own_contacts' => true,
        'own_contactgroups' => true,
        'own_customvariables' => true,
        'host_url' => true,
        'satellite_id' => true,
        'host_type' => true,
        'disabled' => true,
        'usage_flag' => true,
        'created' => true,
        'modified' => true,
        'container' => true,
        'hosttemplate' => true,
        'command' => true,
        'eventhandler_command' => true,
        'timeperiod' => true,
        'check_period' => true,
        'notify_period' => true,
        'satellite' => true,
        'contactgroups_to_hosts' => true,
        'contacts_to_hosts' => true,
        'deleted_hosts' => true,
        'deleted_services' => true,
        'eventcorrelations' => true,
        'grafana_dashboards' => true,
        'grafana_userdashboard_metrics' => true,
        'hostcommandargumentvalues' => true,
        'hosts_to_autoreports' => true,
        'hosts_to_containers' => true,
        'hosts_to_hostdependencies' => true,
        'hosts_to_hostescalations' => true,
        'hosts_to_hostgroups' => true,
        'hosts_to_parenthosts' => true,
        'idoit_hosts' => true,
        'instantreports_to_hosts' => true,
        'last_used_mkagents' => true,
        'mkservicedata' => true,
        'mksnmp' => true,
        'nagios_host_contactgroups' => true,
        'nagios_host_contacts' => true,
        'nagios_host_parenthosts' => true,
        'nagios_hosts' => true,
        'services' => true,
        'services_to_autoreports' => true,
        'widgets' => true
    ];
}
