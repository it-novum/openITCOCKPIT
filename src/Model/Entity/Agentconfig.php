<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Agentconfig Entity
 *
 * @property int $id
 * @property int|null $host_id
 * @property bool $use_https
 * @property bool $insecure
 * @property bool $basic_auth
 * @property string $password
 * @property bool $push_noticed
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Host $host
 */
class Agentconfig extends Entity {
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
        'port'         => true,
        'host_id'      => true,
        'use_https'    => true,
        'insecure'     => true,
        'basic_auth'   => true,
        'username'     => true,
        'password'     => true,
        'push_noticed' => true,
        'created'      => true,
        'modified'     => true,
        'host'         => true
    ];

}
