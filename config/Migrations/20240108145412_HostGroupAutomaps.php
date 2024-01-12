<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class HostGroupAutomaps extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change(): void
    {

        if ($this->hasTable('automaps')) {
            $table = $this->table('automaps');
            $table->addColumn('hostgroup_regex', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])->update();
        }

    }



}
