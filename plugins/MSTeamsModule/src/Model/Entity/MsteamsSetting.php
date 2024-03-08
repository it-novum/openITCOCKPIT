<?php
declare(strict_types=1);

namespace MSTeamsModule\Model\Entity;

use Cake\ORM\Entity;

/**
 * MsteamsSetting Entity
 *
 * @property int $id
 * @property string $webhook_url
 * @property bool $two_way
 * @property bool $use_proxy
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 */
class MsteamsSetting extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected $_accessible = [
        'webhook_url' => true,
        'two_way' => true,
        'use_proxy' => true,
        'created' => true,
        'modified' => true,
    ];
}
