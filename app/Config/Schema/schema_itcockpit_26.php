<?php

use itnovum\openITCOCKPIT\InitialDatabase;

class AppSchema extends CakeSchema {

    public function __construct($options = []) {
        parent::__construct($options);

        require_once OLD_APP . 'Model' . DS . 'Host.php';
        require_once OLD_APP . 'Model' . DS . 'Service.php';
        require_once OLD_APP . 'Model' . DS . 'Container.php';
    }

    public function before($event = []) {
        $db = ConnectionManager::getDataSource($this->connection);
        $db->cacheSources = false;
        return true;
    }

    public function after($event = []) {
        if (isset($event['update'])) {
            switch ($event['update']) {
                case 'commands':
                    $CommandsImporter = new InitialDatabase\Commands(ClassRegistry::init('Command'));
                    $CommandsImporter->import();
                    break;

                case 'containers':
                    $ContainerImporter = new InitialDatabase\Container(new Container(false, null, null, false));
                    $ContainerImporter->import();
                    break;

                case 'timeperiods':
                    $TimperiodsImporter = new InitialDatabase\Timeperiod(ClassRegistry::init('Timeperiod'));
                    $TimperiodsImporter->import();
                    break;

                case 'macros':
                    $MacrosImporter = new InitialDatabase\Macro(ClassRegistry::init('Macro'));
                    $MacrosImporter->import();
                    break;

                case 'cronjobs':
                    $CronjobsImporter = new InitialDatabase\Cronjob(ClassRegistry::init('Cronjob'));
                    $CronjobsImporter->import();
                    break;

                case 'systemsettings':
                    $SystemsettingsImporter = new InitialDatabase\Systemsetting(ClassRegistry::init('Systemsetting'));
                    $SystemsettingsImporter->import();
                    break;

                case 'contacts':
                    $ContactsImporter = new InitialDatabase\Contact(ClassRegistry::init('Contact'));
                    $ContactsImporter->import();
                    break;

                case 'hosttemplates':
                    $HosttemplatesImporter = new InitialDatabase\Hosttemplate(ClassRegistry::init('Hosttemplate'));
                    $HosttemplatesImporter->import();
                    break;

                case 'hosts':
                    $HostsImporter = new InitialDatabase\Host(new Host(false, null, null, false));
                    $HostsImporter->import();
                    break;

                case 'servicetemplates':
                    $ServicetemplatesImporter = new InitialDatabase\Servicetemplate(ClassRegistry::init('Servicetemplate'));
                    $ServicetemplatesImporter->import();
                    break;

                case 'services':
                    $ServicesImporter = new InitialDatabase\Service(new Service(false, null, null, false));
                    $ServicesImporter->import();
                    break;

                case 'acos':
                    $AcosImporter = new InitialDatabase\Aco(ClassRegistry::init('Aco'));
                    $AcosImporter->import();
                    break;

                case 'aros':
                    $ArosImporter = new InitialDatabase\Aro(ClassRegistry::init('Aro'));
                    $ArosImporter->import();
                    break;

                case 'usergroups':
                    $UsergroupsImporter = new InitialDatabase\Usergroup(ClassRegistry::init('Usergroup'));
                    $UsergroupsImporter->import();
            }
        }
    }

    public $changelogs = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'model'           => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'action'          => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'object_id'       => ['type' => 'integer', 'null' => false, 'default' => null],
        'objecttype_id'   => ['type' => 'integer', 'default' => null],
        'user_id'         => ['type' => 'integer', 'default' => null],
        'data'            => ['type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'name'            => ['type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'created'         => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'created' => ['column' => 'created'],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $changelogs_to_containers = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'changelog_id'    => ['type' => 'integer', 'null' => false, 'default' => null],
        'container_id'    => ['type' => 'integer', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY'      => ['column' => 'id', 'unique' => 1],
            'changelog_id' => ['column' => 'changelog_id', 'unique' => 0],
            'container_id' => ['column' => 'container_id', 'unique' => 0],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $commandarguments = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'command_id'      => ['type' => 'integer', 'null' => false, 'default' => null],
        'name'            => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 10, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'human_name'      => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'created'         => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'        => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'export'  => ['column' => ['command_id', 'name', 'human_name'], 'unique' => 0],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $commands = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'name'            => ['type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'command_line'    => ['type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'command_type'    => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 1],
        'human_args'      => ['type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'uuid'            => ['type' => 'string', 'null' => false, 'length' => 37, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'description'     => ['type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'uuid'    => ['column' => 'uuid', 'unique' => 1]
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $contactgroups = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'uuid'            => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 37, 'key' => 'unique', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'container_id'    => ['type' => 'integer', 'null' => false, 'default' => null],
        'description'     => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'uuid'    => ['column' => 'uuid', 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $contactgroups_to_hostescalations = [
        'id'                => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'contactgroup_id'   => ['type' => 'integer', 'null' => false, 'default' => null],
        'hostescalation_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'indexes'           => [
            'PRIMARY'           => ['column' => 'id', 'unique' => 1],
            'contactgroup_id'   => ['column' => 'contactgroup_id', 'unique' => 0],
            'hostescalation_id' => ['column' => 'hostescalation_id', 'unique' => 0],
        ],
        'tableParameters'   => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $contactgroups_to_hosts = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'contactgroup_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'host_id'         => ['type' => 'integer', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY'         => ['column' => 'id', 'unique' => 1],
            'contactgroup_id' => ['column' => 'contactgroup_id', 'unique' => 0],
            'host_id'         => ['column' => 'host_id', 'unique' => 0],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $contactgroups_to_hosttemplates = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'contactgroup_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'hosttemplate_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY'         => ['column' => 'id', 'unique' => 1],
            'contactgroup_id' => ['column' => 'contactgroup_id', 'unique' => 0],
            'hosttemplate_id' => ['column' => 'hosttemplate_id', 'unique' => 0],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $contactgroups_to_serviceescalations = [
        'id'                   => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'contactgroup_id'      => ['type' => 'integer', 'null' => false, 'default' => null],
        'serviceescalation_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'indexes'              => [
            'PRIMARY'              => ['column' => 'id', 'unique' => 1],
            'contactgroup_id'      => ['column' => 'contactgroup_id', 'unique' => 0],
            'serviceescalation_id' => ['column' => 'serviceescalation_id', 'unique' => 0],
        ],
        'tableParameters'      => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $contactgroups_to_services = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'contactgroup_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'service_id'      => ['type' => 'integer', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY'         => ['column' => 'id', 'unique' => 1],
            'contactgroup_id' => ['column' => 'contactgroup_id', 'unique' => 0],
            'service_id'      => ['column' => 'service_id', 'unique' => 0],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $contactgroups_to_servicetemplates = [
        'id'                 => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'contactgroup_id'    => ['type' => 'integer', 'null' => false, 'default' => null],
        'servicetemplate_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'indexes'            => [
            'PRIMARY'            => ['column' => 'id', 'unique' => 1],
            'contactgroup_id'    => ['column' => 'contactgroup_id', 'unique' => 0],
            'servicetemplate_id' => ['column' => 'servicetemplate_id', 'unique' => 0],
        ],
        'tableParameters'    => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $contacts_to_contactgroups = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'contact_id'      => ['type' => 'integer', 'null' => false, 'default' => null],
        'contactgroup_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY'         => ['column' => 'id', 'unique' => 1],
            'contact_id'      => ['column' => 'contact_id', 'unique' => 0],
            'contactgroup_id' => ['column' => 'contactgroup_id', 'unique' => 0],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $contacts_to_containers = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'contact_id'      => ['type' => 'integer', 'null' => false, 'default' => null],
        'container_id'    => ['type' => 'integer', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY'      => ['column' => 'id', 'unique' => 1],
            'contact_id'   => ['column' => 'contact_id', 'unique' => 0],
            'container_id' => ['column' => 'container_id', 'unique' => 0],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $contacts_to_hostcommands = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'contact_id'      => ['type' => 'integer', 'null' => false, 'default' => null],
        'command_id'      => ['type' => 'integer', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY'    => ['column' => 'id', 'unique' => 1],
            'contact_id' => ['column' => 'contact_id', 'unique' => 0],
            'command_id' => ['column' => 'command_id', 'unique' => 0],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $contacts_to_hostescalations = [
        'id'                => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'contact_id'        => ['type' => 'integer', 'null' => false, 'default' => null],
        'hostescalation_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'indexes'           => [
            'PRIMARY'           => ['column' => 'id', 'unique' => 1],
            'contact_id'        => ['column' => 'contact_id', 'unique' => 0],
            'hostescalation_id' => ['column' => 'hostescalation_id', 'unique' => 0],
        ],
        'tableParameters'   => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $contacts_to_hosts = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'contact_id'      => ['type' => 'integer', 'null' => false, 'default' => null],
        'host_id'         => ['type' => 'integer', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY'    => ['column' => 'id', 'unique' => 1],
            'contact_id' => ['column' => 'contact_id', 'unique' => 0],
            'host_id'    => ['column' => 'host_id', 'unique' => 0],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $contacts_to_hosttemplates = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'contact_id'      => ['type' => 'integer', 'null' => false, 'default' => null],
        'hosttemplate_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY'         => ['column' => 'id', 'unique' => 1],
            'contact_id'      => ['column' => 'contact_id', 'unique' => 0],
            'hosttemplate_id' => ['column' => 'hosttemplate_id', 'unique' => 0],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $contacts_to_servicecommands = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'contact_id'      => ['type' => 'integer', 'null' => false, 'default' => null],
        'command_id'      => ['type' => 'integer', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY'    => ['column' => 'id', 'unique' => 1],
            'contact_id' => ['column' => 'contact_id', 'unique' => 0],
            'command_id' => ['column' => 'command_id', 'unique' => 0],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $contacts_to_serviceescalations = [
        'id'                   => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'contact_id'           => ['type' => 'integer', 'null' => false, 'default' => null],
        'serviceescalation_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'indexes'              => [
            'PRIMARY'              => ['column' => 'id', 'unique' => 1],
            'contact_id'           => ['column' => 'contact_id', 'unique' => 0],
            'serviceescalation_id' => ['column' => 'serviceescalation_id', 'unique' => 0],
        ],
        'tableParameters'      => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $contacts_to_services = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'contact_id'      => ['type' => 'integer', 'null' => false, 'default' => null],
        'service_id'      => ['type' => 'integer', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY'    => ['column' => 'id', 'unique' => 1],
            'contact_id' => ['column' => 'contact_id', 'unique' => 0],
            'service_id' => ['column' => 'service_id', 'unique' => 0],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $contacts_to_servicetemplates = [
        'id'                 => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'contact_id'         => ['type' => 'integer', 'null' => false, 'default' => null],
        'servicetemplate_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'indexes'            => [
            'PRIMARY'            => ['column' => 'id', 'unique' => 1],
            'contact_id'         => ['column' => 'contact_id', 'unique' => 0],
            'servicetemplate_id' => ['column' => 'servicetemplate_id', 'unique' => 0],
        ],
        'tableParameters'    => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $containers = [
        'id'               => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'containertype_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'name'             => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'parent_id'        => ['type' => 'integer', 'null' => true, 'default' => null],
        'lft'              => ['type' => 'integer', 'null' => false, 'default' => null],
        'rght'             => ['type' => 'integer', 'null' => false, 'default' => null],
        'indexes'          => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters'  => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $customvariables = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'object_id'       => ['type' => 'integer', 'null' => false, 'default' => null],
        'objecttype_id'   => ['type' => 'integer', 'null' => false, 'default' => null],
        'name'            => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'value'           => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'created'         => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'        => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $documentations = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'uuid'            => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 37, 'key' => 'unique', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'content'         => ['type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'created'         => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'        => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'uuid'    => ['column' => 'uuid', 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $exports = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'task'            => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'text'            => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'finished'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'successfully'    => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'created'         => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'        => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'task'    => ['column' => ['task', 'text', 'finished', 'successfully'], 'unique' => 0]
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $hostcommandargumentvalues = [
        'id'                 => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'commandargument_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'host_id'            => ['type' => 'integer', 'null' => false, 'default' => null],
        'value'              => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'created'            => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'           => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'            => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters'    => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $hostescalations = [
        'id'                      => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'uuid'                    => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 37, 'key' => 'unique', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'container_id'            => ['type' => 'integer', 'null' => false, 'default' => null],
        'timeperiod_id'           => ['type' => 'integer', 'default' => null],
        'first_notification'      => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 6],
        'last_notification'       => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 6],
        'notification_interval'   => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 6],
        'escalate_on_recovery'    => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 1],
        'escalate_on_down'        => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 1],
        'escalate_on_unreachable' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 1],
        'created'                 => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'                => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'                 => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'uuid'    => ['column' => 'uuid', 'unique' => 1]
        ],
        'tableParameters'         => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $hostgroups = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'uuid'            => ['type' => 'string', 'null' => false, 'length' => 37],
        'container_id'    => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'description'     => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'hostgroup_url'   => ['type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'uuid'    => ['column' => 'uuid', 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $hostgroups_to_hostescalations = [
        'id'                => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'hostgroup_id'      => ['type' => 'integer', 'null' => false, 'default' => null],
        'hostescalation_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'excluded'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 4],
        'indexes'           => [
            'PRIMARY'           => ['column' => 'id', 'unique' => 1],
            'hostgroup_id'      => ['column' => ['hostgroup_id', 'excluded'], 'unique' => 0],
            'hostescalation_id' => ['column' => ['hostescalation_id', 'excluded'], 'unique' => 0],
        ],
        'tableParameters'   => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $hosts_to_containers = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'host_id'         => ['type' => 'integer', 'null' => false, 'default' => null],
        'container_id'    => ['type' => 'integer', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY'      => ['column' => 'id', 'unique' => 1],
            'host_id'      => ['column' => 'host_id', 'unique' => 0],
            'container_id' => ['column' => 'container_id', 'unique' => 0],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $hosts_to_hostescalations = [
        'id'                => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'host_id'           => ['type' => 'integer', 'null' => false, 'default' => null],
        'hostescalation_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'excluded'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 4],
        'indexes'           => [
            'PRIMARY'           => ['column' => 'id', 'unique' => 1],
            'host_id'           => ['column' => ['host_id', 'excluded'], 'unique' => 0],
            'hostescalation_id' => ['column' => ['hostescalation_id', 'excluded'], 'unique' => 0],
        ],
        'tableParameters'   => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $hosts_to_hostgroups = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'host_id'         => ['type' => 'integer', 'null' => false, 'default' => null],
        'hostgroup_id'    => ['type' => 'integer', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY'      => ['column' => 'id', 'unique' => 1],
            'host_id'      => ['column' => 'host_id', 'unique' => 0],
            'hostgroup_id' => ['column' => 'hostgroup_id', 'unique' => 0],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $hosts_to_parenthosts = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'host_id'         => ['type' => 'integer', 'null' => false],
        'parenthost_id'   => ['type' => 'integer', 'null' => false],
        'indexes'         => [
            'PRIMARY'       => ['column' => 'id', 'unique' => 1],
            'host_id'       => ['column' => 'host_id', 'unique' => 0],
            'parenthost_id' => ['column' => 'parenthost_id', 'unique' => 0],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $locations = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'uuid'            => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 37, 'key' => 'unique', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'container_id'    => ['type' => 'integer', 'null' => false, 'default' => null],
        'description'     => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'latitude'        => ['type' => 'float', 'null' => true],
        'longitude'       => ['type' => 'float', 'null' => true],
        'timezone'        => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'created'         => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'        => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'uuid'    => ['column' => 'uuid', 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $macros = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'name'            => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 10, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'value'           => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'description'     => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'password'        => ['type' => 'integer', 'null' => false, 'default' => 0, 'length' => 1],
        'created'         => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'        => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $proxies = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'ipaddress'       => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'port'            => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 5],
        'enabled'         => ['type' => 'boolean', 'null' => false, 'default' => '1'],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];


    public $servicecommandargumentvalues = [
        'id'                 => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'commandargument_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'service_id'         => ['type' => 'integer', 'null' => false, 'default' => null],
        'value'              => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'created'            => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'           => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'            => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters'    => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $serviceeventcommandargumentvalues = [
        'id'                 => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'commandargument_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'service_id'         => ['type' => 'integer', 'null' => false, 'default' => null],
        'value'              => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'created'            => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'           => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'            => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters'    => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $serviceescalations = [
        'id'                    => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'uuid'                  => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 37, 'key' => 'unique', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'container_id'          => ['type' => 'integer', 'null' => true, 'default' => null],
        'timeperiod_id'         => ['type' => 'integer', 'default' => null],
        'first_notification'    => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 6],
        'last_notification'     => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 6],
        'notification_interval' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 6],
        'escalate_on_recovery'  => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 1],
        'escalate_on_warning'   => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 1],
        'escalate_on_unknown'   => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 1],
        'escalate_on_critical'  => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 1],
        'created'               => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'              => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'               => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'uuid'    => ['column' => 'uuid', 'unique' => 1]
        ],
        'tableParameters'       => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $servicegroups = [
        'id'               => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'uuid'             => ['type' => 'string', 'null' => false, 'length' => 37],
        'container_id'     => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'description'      => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'servicegroup_url' => ['type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'          => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'uuid'    => ['column' => 'uuid', 'unique' => 1],
        ],
        'tableParameters'  => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $servicegroups_to_serviceescalations = [
        'id'                   => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'servicegroup_id'      => ['type' => 'integer', 'null' => false, 'default' => null],
        'serviceescalation_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'excluded'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 4],
        'indexes'              => [
            'PRIMARY'              => ['column' => 'id', 'unique' => 1],
            'servicegroup_id'      => ['column' => ['servicegroup_id', 'excluded'], 'unique' => 0],
            'serviceescalation_id' => ['column' => ['serviceescalation_id', 'excluded'], 'unique' => 0],
        ],
        'tableParameters'      => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $services_to_serviceescalations = [
        'id'                   => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'service_id'           => ['type' => 'integer', 'null' => false, 'default' => null],
        'serviceescalation_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'excluded'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 4],
        'indexes'              => [
            'PRIMARY'              => ['column' => 'id', 'unique' => 1],
            'service_id'           => ['column' => ['service_id', 'excluded'], 'unique' => 0],
            'serviceescalation_id' => ['column' => ['serviceescalation_id', 'excluded'], 'unique' => 0],
        ],
        'tableParameters'      => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $services_to_servicegroups = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'service_id'      => ['type' => 'integer', 'null' => false, 'default' => null],
        'servicegroup_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY'         => ['column' => 'id', 'unique' => 1],
            'service_id'      => ['column' => 'service_id', 'unique' => 0],
            'servicegroup_id' => ['column' => 'servicegroup_id', 'unique' => 0],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $systemfailures = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'start_time'      => ['type' => 'datetime', 'null' => false, 'default' => null],
        'end_time'        => ['type' => 'datetime', 'null' => false, 'default' => null],
        'comment'         => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'user_id'         => ['type' => 'integer', 'null' => true, 'default' => null],
        'created'         => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'        => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $tenants = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'container_id'    => ['type' => 'integer', 'null' => false, 'default' => null],
        'description'     => ['type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'is_active'       => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 1],
        'number_users'    => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 6],
        'max_users'       => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 6],
        'number_hosts'    => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 6],
        'number_services' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 6],
        'firstname'       => ['type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'lastname'        => ['type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'street'          => ['type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'zipcode'         => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 6],
        'city'            => ['type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'created'         => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'        => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $timeperiod_timeranges = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'timeperiod_id'   => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'day'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'start'           => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 5, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'end'             => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 5, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $timeperiods = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'uuid'            => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 37, 'key' => 'unique', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'container_id'    => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 6],
        'name'            => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'description'     => ['type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'calendar_id'     => ['type' => 'integer', 'null' => true, 'default' => 0, 'length' => 6],
        'created'         => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'        => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'uuid'    => ['column' => 'uuid', 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $contacts = [
        'id'                                 => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'uuid'                               => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 37, 'key' => 'unique', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'name'                               => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'description'                        => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'email'                              => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'phone'                              => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'user_id'                            => ['type' => 'integer', 'null' => true, 'default' => null],
        'host_timeperiod_id'                 => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'service_timeperiod_id'              => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'host_notifications_enabled'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'service_notifications_enabled'      => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'notify_service_recovery'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'notify_service_warning'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'notify_service_unknown'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'notify_service_critical'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'notify_service_flapping'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'notify_service_downtime'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'notify_host_recovery'               => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'notify_host_down'                   => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'notify_host_unreachable'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'notify_host_flapping'               => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'notify_host_downtime'               => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'host_push_notifications_enabled'    => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'service_push_notifications_enabled' => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'indexes'                            => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'uuid'    => ['column' => 'uuid', 'unique' => 1],
            'push'    => ['column' => ['user_id', 'host_push_notifications_enabled', 'service_push_notifications_enabled'], 'unique' => 0],
        ],
        'tableParameters'                    => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $hosttemplatecommandargumentvalues = [
        'id'                 => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'commandargument_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'hosttemplate_id'    => ['type' => 'integer', 'null' => false, 'default' => null],
        'value'              => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'created'            => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'           => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'            => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters'    => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $hosttemplates = [
        'id'                            => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'uuid'                          => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 37, 'key' => 'unique', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'name'                          => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'description'                   => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'hosttemplatetype_id'           => ['type' => 'integer', 'null' => false, 'default' => 1],
        'command_id'                    => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'check_command_args'            => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'eventhandler_command_id'       => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'timeperiod_id'                 => ['type' => 'integer', 'null' => false, 'default' => null],
        'check_interval'                => ['type' => 'integer', 'null' => false, 'default' => '1', 'length' => 5],
        'retry_interval'                => ['type' => 'integer', 'null' => false, 'default' => '3', 'length' => 5],
        'max_check_attempts'            => ['type' => 'integer', 'null' => false, 'default' => '1', 'length' => 3],
        'first_notification_delay'      => ['type' => 'float', 'null' => false, 'default' => '0'],
        'notification_interval'         => ['type' => 'float', 'null' => false, 'default' => '0'],
        'notify_on_down'                => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'notify_on_unreachable'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'notify_on_recovery'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'notify_on_flapping'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'notify_on_downtime'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'flap_detection_enabled'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'flap_detection_on_up'          => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'flap_detection_on_down'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'flap_detection_on_unreachable' => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'low_flap_threshold'            => ['type' => 'float', 'null' => false, 'default' => '0'],
        'high_flap_threshold'           => ['type' => 'float', 'null' => false, 'default' => '0'],
        'process_performance_data'      => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'freshness_checks_enabled'      => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'freshness_threshold'           => ['type' => 'integer', 'null' => true, 'default' => '0', 'length' => 8],
        'passive_checks_enabled'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'event_handler_enabled'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'active_checks_enabled'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'retain_status_information'     => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'retain_nonstatus_information'  => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'notifications_enabled'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'notes'                         => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'priority'                      => ['type' => 'integer', 'null' => false, 'default' => '1', 'length' => 2],
        'check_period_id'               => ['type' => 'integer', 'null' => false, 'default' => null],
        'notify_period_id'              => ['type' => 'integer', 'null' => false, 'default' => null],
        'tags'                          => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'container_id'                  => ['type' => 'integer', 'null' => false, 'default' => null],
        'host_url'                      => ['type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'created'                       => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'                      => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'                       => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'uuid'    => ['column' => 'uuid', 'unique' => 1],
        ],
        'tableParameters'               => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $hosttemplates_to_hostgroups = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'hosttemplate_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'hostgroup_id'    => ['type' => 'integer', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY'         => ['column' => 'id', 'unique' => 1],
            'hosttemplate_id' => ['column' => 'hosttemplate_id', 'unique' => 0],
            'hostgroup_id'    => ['column' => 'hostgroup_id', 'unique' => 0],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $hosts = [
        'id'                            => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'uuid'                          => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 37, 'key' => 'unique', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'container_id'                  => ['type' => 'integer', 'null' => false, 'default' => null],
        'name'                          => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'description'                   => ['type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'hosttemplate_id'               => ['type' => 'integer', 'null' => false, 'default' => null],
        'address'                       => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 128, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'command_id'                    => ['type' => 'integer', 'null' => true, 'default' => null],
        'eventhandler_command_id'       => ['type' => 'integer', 'null' => true, 'default' => null],
        'timeperiod_id'                 => ['type' => 'integer', 'null' => true, 'default' => null],
        'check_interval'                => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 5],
        'retry_interval'                => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 5],
        'max_check_attempts'            => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 3],
        'first_notification_delay'      => ['type' => 'float', 'null' => true, 'default' => null],
        'notification_interval'         => ['type' => 'float', 'null' => true, 'default' => null],
        'notify_on_down'                => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 1],
        'notify_on_unreachable'         => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 1],
        'notify_on_recovery'            => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 1],
        'notify_on_flapping'            => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 1],
        'notify_on_downtime'            => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 1],
        'flap_detection_enabled'        => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 1],
        'flap_detection_on_up'          => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 1],
        'flap_detection_on_down'        => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 1],
        'flap_detection_on_unreachable' => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 1],
        'low_flap_threshold'            => ['type' => 'float', 'null' => true, 'default' => null],
        'high_flap_threshold'           => ['type' => 'float', 'null' => true, 'default' => null],
        'process_performance_data'      => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 6],
        'freshness_checks_enabled'      => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 6],
        'freshness_threshold'           => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 8],
        'passive_checks_enabled'        => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 1],
        'event_handler_enabled'         => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 1],
        'active_checks_enabled'         => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 1],
        'retain_status_information'     => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 6],
        'retain_nonstatus_information'  => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 6],
        'notifications_enabled'         => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 6],
        'notes'                         => ['type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'priority'                      => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 2],
        'check_period_id'               => ['type' => 'integer', 'null' => true, 'default' => null],
        'notify_period_id'              => ['type' => 'integer', 'null' => true, 'default' => null],
        'tags'                          => ['type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'own_contacts'                  => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'own_contactgroups'             => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'own_customvariables'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'host_url'                      => ['type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'satellite_id'                  => ['type' => 'integer', 'default' => 0],
        'host_type'                     => ['type' => 'integer', 'null' => false, 'default' => 1],
        'disabled'                      => ['type' => 'integer', 'null' => true, 'default' => 0, 'length' => 1],
        'usage_flag'                    => ['type' => 'integer', 'null' => false, 'default' => null],
        'created'                       => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'                      => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'                       => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'uuid'    => ['column' => 'uuid', 'unique' => 1],
        ],
        'tableParameters'               => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $servicetemplatecommandargumentvalues = [
        'id'                 => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'commandargument_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'servicetemplate_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'value'              => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'created'            => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'           => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'            => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters'    => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $servicetemplateeventcommandargumentvalues = [
        'id'                 => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'commandargument_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'servicetemplate_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'value'              => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'created'            => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'           => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'            => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters'    => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $servicetemplategroups = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'uuid'            => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 37, 'key' => 'unique', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'container_id'    => ['type' => 'integer', 'null' => false, 'default' => null],
        'description'     => ['type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'created'         => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'        => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'uuid'    => ['column' => 'uuid', 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $servicetemplates_to_servicegroups = [
        'id'                 => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'servicetemplate_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'servicegroup_id'    => ['type' => 'integer', 'null' => false, 'default' => null],
        'indexes'            => [
            'PRIMARY'            => ['column' => 'id', 'unique' => 1],
            'servicetemplate_id' => ['column' => 'servicetemplate_id', 'unique' => 0],
            'servicegroup_id'    => ['column' => 'servicegroup_id', 'unique' => 0],
        ],
        'tableParameters'    => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $servicetemplates_to_servicetemplategroups = [
        'id'                      => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'servicetemplate_id'      => ['type' => 'integer', 'null' => false, 'default' => null],
        'servicetemplategroup_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'indexes'                 => [
            'PRIMARY'                 => ['column' => 'id', 'unique' => 1],
            'servicetemplategroup_id' => ['column' => 'servicetemplategroup_id', 'unique' => 0],
            'servicetemplate_id'      => ['column' => 'servicetemplate_id', 'unique' => 0],
        ],
        'tableParameters'         => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $servicetemplates = [
        'id'                           => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'uuid'                         => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 37, 'key' => 'unique', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'template_name'                => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'name'                         => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'container_id'                 => ['type' => 'integer', 'null' => true, 'default' => null],
        'servicetemplatetype_id'       => ['type' => 'integer', 'null' => false, 'default' => 1],
        'check_period_id'              => ['type' => 'integer', 'null' => true, 'default' => null],
        'notify_period_id'             => ['type' => 'integer', 'null' => true, 'default' => null],
        'description'                  => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'command_id'                   => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'check_command_args'           => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'checkcommand_info'            => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'eventhandler_command_id'      => ['type' => 'integer', 'null' => false, 'default' => '0'],
        'timeperiod_id'                => ['type' => 'integer', 'null' => true, 'default' => null],
        'check_interval'               => ['type' => 'integer', 'null' => false, 'default' => '1', 'length' => 5],
        'retry_interval'               => ['type' => 'integer', 'null' => false, 'default' => '3', 'length' => 5],
        'max_check_attempts'           => ['type' => 'integer', 'null' => false, 'default' => '1', 'length' => 3],
        'first_notification_delay'     => ['type' => 'float', 'null' => false, 'default' => '0'],
        'notification_interval'        => ['type' => 'float', 'null' => false, 'default' => '0'],
        'notify_on_warning'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'notify_on_unknown'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'notify_on_critical'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'notify_on_recovery'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'notify_on_flapping'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'notify_on_downtime'           => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'flap_detection_enabled'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'flap_detection_on_ok'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'flap_detection_on_warning'    => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'flap_detection_on_unknown'    => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'flap_detection_on_critical'   => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'low_flap_threshold'           => ['type' => 'float', 'null' => false, 'default' => '0'],
        'high_flap_threshold'          => ['type' => 'float', 'null' => false, 'default' => '0'],
        'process_performance_data'     => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'freshness_checks_enabled'     => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'freshness_threshold'          => ['type' => 'integer', 'null' => true, 'default' => '0', 'length' => 8],
        'passive_checks_enabled'       => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'event_handler_enabled'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'active_checks_enabled'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'retain_status_information'    => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'retain_nonstatus_information' => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'notifications_enabled'        => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6],
        'notes'                        => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'priority'                     => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 2],
        'tags'                         => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 1500, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'is_volatile'                  => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'check_freshness'              => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'service_url'                  => ['type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'created'                      => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'                     => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'                      => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'uuid'    => ['column' => 'uuid', 'unique' => 1],
        ],
        'tableParameters'              => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $services = [
        'id'                         => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'uuid'                       => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 37, 'key' => 'unique', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'servicetemplate_id'         => ['type' => 'integer', 'null' => false, 'default' => null],
        'host_id'                    => ['type' => 'integer', 'null' => false, 'default' => null],
        'name'                       => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 1500, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'description'                => ['type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'command_id'                 => ['type' => 'integer', 'null' => true, 'default' => null],
        'check_command_args'         => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'eventhandler_command_id'    => ['type' => 'integer', 'null' => true, 'default' => null],
        'notify_period_id'           => ['type' => 'integer', 'null' => true, 'default' => null],
        'check_period_id'            => ['type' => 'integer', 'null' => true, 'default' => null],
        'check_interval'             => ['type' => 'float', 'null' => true, 'default' => null],
        'retry_interval'             => ['type' => 'float', 'null' => true, 'default' => null],
        'max_check_attempts'         => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 6],
        'first_notification_delay'   => ['type' => 'float', 'null' => true, 'default' => null],
        'notification_interval'      => ['type' => 'float', 'null' => true, 'default' => null],
        'notify_on_warning'          => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 1],
        'notify_on_unknown'          => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 1],
        'notify_on_critical'         => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 1],
        'notify_on_recovery'         => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 1],
        'notify_on_flapping'         => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 1],
        'notify_on_downtime'         => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 1],
        'is_volatile'                => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 1],
        'flap_detection_enabled'     => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 1],
        'flap_detection_on_ok'       => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 1],
        'flap_detection_on_warning'  => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 1],
        'flap_detection_on_unknown'  => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 1],
        'flap_detection_on_critical' => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 1],
        'low_flap_threshold'         => ['type' => 'float', 'null' => true, 'default' => null],
        'high_flap_threshold'        => ['type' => 'float', 'null' => true, 'default' => null],
        'process_performance_data'   => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 6],
        'freshness_checks_enabled'   => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 8],
        'freshness_threshold'        => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 6],
        'passive_checks_enabled'     => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 6],
        'event_handler_enabled'      => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 6],
        'active_checks_enabled'      => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 6],
        'notifications_enabled'      => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 6],
        'notes'                      => ['type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'priority'                   => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 2],
        'tags'                       => ['type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'own_contacts'               => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 1],
        'own_contactgroups'          => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 1],
        'own_customvariables'        => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 1],
        'service_url'                => ['type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'service_type'               => ['type' => 'integer', 'null' => false, 'default' => 1],
        'disabled'                   => ['type' => 'integer', 'null' => true, 'default' => 0, 'length' => 1],
        'usage_flag'                 => ['type' => 'integer', 'null' => false, 'default' => null],
        'created'                    => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'                   => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'                    => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'uuid'    => ['column' => 'uuid', 'unique' => 1],
            'export'  => ['column' => ['uuid', 'host_id', 'disabled'], 'unique' => 0],
            'host_id' => ['column' => ['host_id', 'disabled'], 'unique' => 0],
        ],
        'tableParameters'            => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $users = [
        'id'                     => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'primary'],
        'usergroup_id'           => ['type' => 'integer', 'null' => false, 'default' => null],
        'email'                  => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
        'password'               => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 45, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
        'firstname'              => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
        'lastname'               => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
        'position'               => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
        'company'                => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
        'phone'                  => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
        'timezone'               => ['type' => 'string', 'null' => true, 'default' => 'Europe/Berlin', 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
        'dateformat'             => ['type' => 'string', 'null' => true, 'default' => '%H:%M:%S - %d.%m.%Y', 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
        'image'                  => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
        'onetimetoken'           => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
        'samaccountname'         => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 128, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
        'ldap_dn'                => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 512, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
        'showstatsinmenu'        => ['type' => 'boolean', 'null' => false, 'default' => '0'],
        'is_active'              => ['type' => 'boolean', 'null' => false, 'default' => '0'],
        'dashboard_tab_rotation' => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 10],
        'paginatorlength'        => ['type' => 'integer', 'null' => false, 'default' => '25', 'length' => 4],
        'recursive_browser'      => ['type' => 'boolean', 'null' => false, 'default' => '0'],
        'created'                => ['type' => 'datetime', 'null' => true, 'default' => null],
        'modified'               => ['type' => 'datetime', 'null' => true, 'default' => null],
        'indexes'                => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters'        => ['charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'],
    ];

    public $users_to_usercontainerroles = [
        'id'                   => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'user_id'              => ['type' => 'integer', 'null' => false, 'default' => null],
        'usercontainerrole_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'indexes'              => [
            'PRIMARY'              => ['column' => 'id', 'unique' => 1],
            'user_id'              => ['column' => 'user_id', 'unique' => 0],
            'usercontainerrole_id' => ['column' => 'usercontainerrole_id', 'unique' => 0],
        ],
        'tableParameters'      => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];


    public $usercontainerroles = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'primary'],
        'name'            => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'],
    ];

    public $usercontainerroles_to_containers = [
        'id'                   => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'usercontainerrole_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'container_id'         => ['type' => 'integer', 'null' => false, 'default' => null],
        'permission_level'     => ['type' => 'integer', 'null' => false, 'default' => 1, 'length' => 1],
        'indexes'              => [
            'PRIMARY'              => ['column' => 'id', 'unique' => 1],
            'usercontainerrole_id' => ['column' => 'usercontainerrole_id', 'unique' => 0],
            'container_id'         => ['column' => 'container_id', 'unique' => 0],
        ],
        'tableParameters'      => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $systemsettings = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'key'             => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'value'           => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'info'            => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8', 'length' => 1500,],
        'section'         => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'created'         => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'        => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $hostdependencies = [
        'id'                               => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'uuid'                             => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 37, 'key' => 'unique', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'container_id'                     => ['type' => 'integer', 'null' => true, 'default' => null],
        'inherits_parent'                  => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'timeperiod_id'                    => ['type' => 'integer', 'null' => true, 'default' => null],
        'execution_fail_on_up'             => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 1],
        'execution_fail_on_down'           => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 1],
        'execution_fail_on_unreachable'    => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 1],
        'execution_fail_on_pending'        => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 1],
        'execution_none'                   => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 1],
        'notification_fail_on_up'          => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 1],
        'notification_fail_on_down'        => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 1],
        'notification_fail_on_unreachable' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 1],
        'notification_fail_on_pending'     => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 1],
        'notification_none'                => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 1],
        'created'                          => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'                         => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'                          => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'uuid'    => ['column' => 'uuid', 'unique' => 1]
        ],
        'tableParameters'                  => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $hosts_to_hostdependencies = [
        'id'                => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'host_id'           => ['type' => 'integer', 'null' => false, 'default' => null],
        'hostdependency_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'dependent'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'indexes'           => [
            'PRIMARY'           => ['column' => 'id', 'unique' => 1],
            'host_id'           => ['column' => ['host_id', 'dependent'], 'unique' => 0],
            'hostdependency_id' => ['column' => ['hostdependency_id', 'dependent'], 'unique' => 0],
        ],
        'tableParameters'   => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $hostgroups_to_hostdependencies = [
        'id'                => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'hostgroup_id'      => ['type' => 'integer', 'null' => false, 'default' => null],
        'hostdependency_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'dependent'         => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'indexes'           => [
            'PRIMARY'           => ['column' => 'id', 'unique' => 1],
            'hostgroup_id'      => ['column' => ['hostgroup_id', 'dependent'], 'unique' => 0],
            'hostdependency_id' => ['column' => ['hostdependency_id', 'dependent'], 'unique' => 0],
        ],
        'tableParameters'   => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $servicedependencies = [
        'id'                            => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'uuid'                          => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 37, 'key' => 'unique', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'container_id'                  => ['type' => 'integer', 'null' => true, 'default' => null],
        'inherits_parent'               => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'timeperiod_id'                 => ['type' => 'integer', 'null' => true, 'default' => null],
        'execution_fail_on_ok'          => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 1],
        'execution_fail_on_warning'     => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 1],
        'execution_fail_on_unknown'     => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 1],
        'execution_fail_on_critical'    => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 1],
        'execution_fail_on_pending'     => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 1],
        'execution_none'                => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 1],
        'notification_fail_on_ok'       => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 1],
        'notification_fail_on_warning'  => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 1],
        'notification_fail_on_unknown'  => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 1],
        'notification_fail_on_critical' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 1],
        'notification_fail_on_pending'  => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 1],
        'notification_none'             => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 1],
        'created'                       => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'                      => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'                       => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'uuid'    => ['column' => 'uuid', 'unique' => 1]
        ],
        'tableParameters'               => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $services_to_servicedependencies = [
        'id'                   => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'service_id'           => ['type' => 'integer', 'null' => false, 'default' => null],
        'servicedependency_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'dependent'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'indexes'              => [
            'PRIMARY'              => ['column' => 'id', 'unique' => 1],
            'service_id'           => ['column' => ['service_id', 'dependent'], 'unique' => 0],
            'servicedependency_id' => ['column' => ['servicedependency_id', 'dependent'], 'unique' => 0],
        ],
        'tableParameters'      => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $servicegroups_to_servicedependencies = [
        'id'                   => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'servicegroup_id'      => ['type' => 'integer', 'null' => false, 'default' => null],
        'servicedependency_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'dependent'            => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'indexes'              => [
            'PRIMARY'              => ['column' => 'id', 'unique' => 1],
            'servicegroup_id'      => ['column' => ['servicegroup_id', 'dependent'], 'unique' => 0],
            'servicedependency_id' => ['column' => ['servicedependency_id', 'dependent'], 'unique' => 0],
        ],
        'tableParameters'      => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $systemdowntimes = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'objecttype_id'   => ['type' => 'integer', 'default' => null],
        'object_id'       => ['type' => 'integer', 'default' => null],
        'downtimetype_id' => ['type' => 'integer', 'default' => 0],
        'weekdays'        => ['type' => 'string', 'default' => null],
        'day_of_month'    => ['type' => 'string', 'default' => null],
        'from_time'       => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'to_time'         => ['type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'duration'        => ['type' => 'integer', 'null' => false, 'default' => 0],
        'is_recursive'    => ['type' => 'integer', 'null' => false, 'default' => 0],
        'comment'         => ['type' => 'string', 'default' => null],
        'author'          => ['type' => 'string', 'default' => null],
        'created'         => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'        => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $cronschedules = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'cronjob_id'      => ['type' => 'integer', 'default' => null],
        'is_running'      => ['type' => 'integer', 'default' => null],
        'start_time'      => ['type' => 'datetime', 'null' => false, 'default' => null],
        'end_time'        => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $cronjobs = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'task'            => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'plugin'          => ['type' => 'string', 'null' => false, 'default' => 'Core', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'interval'        => ['type' => 'integer', 'default' => null],
        'enabled'         => ['type' => 'boolean', 'length' => 1, 'default' => 1, 'null' => false],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $users_to_containers = [
        'id'               => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'user_id'          => ['type' => 'integer', 'null' => false, 'default' => null],
        'container_id'     => ['type' => 'integer', 'null' => false, 'default' => null],
        'permission_level' => ['type' => 'integer', 'null' => false, 'default' => 1, 'length' => 1],
        'indexes'          => [
            'PRIMARY'      => ['column' => 'id', 'unique' => 1],
            'user_id'      => ['column' => 'user_id', 'unique' => 0],
            'container_id' => ['column' => 'container_id', 'unique' => 0],
        ],
        'tableParameters'  => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $deleted_services = [
        'id'                 => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'uuid'               => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 37, 'key' => 'unique', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'host_uuid'          => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 37, 'key' => 'unique', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'servicetemplate_id' => ['type' => 'integer', 'null' => false, 'default' => null], 'host_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'name'               => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 255, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'description'        => ['type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'deleted_perfdata'   => ['type' => 'integer', 'null' => true, 'default' => 0, 'length' => 1],
        'created'            => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'           => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'            => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'uuid'    => ['column' => 'uuid', 'unique' => 1],
        ],
        'tableParameters'    => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $deleted_hosts = [
        'id'               => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'uuid'             => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 37, 'key' => 'unique', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'hosttemplate_id'  => ['type' => 'integer', 'null' => false, 'default' => null], 'host_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'name'             => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 255, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'description'      => ['type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'deleted_perfdata' => ['type' => 'integer', 'null' => true, 'default' => 0, 'length' => 1],
        'created'          => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'         => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'          => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'uuid'    => ['column' => 'uuid', 'unique' => 1],
        ],
        'tableParameters'  => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $registers = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'license'         => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 37, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'accepted'        => ['type' => 'boolean', 'null' => false, 'default' => '0'],
        'apt'             => ['type' => 'boolean', 'null' => false, 'default' => '0'],
        'created'         => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'        => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $calendars = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'name'            => ['type' => 'string', 'null' => false],
        'description'     => ['type' => 'string', 'null' => false, 'default' => ''],
        'container_id'    => ['type' => 'integer', 'null' => false], // ID from the tentant of the user.
        'created'         => ['type' => 'datetime', 'null' => false, 'default' => null], // CakePHP will update this field automatically (if not set with data).
        'modified'        => ['type' => 'datetime', 'null' => false, 'default' => null], // CakePHP will update this field automatically (if not set with data).
        'indexes'         => [
            'PRIMARY'     => ['column' => 'id', 'unique' => 1],
            'UNIQUE_NAME' => ['column' => ['container_id', 'name'], 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $calendar_holidays = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'calendar_id'     => ['type' => 'integer', 'null' => false],
        'name'            => ['type' => 'string', 'null' => false],
        'default_holiday' => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
        'date'            => ['type' => 'date', 'null' => false], // The date of the holiday.
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $graphgen_tmpls = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'name'            => ['type' => 'string', 'null' => false, 'length' => 128],
        'relative_time'   => ['type' => 'integer', 'null' => false],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $graphgen_tmpl_confs = [
        'id'               => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'graphgen_tmpl_id' => ['type' => 'integer', 'null' => false],
        'service_id'       => ['type' => 'integer', 'null' => false],
        'data_sources'     => ['type' => 'string', 'length' => 256, 'null' => false],
        'indexes'          => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters'  => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $graph_collections = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'name'            => ['type' => 'string', 'null' => false],
        'description'     => ['type' => 'string', 'null' => false],
        'indexes'         => [
            'PRIMARY'     => ['column' => 'id', 'unique' => 1],
            'UNIQUE_NAME' => ['column' => ['id', 'name'], 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $graph_tmpl_to_graph_collection = [
        'id'                  => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'graphgen_tmpl_id'    => ['type' => 'integer', 'null' => false],
        'graph_collection_id' => ['type' => 'integer', 'null' => false],
        'indexes'             => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters'     => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $automaps = [
        'id'                => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'name'              => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'container_id'      => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 6],
        'description'       => ['type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'host_regex'        => ['type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'service_regex'     => ['type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'show_ok'           => ['type' => 'boolean', 'null' => false, 'default' => '1'],
        'show_warning'      => ['type' => 'boolean', 'null' => false, 'default' => '1'],
        'show_critical'     => ['type' => 'boolean', 'null' => false, 'default' => '1'],
        'show_unknown'      => ['type' => 'boolean', 'null' => false, 'default' => '1'],
        'show_acknowledged' => ['type' => 'boolean', 'null' => false, 'default' => '1'],
        'show_downtime'     => ['type' => 'boolean', 'null' => false, 'default' => '1'],
        'show_label'        => ['type' => 'boolean', 'null' => false, 'default' => '0'],
        'group_by_host'     => ['type' => 'boolean', 'null' => false, 'default' => '0'],
        'font_size'         => ['type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'recursive'         => ['type' => 'boolean', 'null' => false, 'default' => '0'],
        'created'           => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'          => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'           => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters'   => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $acos = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'primary'],
        'parent_id'       => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 10],
        'model'           => ['type' => 'string', 'null' => true],
        'foreign_key'     => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 10],
        'alias'           => ['type' => 'string', 'null' => true],
        'lft'             => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 10],
        'rght'            => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 10],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $aros_acos = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'primary'],
        'aro_id'          => ['type' => 'integer', 'null' => false, 'length' => 10, 'key' => 'index'],
        'aco_id'          => ['type' => 'integer', 'null' => false, 'length' => 10],
        '_create'         => ['type' => 'string', 'null' => false, 'default' => '0', 'length' => 2],
        '_read'           => ['type' => 'string', 'null' => false, 'default' => '0', 'length' => 2],
        '_update'         => ['type' => 'string', 'null' => false, 'default' => '0', 'length' => 2],
        '_delete'         => ['type' => 'string', 'null' => false, 'default' => '0', 'length' => 2],
        'indexes'         => [
            'PRIMARY'     => ['column' => 'id', 'unique' => 1],
            'ARO_ACO_KEY' => ['column' => ['aro_id', 'aco_id'], 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $aros = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'primary'],
        'parent_id'       => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 10],
        'model'           => ['type' => 'string', 'null' => true],
        'foreign_key'     => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 10],
        'alias'           => ['type' => 'string', 'null' => true],
        'lft'             => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 10],
        'rght'            => ['type' => 'integer', 'null' => true, 'default' => null, 'length' => 10],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $usergroups = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'name'            => ['type' => 'string', 'null' => false, 'length' => 255, 'charset' => 'utf8'],
        'description'     => ['type' => 'string', 'default' => null, 'length' => 255, 'charset' => 'utf8'],
        'created'         => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'        => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $instantreports = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'name'            => ['type' => 'string', 'null' => false, 'length' => 255, 'charset' => 'utf8'],
        'container_id'    => ['type' => 'integer', 'null' => false, 'default' => null],
        'evaluation'      => ['type' => 'integer', 'null' => false, 'default' => null],
        'type'            => ['type' => 'integer', 'null' => false, 'default' => null],
        'timeperiod_id'   => ['type' => 'integer', 'null' => false, 'default' => null],
        'reflection'      => ['type' => 'integer', 'null' => false, 'default' => null],
        'downtimes'       => ['type' => 'integer', 'null' => false, 'default' => null],
        'summary'         => ['type' => 'integer', 'null' => false, 'default' => null],
        'send_email'      => ['type' => 'integer', 'null' => false, 'default' => null],
        'send_interval'   => ['type' => 'integer', 'null' => false, 'default' => null],
        'last_send_date'  => ['type' => 'datetime', 'null' => false, 'default' => null],
        'created'         => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'        => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $instantreports_to_hostgroups = [
        'id'               => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'instantreport_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'hostgroup_id'     => ['type' => 'integer', 'null' => false, 'default' => null],
        'indexes'          => [
            'PRIMARY'          => ['column' => 'id', 'unique' => 1],
            'instantreport_id' => ['column' => 'instantreport_id', 'unique' => 0],
            'hostgroup_id'     => ['column' => 'hostgroup_id', 'unique' => 0],
        ],
        'tableParameters'  => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $instantreports_to_hosts = [
        'id'               => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'instantreport_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'host_id'          => ['type' => 'integer', 'null' => false, 'default' => null],
        'indexes'          => [
            'PRIMARY'          => ['column' => 'id', 'unique' => 1],
            'instantreport_id' => ['column' => 'instantreport_id', 'unique' => 0],
            'host_id'          => ['column' => 'host_id', 'unique' => 0],
        ],
        'tableParameters'  => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $instantreports_to_servicegroups = [
        'id'               => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'instantreport_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'servicegroup_id'  => ['type' => 'integer', 'null' => false, 'default' => null],
        'indexes'          => [
            'PRIMARY'          => ['column' => 'id', 'unique' => 1],
            'instantreport_id' => ['column' => 'instantreport_id', 'unique' => 0],
            'servicegroup_id'  => ['column' => 'servicegroup_id', 'unique' => 0],
        ],
        'tableParameters'  => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $instantreports_to_services = [
        'id'               => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'instantreport_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'service_id'       => ['type' => 'integer', 'null' => false, 'default' => null],
        'indexes'          => [
            'PRIMARY'          => ['column' => 'id', 'unique' => 1],
            'instantreport_id' => ['column' => 'instantreport_id', 'unique' => 0],
            'service_id'       => ['column' => 'service_id', 'unique' => 0],
        ],
        'tableParameters'  => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $instantreports_to_users = [
        'id'               => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'instantreport_id' => ['type' => 'integer', 'null' => false, 'default' => null],
        'user_id'          => ['type' => 'integer', 'null' => false, 'default' => null],
        'indexes'          => [
            'PRIMARY'          => ['column' => 'id', 'unique' => 1],
            'instantreport_id' => ['column' => 'instantreport_id', 'unique' => 0],
            'user_id'          => ['column' => 'user_id', 'unique' => 0],
        ],
        'tableParameters'  => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $apikeys = [
        'id'              => ['type' => 'integer', 'null' => false, 'key' => 'primary'],
        'user_id'         => ['type' => 'integer', 'null' => false],
        'apikey'          => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 255, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'description'     => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 255, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
            'apikey'  => ['column' => ['apikey', 'user_id'], 'unique' => 0]
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $dashboard_tabs = [
        'id'                => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'user_id'           => ['type' => 'integer', 'null' => false],
        'position'          => ['type' => 'integer', 'null' => false],
        'name'              => ['type' => 'string', 'null' => false],
        'shared'            => ['type' => 'boolean', 'null' => false, 'default' => '0'],
        'source_tab_id'     => ['type' => 'integer', 'null' => true],
        'check_for_updates' => ['type' => 'integer', 'null' => true, 'default' => null],
        'last_update'       => ['type' => 'integer', 'null' => true, 'default' => '0'],
        'locked'            => ['type' => 'boolean', 'null' => false, 'default' => '0'],
        'created'           => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'          => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'           => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters'   => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $widgets = [
        'id'               => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'dashboard_tab_id' => ['type' => 'integer', 'null' => false],
        'type_id'          => ['type' => 'integer', 'null' => false],
        'host_id'          => ['type' => 'integer', 'null' => true],
        'service_id'       => ['type' => 'integer', 'null' => true],
        'row'              => ['type' => 'integer', 'null' => false],
        'col'              => ['type' => 'integer', 'null' => false],
        'width'            => ['type' => 'integer', 'null' => false],
        'height'           => ['type' => 'integer', 'null' => false],
        'title'            => ['type' => 'string', 'null' => false], // The title of the widget.
        'color'            => ['type' => 'string', 'null' => false], // Color of the widgetbar.
        'directive'        => ['type' => 'string', 'null' => false], // Angular directive of the widget.
        'icon'             => ['type' => 'string', 'null' => false],
        'json_data'        => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 2000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'created'          => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'         => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'          => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters'  => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $configuration_files = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary', 'unsigned' => true],
        'config_file'     => ['type' => 'string', 'null' => false, 'length' => 255, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'key'             => ['type' => 'string', 'null' => false, 'length' => 2000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'value'           => ['type' => 'string', 'null' => false, 'length' => 2000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'created'         => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'        => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $configuration_queue = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary', 'unsigned' => true],
        'task'            => ['type' => 'string', 'null' => false],
        'data'            => ['type' => 'string', 'null' => false],
        'json_data'       => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 2000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'created'         => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'        => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $agentchecks = [
        'id'                 => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'name'               => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'plugin_name'        => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'servicetemplate_id' => ['type' => 'integer', 'default' => null],
        'created'            => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'           => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'            => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters'    => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];

    public $agentconfigs = [
        'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
        'host_id'         => ['type' => 'integer', 'default' => null],
        'port'            => ['type' => 'integer', 'default' => null],
        'use_https'       => ['type' => 'boolean', 'null' => false, 'default' => '1'],
        'insecure'        => ['type' => 'boolean', 'null' => false, 'default' => '1'],
        'basic_auth'      => ['type' => 'boolean', 'null' => false, 'default' => '1'],
        'username'        => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'password'        => ['type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'],
        'created'         => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified'        => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes'         => [
            'PRIMARY' => ['column' => 'id', 'unique' => 1],
        ],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
    ];


    /*public $devicegroups = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
        'container_id' => array('type' => 'integer', 'null' => false, 'default' => null),
        'description' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
        'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
        'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
        'indexes' => array(
            'PRIMARY' => array('column' => 'id', 'unique' => 1),
        ),
        'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
    );*/

}
