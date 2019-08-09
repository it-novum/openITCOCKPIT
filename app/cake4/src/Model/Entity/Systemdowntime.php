<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Systemdowntime Entity
 *
 * @property int $id
 * @property int|null $objecttype_id
 * @property int|null $object_id
 * @property int|null $downtimetype_id
 * @property string|null $weekdays
 * @property string|null $day_of_month
 * @property string $from_time
 * @property string|null $to_time
 * @property int $duration
 * @property string|null $comment
 * @property string|null $author
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Objecttype $objecttype
 * @property \App\Model\Entity\Object $object
 * @property \App\Model\Entity\Downtimetype $downtimetype
 */
class Systemdowntime extends Entity
{
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
        'objecttype_id' => true,
        'object_id' => true,
        'downtimetype_id' => true,
        'weekdays' => true,
        'day_of_month' => true,
        'from_time' => true,
        'to_time' => true,
        'duration' => true,
        'comment' => true,
        'author' => true,
        'created' => true,
        'modified' => true,
        'objecttype' => true,
        'object' => true,
        'downtimetype' => true
    ];
}
