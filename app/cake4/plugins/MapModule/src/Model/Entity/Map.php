<?php
declare(strict_types=1);

namespace MapModule\Model\Entity;

use App\Model\Entity\Container;
use Cake\I18n\FrozenTime;
use Cake\ORM\Entity;

/**
 * Map Entity
 *
 * @property int $id
 * @property string $name
 * @property string $title
 * @property string|null $background
 * @property int $refresh_interval
 * @property FrozenTime $created
 * @property FrozenTime $modified
 *
 * @property Mapgadget[] $mapgadgets
 * @property Mapicon[] $mapicons
 * @property Mapitem[] $mapitems
 * @property Mapline[] $maplines
 * @property MapsToContainer[] $maps_to_containers
 * @property MapsToRotation[] $maps_to_rotations
 * @property Mapsummaryitem[] $mapsummaryitems
 * @property Maptext[] $maptexts
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
