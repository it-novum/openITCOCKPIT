<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;
use App\Model\Entity\Container;
use Cake\I18n\FrozenTime;
use DistributeModule\Model\Entity\Satellite;

/**
 * Statuspage Entity
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property bool $public
 * @property bool $show_comments
 * @property FrozenTime $created
 * @property FrozenTime $modified
 *
 * @property StatuspageItem[] $statuspage_items
 * @property StatuspagesToContainer[] $statuspages_to_containers
 * @property Container[] $containers
 * @property Satellite[] $satellites
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
        'show_comments' => true,
        'created' => true,
        'modified' => true,
        'statuspage_items' => true,
        'containers'         => true
    ];

    /**
     * @return array
     */
    public function getContainerIds() {
        foreach ($this->containers as $container) {
            /** @var Container $container */
            $containerIds[] = $container->get('id');
        }

        return array_unique($containerIds);
    }
}
