<?php

namespace Statusengine2Module\Model\Entity;

use Cake\ORM\Entity;

/**
 * NotificationHost Entity
 *
 * @property int $notification_id
 * @property int $instance_id
 * @property int $notification_type
 * @property int $notification_reason
 * @property int $object_id
 * @property \Cake\I18n\FrozenTime $start_time
 * @property int $start_time_usec
 * @property \Cake\I18n\FrozenTime $end_time
 * @property int $end_time_usec
 * @property int $state
 * @property string|null $output
 * @property string|null $long_output
 * @property int $escalated
 * @property int $contacts_notified
 *
 * @property \Statusengine2Module\Model\Entity\ObjectEntity $object
 */
class NotificationHost extends Entity {
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
        'instance_id'         => true,
        'notification_type'   => true,
        'notification_reason' => true,
        'object_id'           => true,
        'start_time_usec'     => true,
        'end_time'            => true,
        'end_time_usec'       => true,
        'state'               => true,
        'output'              => true,
        'long_output'         => true,
        'escalated'           => true,
        'contacts_notified'   => true,
        'notification'        => true,
        'instance'            => true,
        'object'              => true
    ];
}
