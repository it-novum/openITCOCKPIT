<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Statuspage Entity
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property bool $public
 *
 * @property \App\Model\Entity\StatuspagesToContainer[] $statuspages_to_containers
 * @property \App\Model\Entity\StatuspagesToHostgroup[] $statuspages_to_hostgroups
 * @property \App\Model\Entity\StatuspagesToHost[] $statuspages_to_hosts
 * @property \App\Model\Entity\StatuspagesToServicegroup[] $statuspages_to_servicegroups
 * @property \App\Model\Entity\StatuspagesToService[] $statuspages_to_services
 */
class Statuspage extends Entity
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
        'name' => true,
        'description' => true,
        'public' => true,
        'containers' => true,
        'hostgroups' => true,
        'hosts' => true,
        'servicegroups' => true,
        'services' => true,
    ];
}
