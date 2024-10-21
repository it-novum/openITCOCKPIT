<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Export Entity
 *
 * @property int $id
 * @property string $task
 * @property string $text
 * @property int $finished
 * @property int $successfully
 * @property \Cake\I18n\DateTime $created
 * @property \Cake\I18n\DateTime $modified
 */
class Export extends Entity {
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected array $_accessible = [
        'task'         => true,
        'text'         => true,
        'finished'     => true,
        'successfully' => true,
        'created'      => true,
        'modified'     => true,
    ];
}
