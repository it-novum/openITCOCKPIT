<?php
declare(strict_types=1);

namespace Statusengine2Module\Model\Entity;

use Cake\ORM\Entity;

/**
 * NagiosContactnotificationmethod Entity
 *
 * @property int $contactnotificationmethod_id
 * @property int $instance_id
 * @property int $contactnotification_id
 * @property \Cake\I18n\FrozenTime $start_time
 * @property int $start_time_usec
 * @property \Cake\I18n\FrozenTime $end_time
 * @property int $end_time_usec
 * @property int $command_object_id
 * @property string|null $command_args
 *
 * @property \Statusengine2Module\Model\Entity\Contactnotificationmethod $contactnotificationmethod
 * @property \Statusengine2Module\Model\Entity\Instance $instance
 * @property \Statusengine2Module\Model\Entity\Contactnotification $contactnotification
 * @property \Statusengine2Module\Model\Entity\ObjectEntity $command_object
 */
class Contactnotificationmethod extends Entity {
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
        'instance_id'               => true,
        'contactnotification_id'    => true,
        'start_time_usec'           => true,
        'end_time'                  => true,
        'end_time_usec'             => true,
        'command_object_id'         => true,
        'command_args'              => true,
        'contactnotificationmethod' => true,
        'instance'                  => true,
        'contactnotification'       => true,
        'command_object'            => true,
    ];
}
