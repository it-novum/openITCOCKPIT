<?php
declare(strict_types=1);

namespace MapModule\Model\Entity;

use App\Model\Entity\Container;
use Cake\ORM\Entity;

/**
 * Map Entity
 *
 * @property int $id
 * @property string $name
 * @property string $title
 * @property string|null $background
 * @property int $refresh_interval
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \MapModule\Model\Entity\Mapgadget[] $mapgadgets
 * @property \MapModule\Model\Entity\Mapicon[] $mapicons
 * @property \MapModule\Model\Entity\Mapitem[] $mapitems
 * @property \MapModule\Model\Entity\Mapline[] $maplines
 * @property \MapModule\Model\Entity\MapsToContainer[] $maps_to_containers
 * @property \MapModule\Model\Entity\MapsToRotation[] $maps_to_rotations
 * @property \MapModule\Model\Entity\Mapsummaryitem[] $mapsummaryitems
 * @property \MapModule\Model\Entity\Maptext[] $maptexts
 */
class Map extends Entity {
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
        'name'               => true,
        'title'              => true,
        'background'         => true,
        'refresh_interval'   => true,
        'created'            => true,
        'modified'           => true,
        'mapgadgets'         => true,
        'mapicons'           => true,
        'mapitems'           => true,
        'maplines'           => true,
        'maps_to_containers' => true,
        'maps_to_rotations'  => true,
        'mapsummaryitems'    => true,
        'maptexts'           => true,
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
