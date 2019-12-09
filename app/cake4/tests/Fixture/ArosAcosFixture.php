<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ArosAcosFixture
 */
class ArosAcosFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 10, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'aro_id' => ['type' => 'integer', 'length' => 10, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'aco_id' => ['type' => 'integer', 'length' => 10, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_create' => ['type' => 'string', 'length' => 2, 'null' => false, 'default' => '0', 'collate' => 'utf8_swedish_ci', 'comment' => '', 'precision' => null],
        '_read' => ['type' => 'string', 'length' => 2, 'null' => false, 'default' => '0', 'collate' => 'utf8_swedish_ci', 'comment' => '', 'precision' => null],
        '_update' => ['type' => 'string', 'length' => 2, 'null' => false, 'default' => '0', 'collate' => 'utf8_swedish_ci', 'comment' => '', 'precision' => null],
        '_delete' => ['type' => 'string', 'length' => 2, 'null' => false, 'default' => '0', 'collate' => 'utf8_swedish_ci', 'comment' => '', 'precision' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'ARO_ACO_KEY' => ['type' => 'unique', 'columns' => ['aro_id', 'aco_id'], 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8_swedish_ci'
        ],
    ];
    // @codingStandardsIgnoreEnd
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'aro_id' => 1,
                'aco_id' => 1,
                '_create' => 'Lo',
                '_read' => 'Lo',
                '_update' => 'Lo',
                '_delete' => 'Lo',
            ],
        ];
        parent::init();
    }
}
