<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * UsercontainerroleMembership Entity
 *
 * @property int $id
 * @property int $user_id
 * @property int $usercontainerrole_id
 * @property bool $through_ldap
 */
class UsercontainerroleMembership extends Entity {

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
        'user_id'              => true,
        'usercontainerrole_id' => true,
        'through_ldap'         => true
    ];
}
