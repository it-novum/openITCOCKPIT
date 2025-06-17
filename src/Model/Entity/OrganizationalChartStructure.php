<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * OrganizationalChartStructure Entity
 *
 * @property int $id
 * @property int|null $parent_id
 * @property int $lft
 * @property int $rght
 * @property int|null $organizational_chart_id
 * @property int|null $container_id
 *
 * @property \App\Model\Entity\ParentOrganizationalChartStructure $parent_organizational_chart_structure
 * @property \App\Model\Entity\OrganizationalChart $organizational_chart
 * @property \App\Model\Entity\Container $container
 * @property \App\Model\Entity\ChildOrganizationalChartStructure[] $child_organizational_chart_structures
 * @property \App\Model\Entity\UsersToOrganizationalChartStructure[] $users_to_organizational_chart_structures
 */
class OrganizationalChartStructure extends Entity
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
        'parent_id' => true,
        'lft' => true,
        'rght' => true,
        'organizational_chart_id' => true,
        'container_id' => true,
        'parent_organizational_chart_structure' => true,
        'organizational_chart' => true,
        'container' => true,
        'child_organizational_chart_structures' => true,
        'users_to_organizational_chart_structures' => true,
    ];
}
