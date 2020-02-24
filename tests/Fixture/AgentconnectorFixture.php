<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * AgentconnectorFixture
 */
class AgentconnectorFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public $table = 'agentconnector';
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'hostuuid' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null],
        'checksum' => ['type' => 'binary', 'length' => 255, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'ca_checksum' => ['type' => 'binary', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'generation_date' => ['type' => 'biginteger', 'length' => 20, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'remote_addr' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null],
        'http_x_forwarded_for' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null],
        'trusted' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8_general_ci'
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
                'hostuuid' => 'Lorem ipsum dolor sit amet',
                'checksum' => 'Lorem ipsum dolor sit amet',
                'ca_checksum' => 'Lorem ipsum dolor sit amet',
                'generation_date' => 1,
                'remote_addr' => 'Lorem ipsum dolor sit amet',
                'http_x_forwarded_for' => 'Lorem ipsum dolor sit amet',
                'trusted' => 1,
            ],
        ];
        parent::init();
    }
}
