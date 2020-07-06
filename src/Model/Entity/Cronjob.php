<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Cronjob Entity
 *
 * @property int $id
 * @property string $task
 * @property string $plugin
 * @property int|null $interval
 * @property bool $enabled
 *
 * @property \App\Model\Entity\Cronschedule[] $cronschedules
 */
class Cronjob extends Entity {

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
        'task'         => true,
        'plugin'       => true,
        'interval'     => true,
        'enabled'      => true,
        'cronschedule' => true
    ];
}
