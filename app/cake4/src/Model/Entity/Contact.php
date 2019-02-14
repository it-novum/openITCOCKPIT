<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Contact Entity
 *
 * @property int $id
 * @property string $uuid
 * @property string $name
 * @property string $description
 * @property string $email
 * @property string $phone
 * @property int|null $user_id
 * @property int $host_timeperiod_id
 * @property int $service_timeperiod_id
 * @property int $host_notifications_enabled
 * @property int $service_notifications_enabled
 * @property int $notify_service_recovery
 * @property int $notify_service_warning
 * @property int $notify_service_unknown
 * @property int $notify_service_critical
 * @property int $notify_service_flapping
 * @property int $notify_service_downtime
 * @property int $notify_host_recovery
 * @property int $notify_host_down
 * @property int $notify_host_unreachable
 * @property int $notify_host_flapping
 * @property int $notify_host_downtime
 * @property int $host_push_notifications_enabled
 * @property int $service_push_notifications_enabled
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Timeperiod $host_timeperiod
 * @property \App\Model\Entity\Timeperiod $service_timeperiod
 */
class Contact extends Entity {

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
        'uuid'                               => true,
        'name'                               => true,
        'description'                        => true,
        'email'                              => true,
        'phone'                              => true,
        'user_id'                            => true,
        'host_timeperiod_id'                 => true,
        'service_timeperiod_id'              => true,
        'host_notifications_enabled'         => true,
        'service_notifications_enabled'      => true,
        'notify_service_recovery'            => true,
        'notify_service_warning'             => true,
        'notify_service_unknown'             => true,
        'notify_service_critical'            => true,
        'notify_service_flapping'            => true,
        'notify_service_downtime'            => true,
        'notify_host_recovery'               => true,
        'notify_host_down'                   => true,
        'notify_host_unreachable'            => true,
        'notify_host_flapping'               => true,
        'notify_host_downtime'               => true,
        'host_push_notifications_enabled'    => true,
        'service_push_notifications_enabled' => true,
        'user'                               => true,
        'customvariables'                    => true,
        'host_timeperiod'                    => true,
        'service_timeperiod'                 => true,
        'host_commands'                      => true,
        'service_commands'                   => true,
        'containers'                         => true
    ];
}
