<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

/**
 * Class Hyperscale
 *
 * Created:
 * oitc migrations create Hyperscale
 *
 * Usage:
 * openitcockpit-update
 */
class Hyperscale extends AbstractMigration {

    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change() {
        $table = $this->table('hosts');
        $table
            ->addColumn('node_id', 'integer', [
                'after'   => 'satellite_id',
                'default' => 0,
                'limit'   => 5,
                'null'    => false
            ])
            ->addColumn('reassign_node', 'boolean', [
                'after'   => 'node_id',
                'default' => 1,
                'limit'   => null,
                'null'    => false
            ])
            ->update();
    }
}
