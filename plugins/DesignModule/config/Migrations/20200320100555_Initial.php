<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

/**
 * Class Initial
 *
 * Created via:
 * oitc migrations create -p DesignModule Initial
 *
 * Run migration:
 * oitc migrations migrate -p DesignModule
 *
 */
class Initial extends AbstractMigration {
    /**
     * Whether the tables created in this migration
     * should auto-create an `id` field or not
     *
     * This option is global for all tables created in the migration file.
     * If you set it to false, you have to manually add the primary keys for your
     * tables using the Migrations\Table::addPrimaryKey() method
     *
     * @var bool
     */
    public $autoId = false;



    /**
     * Up Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-up-method
     * @return void
     */
    public function up() {
        if (!$this->hasTable('designs')) {
            $this->table('designs')
                ->addColumn('id', 'integer', [
                    'autoIncrement' => true,
                    'default'       => null,
                    'limit'         => 11,
                    'null'          => false,
                ])
                ->addPrimaryKey(['id'])
                ->addColumn('page_header', 'string', [
                    'default' => null,
                    'limit'   => 255,
                    'null'    => false,
                ])->addColumn('header-btn', 'string', [
                    'default' => null,
                    'limit'   => 255,
                    'null'    => false,
                ])->addColumn('page-sidebar', 'string', [ //.page-logo, .page-sidebar, .nav-footer, .bg-brand-gradient
                    'default' => null,
                    'limit'   => 255,
                    'null'    => false,
                ])->addColumn('nav-title', 'string', [
                    'default' => null,
                    'limit'   => 255,
                    'null'    => false,
                ])->addColumn('nav-menu', 'string', [ //.nav-menu li a  .nav-menu li i
                    'default' => null,
                    'limit'   => 255,
                    'null'    => false,
                ])->addColumn('nav-menu-hover', 'string', [ //.nav-menu li a:hover  .nav-menu li i:hover
                    'default' => null,
                    'limit'   => 255,
                    'null'    => false,
                ])->addColumn('nav-tabs', 'string', [ //.nav-tabs .nav-item .nav-link.active:not(:hover)
                    'default' => null,
                    'limit'   => 255,
                    'null'    => false,
                ])->addColumn('nav-tabs-hover', 'string', [
                    'default' => null,
                    'limit'   => 255,
                    'null'    => false,
                ])->addColumn('page-content', 'string', [
                    'default' => null,
                    'limit'   => 255,
                    'null'    => false,
                ])->addColumn('page-content-wrapper', 'string', [
                    'default' => null,
                    'limit'   => 255,
                    'null'    => false,
                ])->addColumn('panel-hdr', 'string', [
                    'default' => null,
                    'limit'   => 255,
                    'null'    => false,
                ])->addColumn('panel', 'string', [
                    'default' => null,
                    'limit'   => 255,
                    'null'    => false,
                ])->addColumn('breadcrumb-links', 'string', [ // breadcrumb links a
                    'default' => null,
                    'limit'   => 255,
                    'null'    => false,
                ])->addColumn('logo-in-header', 'integer', [ // breadcrumb links a
                    'default' => null,
                    'limit'   => 255,
                    'null'    => false,
                ])
                ->create();

        }
    }

    /**
     * Down Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-down-method
     * @return void
     */
    public function down() {
        $this->table('designs')->drop()->save();
    }

}
