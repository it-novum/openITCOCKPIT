<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * StatuspageHost Entity
 *
 * @property int $id
 * @property int $statuspage_id
 * @property int $service_id
 * @property string|null $display_alias
 *
 * @property \App\Model\Entity\Statuspage $statuspage
 * @property \App\Model\Entity\Service $service
 */
class StatuspagesToService extends Entity
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
        'service_id' => true,
        'display_alias' => true,
        'statuspage' => true,
        'service' => true,
    ];
}

