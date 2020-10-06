<?php

declare(strict_types=1);

namespace TelegramModule\Model\Entity;

use Cake\ORM\Entity;

/**
 * TelegramModule Entity
 *
 * @property int $id
 * @property int $chat_id
 * @property bool $enabled
 * @property string $started_from_username
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 */
class TelegramChats extends Entity {
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
        'chat_id'               => true,
        'enabled'               => true,
        'started_from_username' => true,
        'created'               => true,
        'modified'              => true,
    ];
}
