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
 * @property \App\Model\Entity\Servicetemplate $servicetemplate
 * @property \App\Model\Entity\Host $host
 * @property \App\Model\Entity\Command $command
 * @property \App\Model\Entity\EventhandlerCommand $eventhandler_command
 * @property \App\Model\Entity\NotifyPeriod $notify_period
 * @property \App\Model\Entity\CheckPeriod $check_period
 * @property \App\Model\Entity\ContactgroupsToService[] $contactgroups_to_services
 * @property \App\Model\Entity\ContactsToService[] $contacts_to_services
 * @property \App\Model\Entity\Eventcorrelation[] $eventcorrelations
 * @property \App\Model\Entity\GrafanaUserdashboardMetric[] $grafana_userdashboard_metrics
 * @property \App\Model\Entity\GraphgenTmplConf[] $graphgen_tmpl_confs
 * @property \App\Model\Entity\InstantreportsToService[] $instantreports_to_services
 * @property \App\Model\Entity\Mkservicedata[] $mkservicedata
 * @property \App\Model\Entity\NagiosServiceContactgroup[] $nagios_service_contactgroups
 * @property \App\Model\Entity\NagiosServiceContact[] $nagios_service_contacts
 * @property \App\Model\Entity\NagiosServiceParentservice[] $nagios_service_parentservices
 * @property \App\Model\Entity\NagiosService[] $nagios_services
 * @property \App\Model\Entity\Servicecommandargumentvalue[] $servicecommandargumentvalues
 * @property \App\Model\Entity\Serviceeventcommandargumentvalue[] $serviceeventcommandargumentvalues
 * @property \App\Model\Entity\ServicesToAutoreport[] $services_to_autoreports
 * @property \App\Model\Entity\ServicesToServicedependency[] $services_to_servicedependencies
 * @property \App\Model\Entity\ServicesToServiceescalation[] $services_to_serviceescalations
 * @property \App\Model\Entity\ServicesToServicegroup[] $services_to_servicegroups
 * @property \App\Model\Entity\Widget[] $widgets
 */
class Service extends Entity
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
        'servicetemplate_id' => true,
        'host_id' => true,
        'name' => true,
        'description' => true,
        'command_id' => true,
        'check_command_args' => true,
        'eventhandler_command_id' => true,
        'notify_period_id' => true,
        'check_period_id' => true,
        'check_interval' => true,
        'retry_interval' => true,
        'max_check_attempts' => true,
        'first_notification_delay' => true,
        'notification_interval' => true,
        'notify_on_warning' => true,
        'notify_on_unknown' => true,
        'notify_on_critical' => true,
        'notify_on_recovery' => true,
        'notify_on_flapping' => true,
        'notify_on_downtime' => true,
        'is_volatile' => true,
        'flap_detection_enabled' => true,
        'flap_detection_on_ok' => true,
        'flap_detection_on_warning' => true,
        'flap_detection_on_unknown' => true,
        'flap_detection_on_critical' => true,
        'low_flap_threshold' => true,
        'high_flap_threshold' => true,
        'process_performance_data' => true,
        'freshness_checks_enabled' => true,
        'freshness_threshold' => true,
        'passive_checks_enabled' => true,
        'event_handler_enabled' => true,
        'active_checks_enabled' => true,
        'notifications_enabled' => true,
        'notes' => true,
        'priority' => true,
        'tags' => true,
        'own_contacts' => true,
        'own_contactgroups' => true,
        'own_customvariables' => true,
        'service_url' => true,
        'service_type' => true,
        'disabled' => true,
        'usage_flag' => true,
        'created' => true,
        'modified' => true,
        'servicetemplate' => true,
        'host' => true,
        'command' => true,
        'eventhandler_command' => true,
        'notify_period' => true,
        'check_period' => true,
        'contactgroups_to_services' => true,
        'contacts_to_services' => true,
        'eventcorrelations' => true,
        'grafana_userdashboard_metrics' => true,
        'graphgen_tmpl_confs' => true,
        'instantreports_to_services' => true,
        'mkservicedata' => true,
        'nagios_service_contactgroups' => true,
        'nagios_service_contacts' => true,
        'nagios_service_parentservices' => true,
        'nagios_services' => true,
        'servicecommandargumentvalues' => true,
        'serviceeventcommandargumentvalues' => true,
        'services_to_autoreports' => true,
        'services_to_servicedependencies' => true,
        'services_to_serviceescalations' => true,
        'services_to_servicegroups' => true,
        'widgets' => true,
    ];
}
