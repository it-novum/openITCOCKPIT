<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * FilterBokkmark Entity
 *
 * @property int $id
 * @property string $uuid
 * @property string $filter_entity
 * @property string $name
 * @property string $filter
 * @property string $url
 * @property bool $default
**/

class FilterBookmark extends Entity {
    protected $_accessible = [
        'uuid'            => true,
        'filter_entity'   => true,
        'name'            => true,
        'user_id'         => true,
        'filter'          => true,
        'url'             => true,
        'default'         => true,
    ];
}
