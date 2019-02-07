<?php
namespace MkModule\Model\Entity;

use Cake\ORM\Entity;

/**
 * Mksnmp Entity
 *
 * @property int $id
 * @property int $host_id
 * @property int|null $version
 * @property string|null $community
 * @property int|null $security_level
 * @property int|null $hash_algorithm
 * @property string|null $username
 * @property string|null $password
 * @property int|null $encryption_algorithm
 * @property string|null $encryption_password
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \MkModule\Model\Entity\Host $host
 */
class Mksnmp extends Entity
{

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
        'host_id' => true,
        'version' => true,
        'community' => true,
        'security_level' => true,
        'hash_algorithm' => true,
        'username' => true,
        'password' => true,
        'encryption_algorithm' => true,
        'encryption_password' => true,
        'created' => true,
        'modified' => true,
        'host' => true
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array
     */
    protected $_hidden = [
        'password'
    ];
}
