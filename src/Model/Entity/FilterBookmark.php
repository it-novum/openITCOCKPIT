<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * FilterBokkmark Entity
 *
 * @property int $id
 * @property string $uuid
 * @property string $plugin
 * @property string $controller
 * @property string $action
 * @property string $name
 * @property string $filter
 * @property bool $default
**/

class FilterBookmark extends Entity {
    protected $_accessible = [
        'uuid'            => true,
        'plugin'          => true,
        'controller'      => true,
        'action'          => true,
        'name'            => true,
        'user_id'         => true,
        'filter'          => true,
        'default'         => true,
    ];
}
