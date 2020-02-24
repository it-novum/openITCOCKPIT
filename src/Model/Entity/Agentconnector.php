<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Agentconnector Entity
 *
 * @property int $id
 * @property string $hostuuid
 * @property string|resource|null $checksum
 * @property string|resource|null $ca_checksum
 * @property int|null $generation_date
 * @property string|null $remote_addr
 * @property string|null $http_x_forwarded_for
 * @property bool $trusted
 */
class Agentconnector extends Entity
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
        'hostuuid' => true,
        'checksum' => true,
        'ca_checksum' => true,
        'generation_date' => true,
        'remote_addr' => true,
        'http_x_forwarded_for' => true,
        'trusted' => true,
    ];
}
