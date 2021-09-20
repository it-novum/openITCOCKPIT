<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * MessagesOtd Entity
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string|null $content
 * @property string $style
 * @property int $user_id
 * @property \Cake\I18n\FrozenDate $date
 * @property int $expiration_duration
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Usergroup $usergroup
 */
class MessagesOtd extends Entity {
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
        'title'               => true,
        'description'         => true,
        'content'             => true,
        'style'               => true,
        'user_id'             => true,
        'date'                => true,
        'expiration_duration' => true,
        'created'             => true,
        'modified'            => true,
        'user'                => true,
        'usergroups'          => true
    ];
}
