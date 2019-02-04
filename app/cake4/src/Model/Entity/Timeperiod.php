<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Timeperiod Entity
 *
 * @property int $id
 * @property string $uuid
 * @property int $container_id
 * @property string $name
 * @property string|null $description
 * @property int|null $calendar_id
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Container $container
 * @property \App\Model\Entity\Calendar $calendar
 * @property \App\Model\Entity\Autoreport[] $autoreports
 * @property \App\Model\Entity\Hostdependency[] $hostdependencies
 * @property \App\Model\Entity\Hostescalation[] $hostescalations
 * @property \App\Model\Entity\Host[] $hosts
 * @property \App\Model\Entity\Hosttemplate[] $hosttemplates
 * @property \App\Model\Entity\Instantreport[] $instantreports
 * @property \App\Model\Entity\NagiosTimeperiodTimerange[] $nagios_timeperiod_timeranges
 * @property \App\Model\Entity\NagiosTimeperiod[] $nagios_timeperiods
 * @property \App\Model\Entity\Servicedependency[] $servicedependencies
 * @property \App\Model\Entity\Serviceescalation[] $serviceescalations
 * @property \App\Model\Entity\Servicetemplate[] $servicetemplates
 * @property \App\Model\Entity\TimeperiodTimerange[] $timeperiod_timeranges
 */
class Timeperiod extends Entity {

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
        'container_id'                 => true,
        'name'                         => true,
        'description'                  => true,
        'calendar_id'                  => true,
        'created'                      => true,
        'modified'                     => true,
        'container'                    => true,
        'calendar'                     => true,
        'autoreports'                  => true,
        'hostdependencies'             => true,
        'hostescalations'              => true,
        'hosts'                        => true,
        'hosttemplates'                => true,
        'instantreports'               => true,
        'nagios_timeperiod_timeranges' => true,
        'nagios_timeperiods'           => true,
        'servicedependencies'          => true,
        'serviceescalations'           => true,
        'servicetemplates'             => true,
        'timeperiod_timeranges'        => true
    ];
}
