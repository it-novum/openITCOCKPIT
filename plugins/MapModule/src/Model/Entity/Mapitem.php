<?php
// Copyright (C) <2015>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//	This program is free software: you can redistribute it and/or modify
//	it under the terms of the GNU General Public License as published by
//	the Free Software Foundation, version 3 of the License.
//
//	This program is distributed in the hope that it will be useful,
//	but WITHOUT ANY WARRANTY; without even the implied warranty of
//	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//	GNU General Public License for more details.
//
//	You should have received a copy of the GNU General Public License
//	along with this program.  If not, see <http://www.gnu.org/licenses/>.
//

// 2.
//	If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//	under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//	License agreement and license key will be shipped with the order
//	confirmation.

declare(strict_types=1);

namespace MapModule\Model\Entity;

use Cake\I18n\FrozenTime;
use Cake\ORM\Entity;

/**
 * Mapitem Entity
 *
 * @property int $id
 * @property int $map_id
 * @property int $x
 * @property int $y
 * @property int|null $limit
 * @property string $iconset
 * @property string $type
 * @property int $object_id
 * @property int $z_index
 * @property int $show_label
 * @property int $label_possition
 * @property FrozenTime $created
 * @property FrozenTime $modified
 *
 * @property Map $map
 * @property Object $object
 */
class Mapitem extends Entity {
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
        'map_id'          => true,
        'x'               => true,
        'y'               => true,
        'limit'           => true,
        'iconset'         => true,
        'type'            => true,
        'object_id'       => true,
        'z_index'         => true,
        'show_label'      => true,
        'label_possition' => true,
        'created'         => true,
        'modified'        => true,
        'map'             => true,
        'object'          => true,
    ];
}
