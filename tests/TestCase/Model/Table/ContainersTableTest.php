<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ContainersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ContainersTable Test Case
 */
class ContainersTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ContainersTable
     */
    public $Containers;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Containers',
        'app.Containertypes',
        'app.Automaps',
        'app.Autoreports',
        'app.Calendars',
        'app.ChangelogsToContainers',
        'app.Contactgroups',
        'app.ContactsToContainers',
        'app.GrafanaUserdashboards',
        'app.Hostdependencies',
        'app.Hostescalations',
        'app.Hostgroups',
        'app.Hosts',
        'app.HostsToContainers',
        'app.Hosttemplates',
        'app.IdoitObjects',
        'app.IdoitObjecttypes',
        'app.Instantreports',
        'app.Locations',
        'app.MapUploads',
        'app.MapsToContainers',
        'app.Mkagents',
        'app.NmapConfigurations',
        'app.RotationsToContainers',
        'app.Satellites',
        'app.Servicedependencies',
        'app.Serviceescalations',
        'app.Servicegroups',
        'app.Servicetemplategroups',
        'app.Servicetemplates',
        'app.Tenants',
        'app.Timeperiods',
        'app.UsersToContainers'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Containers') ? [] : ['className' => ContainersTable::class];
        $this->Containers = TableRegistry::getTableLocator()->get('Containers', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Containers);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
