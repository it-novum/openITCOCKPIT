<?php
declare(strict_types=1);

namespace Statusengine2Module\Model\Entity;

use Cake\ORM\Entity;

/**
 * NagiosContactnotification Entity
 *
 * @property int $contactnotification_id
 * @property int $instance_id
 * @property int $notification_id
 * @property int $contact_object_id
 * @property \Cake\I18n\FrozenTime $start_time
 * @property int $start_time_usec
 * @property \Cake\I18n\FrozenTime $end_time
 * @property int $end_time_usec
 *
 * @property \Statusengine2Module\Model\Entity\Contactnotification $contactnotification
 * @property \Statusengine2Module\Model\Entity\Notification $notification
 * @property \Statusengine2Module\Model\Entity\ObjectEntity $contact_object
 */
class Contactnotification extends Entity {
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
        'notification_id'     => true,
        'contact_object_id'   => true,
        'start_time_usec'     => true,
        'end_time'            => true,
        'end_time_usec'       => true,
        'contactnotification' => true,
        'instance'            => true,
        'notification'        => true,
        'contact_object'      => true,
    ];
}
