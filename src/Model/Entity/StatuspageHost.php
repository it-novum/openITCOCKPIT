<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * StatuspageHost Entity
 *
 * @property int $id
 * @property int $statuspage_id
 * @property int $host_id
 * @property string|null $display_name
 *
 * @property \App\Model\Entity\Statuspage $statuspage
 * @property \App\Model\Entity\Host $host
 */
class StatuspageHost extends Entity
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
        'statuspage_id' => true,
        'host_id' => true,
        'display_name' => true,
        'statuspage' => true,
        'host' => true,
    ];
}
