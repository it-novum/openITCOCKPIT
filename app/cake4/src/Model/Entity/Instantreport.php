<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Automap Entity
 *
 * @property int $id
 * @property string $name
 * @property int $container_id
 * @property string|null $description
 * @property string|null $host_regex
 * @property string|null $service_regex
 * @property bool $show_ok
 * @property bool $show_warning
 * @property bool $show_critical
 * @property bool $show_unknown
 * @property bool $show_acknowledged
 * @property bool $show_downtime
 * @property bool $show_label
 * @property bool $group_by_host
 * @property string|null $font_size
 * @property bool $recursive
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Container $container
 */
class Instantreport extends Entity {
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
        'name'           => true,
        'container_id'   => true,
        'evaluation'     => true,
        'type'           => true,
        'timeperiod_id'  => true,
        'reflection'     => true,
        'downtimes'      => true,
        'summary'        => true,
        'send_email'     => true,
        'send_interval'  => true,
        'last_send_date' => true,
        'hostgroups'     => true,
        'hosts'          => true,
        'servicegroups'  => true,
        'services'       => true,
        'users'           => true,
        'created'        => true,
        'modified'       => true
    ];
}
