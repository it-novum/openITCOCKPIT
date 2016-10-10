<?php

use itnovum\openITCOCKPIT\InitialDatabase;

class AppSchema extends CakeSchema {

	public function before($event = array()) {
		$db = ConnectionManager::getDataSource($this->connection);
		$db->cacheSources = false;
		return true;
	}

	public function after($event = array()) {
		if(isset($event['update'])){
			switch($event['update']){
				case 'commands':
					$CommandsImporter = new InitialDatabase\Commands(ClassRegistry::init('Command'));
					$CommandsImporter->import();
					break;

				case 'containers':
					$ContainerImporter = new InitialDatabase\Container(ClassRegistry::init('Container'));
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
					$HostsImporter = new InitialDatabase\Host(ClassRegistry::init('Host'));
					$HostsImporter->import();
					break;

				case 'servicetemplates':
					$ServicetemplatesImporter = new InitialDatabase\Servicetemplate(ClassRegistry::init('Servicetemplate'));
					$ServicetemplatesImporter->import();
					break;

				case 'services':
					$ServicesImporter = new InitialDatabase\Service(ClassRegistry::init('Service'));
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

	public $changelogs = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'model' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'action' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'object_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'objecttype_id' => array('type' => 'integer', 'default' => null),
		'user_id' => array('type' => 'integer', 'default' => null),
		'data' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'name' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'created' => array('column' => 'created'),
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $changelogs_to_containers = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'changelog_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'container_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'changelog_id' => array('column' => 'changelog_id', 'unique' => 0),
			'container_id' => array('column' => 'container_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $commandarguments = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'command_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 10, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'human_name' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'export' => array('column' => array('command_id', 'name', 'human_name'), 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $commands = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'command_line' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'command_type' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 1),
		'human_args' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'uuid' => array('type' => 'string', 'null' => false, 'length' => 37, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'description' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $contactgroups = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'uuid' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 37, 'key' => 'unique', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'container_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'description' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'uuid' => array('column' => 'uuid', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $contactgroups_to_hostescalations = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'contactgroup_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'hostescalation_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'contactgroup_id' => array('column' => 'contactgroup_id', 'unique' => 0),
			'hostescalation_id' => array('column' => 'hostescalation_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $contactgroups_to_hosts = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'contactgroup_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'host_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'contactgroup_id' => array('column' => 'contactgroup_id', 'unique' => 0),
			'host_id' => array('column' => 'host_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $contactgroups_to_hosttemplates = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'contactgroup_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'hosttemplate_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'contactgroup_id' => array('column' => 'contactgroup_id', 'unique' => 0),
			'hosttemplate_id' => array('column' => 'hosttemplate_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $contactgroups_to_serviceescalations = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'contactgroup_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'serviceescalation_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'contactgroup_id' => array('column' => 'contactgroup_id', 'unique' => 0),
			'serviceescalation_id' => array('column' => 'serviceescalation_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $contactgroups_to_services = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'contactgroup_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'service_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'contactgroup_id' => array('column' => 'contactgroup_id', 'unique' => 0),
			'service_id' => array('column' => 'service_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $contactgroups_to_servicetemplates = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'contactgroup_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'servicetemplate_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'contactgroup_id' => array('column' => 'contactgroup_id', 'unique' => 0),
			'servicetemplate_id' => array('column' => 'servicetemplate_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $contacts_to_contactgroups = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'contact_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'contactgroup_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'contact_id' => array('column' => 'contact_id', 'unique' => 0),
			'contactgroup_id' => array('column' => 'contactgroup_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $contacts_to_containers = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'contact_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'container_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'contact_id' => array('column' => 'contact_id', 'unique' => 0),
			'container_id' => array('column' => 'container_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $contacts_to_hostcommands = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'contact_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'command_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'contact_id' => array('column' => 'contact_id', 'unique' => 0),
			'command_id' => array('column' => 'command_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $contacts_to_hostescalations = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'contact_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'hostescalation_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'contact_id' => array('column' => 'contact_id', 'unique' => 0),
			'hostescalation_id' => array('column' => 'hostescalation_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $contacts_to_hosts = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'contact_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'host_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'contact_id' => array('column' => 'contact_id', 'unique' => 0),
			'host_id' => array('column' => 'host_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $contacts_to_hosttemplates = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'contact_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'hosttemplate_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'contact_id' => array('column' => 'contact_id', 'unique' => 0),
			'hosttemplate_id' => array('column' => 'hosttemplate_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $contacts_to_servicecommands = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'contact_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'command_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'contact_id' => array('column' => 'contact_id', 'unique' => 0),
			'command_id' => array('column' => 'command_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $contacts_to_serviceescalations = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'contact_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'serviceescalation_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'contact_id' => array('column' => 'contact_id', 'unique' => 0),
			'serviceescalation_id' => array('column' => 'serviceescalation_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $contacts_to_services = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'contact_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'service_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'contact_id' => array('column' => 'contact_id', 'unique' => 0),
			'service_id' => array('column' => 'service_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $contacts_to_servicetemplates = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'contact_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'servicetemplate_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'contact_id' => array('column' => 'contact_id', 'unique' => 0),
			'servicetemplate_id' => array('column' => 'servicetemplate_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $containers = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'containertype_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'parent_id' => array('type' => 'integer', 'null' => true, 'default' => null),
		'lft' => array('type' => 'integer', 'null' => false, 'default' => null),
		'rght' => array('type' => 'integer', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $customvariables = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'object_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'objecttype_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'value' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $documentations = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'uuid' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 37, 'key' => 'unique', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'content' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'uuid' => array('column' => 'uuid', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $exports = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'task' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'text' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'finished' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1),
		'successfully' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $hostcommandargumentvalues = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'commandargument_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'host_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'value' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $hostescalations = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'uuid' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 37, 'key' => 'unique', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'container_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'timeperiod_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'first_notification' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 6),
		'last_notification' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 6),
		'notification_interval' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 6),
		'escalate_on_recovery' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 1),
		'escalate_on_down' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 1),
		'escalate_on_unreachable' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 1),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $hostgroups = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'uuid' => ['type' => 'string', 'null' => false, 'length' => 37],
		'container_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'description' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'hostgroup_url' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'uuid' => array('column' => 'uuid', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $hostgroups_to_hostescalations = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'hostgroup_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'hostescalation_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'excluded' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 4),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'hostgroup_id' => array('column' => array('hostgroup_id', 'excluded'), 'unique' => 0),
			'hostescalation_id' => array('column' => array('hostescalation_id', 'excluded'), 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $hosts_to_containers = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'host_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'container_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'host_id' => array('column' => 'host_id', 'unique' => 0),
			'container_id' => array('column' => 'container_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $hosts_to_hostescalations = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'host_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'hostescalation_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'excluded' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 4),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'host_id' => array('column' => array('host_id', 'excluded'), 'unique' => 0),
			'hostescalation_id' => array('column' => array('hostescalation_id', 'excluded'), 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $hosts_to_hostgroups = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'host_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'hostgroup_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'host_id' => array('column' => 'host_id', 'unique' => 0),
			'hostgroup_id' => array('column' => 'hostgroup_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $hosts_to_parenthosts = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'host_id' => array('type' => 'integer', 'null' => false),
		'parenthost_id' => array('type' => 'integer', 'null' => false),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'host_id' => array('column' => 'host_id', 'unique' => 0),
			'parenthost_id' => array('column' => 'parenthost_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $locations = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'uuid' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 37, 'key' => 'unique', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'container_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'description' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'latitude' => array('type' => 'float', 'null' => true, 'default' => '0'),
		'longitude' => array('type' => 'float', 'null' => true, 'default' => '0'),
		'timezone' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'uuid' => array('column' => 'uuid', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $macros = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 10, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'value' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'description' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
        'password' => ['type' => 'integer', 'null' => false, 'default' => 0, 'length' => 1],
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $proxies = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'ipaddress' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'port' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 5),
		'enabled' => array('type' => 'boolean', 'null' => false, 'default' => '1'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);


	public $servicecommandargumentvalues = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'commandargument_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'service_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'value' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $serviceeventcommandargumentvalues = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'commandargument_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'service_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'value' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $serviceescalations = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'uuid' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 37, 'key' => 'unique', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'container_id' => array('type' => 'integer', 'null' => true, 'default' => null),
		'timeperiod_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'first_notification' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 6),
		'last_notification' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 6),
		'notification_interval' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 6),
		'escalate_on_recovery' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 1),
		'escalate_on_warning' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 1),
		'escalate_on_unknown' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 1),
		'escalate_on_critical' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 1),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $servicegroups = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'uuid' => ['type' => 'string', 'null' => false, 'length' => 37],
		'container_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'description' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'servicegroup_url' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'uuid' => array('column' => 'uuid', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $servicegroups_to_serviceescalations = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'servicegroup_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'serviceescalation_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'excluded' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 4),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'servicegroup_id' => array('column' => array('servicegroup_id', 'excluded'), 'unique' => 0),
			'serviceescalation_id' => array('column' => array('serviceescalation_id', 'excluded'), 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $services_to_serviceescalations = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'service_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'serviceescalation_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'excluded' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 4),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'service_id' => array('column' => array('service_id', 'excluded'), 'unique' => 0),
			'serviceescalation_id' => array('column' => array('serviceescalation_id', 'excluded'), 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $services_to_servicegroups = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'service_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'servicegroup_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'service_id' => array('column' => 'service_id', 'unique' => 0),
			'servicegroup_id' => array('column' => 'servicegroup_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $systemfailures = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'start_time' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'end_time' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'comment' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'user_id' => array('type' => 'integer', 'null' => true, 'default' => null),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $tenants = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'container_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'description' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'is_active' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 1),
		'date' => array('type' => 'date', 'null' => true, 'default' => null),
		'number_users' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 6),
		'max_users' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 6),
		'number_hosts' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 6),
		'max_hosts' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 6),
		'number_services' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 6),
		'max_services' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 6),
		'firstname' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'lastname' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'street' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'zipcode' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 6),
		'city' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $timeperiod_timeranges = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'timeperiod_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'day' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'start' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 5, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'end' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 5, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $timeperiods = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'uuid' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 37, 'key' => 'unique', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'container_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 6),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'description' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'uuid' => array('column' => 'uuid', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $contacts = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'uuid' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 37, 'key' => 'unique', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'description' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'email' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'phone' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'host_timeperiod_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'service_timeperiod_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'host_notifications_enabled' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1),
		'service_notifications_enabled' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1),
		'notify_service_recovery' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1),
		'notify_service_warning' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1),
		'notify_service_unknown' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1),
		'notify_service_critical' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1),
		'notify_service_flapping' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1),
		'notify_service_downtime' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1),
		'notify_host_recovery' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1),
		'notify_host_down' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1),
		'notify_host_unreachable' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1),
		'notify_host_flapping' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1),
		'notify_host_downtime' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'uuid' => array('column' => 'uuid', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $hosttemplatecommandargumentvalues = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'commandargument_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'hosttemplate_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'value' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $hosttemplates = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'uuid' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 37, 'key' => 'unique', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'description' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'hosttemplatetype_id' => array('type' => 'integer', 'null' => false, 'default' => 1),
		'command_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'check_command_args' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'eventhandler_command_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'timeperiod_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'check_interval' => array('type' => 'integer', 'null' => false, 'default' => '1', 'length' => 5),
		'retry_interval' => array('type' => 'integer', 'null' => false, 'default' => '3', 'length' => 5),
		'max_check_attempts' => array('type' => 'integer', 'null' => false, 'default' => '1', 'length' => 3),
		'first_notification_delay' => array('type' => 'float', 'null' => false, 'default' => '0'),
		'notification_interval' => array('type' => 'float', 'null' => false, 'default' => '0'),
		'notify_on_down' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1),
		'notify_on_unreachable' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1),
		'notify_on_recovery' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1),
		'notify_on_flapping' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1),
		'notify_on_downtime' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1),
		'flap_detection_enabled' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1),
		'flap_detection_on_up' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1),
		'flap_detection_on_down' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1),
		'flap_detection_on_unreachable' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1),
		'low_flap_threshold' => array('type' => 'float', 'null' => false, 'default' => '0'),
		'high_flap_threshold' => array('type' => 'float', 'null' => false, 'default' => '0'),
		'process_performance_data' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'freshness_checks_enabled' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'freshness_threshold' => array('type' => 'integer', 'null' => true, 'default' => '0', 'length' => 8),
		'passive_checks_enabled' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1),
		'event_handler_enabled' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1),
		'active_checks_enabled' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1),
		'retain_status_information' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'retain_nonstatus_information' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'notifications_enabled' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'notes' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'priority' => array('type' => 'integer', 'null' => false, 'default' => '1', 'length' => 2),
		'check_period_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'notify_period_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'tags' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'container_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'host_url' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'uuid' => array('column' => 'uuid', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $hosts = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'uuid' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 37, 'key' => 'unique', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'container_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'description' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'hosttemplate_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'address' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 128, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'command_id' => array('type' => 'integer', 'null' => true, 'default' => null),
		'eventhandler_command_id' => array('type' => 'integer', 'null' => true, 'default' => null),
		'timeperiod_id' => array('type' => 'integer', 'null' => true, 'default' => null),
		'check_interval' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 5),
		'retry_interval' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 5),
		'max_check_attempts' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 3),
		'first_notification_delay' => array('type' => 'float', 'null' => true, 'default' => null),
		'notification_interval' => array('type' => 'float', 'null' => true, 'default' => null),
		'notify_on_down' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 1),
		'notify_on_unreachable' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 1),
		'notify_on_recovery' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 1),
		'notify_on_flapping' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 1),
		'notify_on_downtime' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 1),
		'flap_detection_enabled' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 1),
		'flap_detection_on_up' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 1),
		'flap_detection_on_down' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 1),
		'flap_detection_on_unreachable' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 1),
		'low_flap_threshold' => array('type' => 'float', 'null' => true, 'default' => null),
		'high_flap_threshold' => array('type' => 'float', 'null' => true, 'default' => null),
		'process_performance_data' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 6),
		'freshness_checks_enabled' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 6),
		'freshness_threshold' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 8),
		'passive_checks_enabled' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 1),
		'event_handler_enabled' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 1),
		'active_checks_enabled' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 1),
		'retain_status_information' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 6),
		'retain_nonstatus_information' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 6),
		'notifications_enabled' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 6),
		'notes' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'priority' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 2),
		'check_period_id' => array('type' => 'integer', 'null' => true, 'default' => null),
		'notify_period_id' => array('type' => 'integer', 'null' => true, 'default' => null),
		'tags' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'own_contacts' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1),
		'own_contactgroups' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1),
		'own_customvariables' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1),
		'host_url' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'satellite_id' => array('type' => 'integer', 'default' => 0),
		'host_type' => array('type' => 'integer', 'null' => false, 'default' => 1),
		'disabled' => array('type' => 'integer', 'null' => true, 'default' => 0, 'length' => 1),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'uuid' => array('column' => 'uuid', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $servicetemplatecommandargumentvalues = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'commandargument_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'servicetemplate_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'value' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $servicetemplateeventcommandargumentvalues = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'commandargument_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'servicetemplate_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'value' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $servicetemplategroups = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'uuid' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 37, 'key' => 'unique', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'container_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'description' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'uuid' => array('column' => 'uuid', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $servicetemplates_to_servicetemplategroups = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'servicetemplate_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'servicetemplategroup_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'servicetemplategroup_id' => array('column' => 'servicetemplategroup_id', 'unique' => 0),
			'servicetemplate_id' => array('column' => 'servicetemplate_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $servicetemplates = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'uuid' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 37, 'key' => 'unique', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'container_id' => array('type' => 'integer', 'null' => true, 'default' => null),
		'servicetemplatetype_id' => array('type' => 'integer', 'null' => false, 'default' => 1),
		'check_period_id' => array('type' => 'integer', 'null' => true, 'default' => null),
		'notify_period_id' => array('type' => 'integer', 'null' => true, 'default' => null),
		'description' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'command_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'check_command_args' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'checkcommand_info' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'eventhandler_command_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'timeperiod_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'check_interval' => array('type' => 'integer', 'null' => false, 'default' => '1', 'length' => 5),
		'retry_interval' => array('type' => 'integer', 'null' => false, 'default' => '3', 'length' => 5),
		'max_check_attempts' => array('type' => 'integer', 'null' => false, 'default' => '1', 'length' => 3),
		'first_notification_delay' => array('type' => 'float', 'null' => false, 'default' => '0'),
		'notification_interval' => array('type' => 'float', 'null' => false, 'default' => '0'),
		'notify_on_warning' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1),
		'notify_on_unknown' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1),
		'notify_on_critical' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1),
		'notify_on_recovery' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'notify_on_flapping' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1),
		'notify_on_downtime' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1),
		'flap_detection_enabled' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1),
		'flap_detection_on_ok' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1),
		'flap_detection_on_warning' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1),
		'flap_detection_on_unknown' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1),
		'flap_detection_on_critical' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'low_flap_threshold' => array('type' => 'float', 'null' => false, 'default' => '0'),
		'high_flap_threshold' => array('type' => 'float', 'null' => false, 'default' => '0'),
		'process_performance_data' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'freshness_checks_enabled' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'freshness_threshold' => array('type' => 'integer', 'null' => true, 'default' => '0', 'length' => 8),
		'passive_checks_enabled' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1),
		'event_handler_enabled' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1),
		'active_checks_enabled' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1),
		'retain_status_information' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'retain_nonstatus_information' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'notifications_enabled' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'notes' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'priority' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 2),
		'tags' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 1500, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'service_url' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'is_volatile' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'check_freshness' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'service_url' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'uuid' => array('column' => 'uuid', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $services = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'uuid' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 37, 'key' => 'unique', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'servicetemplate_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'host_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'name' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 1500, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'description' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'command_id' => array('type' => 'integer', 'null' => true, 'default' => null),
		'check_command_args' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'eventhandler_command_id' => array('type' => 'integer', 'null' => true, 'default' => null),
		'notify_period_id' => array('type' => 'integer', 'null' => true, 'default' => null),
		'check_period_id' => array('type' => 'integer', 'null' => true, 'default' => null),
		'check_interval' => array('type' => 'float', 'null' => true, 'default' => null),
		'retry_interval' => array('type' => 'float', 'null' => true, 'default' => null),
		'max_check_attempts' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 6),
		'first_notification_delay' => array('type' => 'float', 'null' => true, 'default' => null),
		'notification_interval' => array('type' => 'float', 'null' => true, 'default' => null),
		'notify_on_warning' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 1),
		'notify_on_unknown' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 1),
		'notify_on_critical' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 1),
		'notify_on_recovery' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 1),
		'notify_on_flapping' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 1),
		'notify_on_downtime' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 1),
		'is_volatile' => array('type' => 'integer', 'null' => true, 'length' => 1),
		'flap_detection_enabled' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 1),
		'flap_detection_on_ok' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 1),
		'flap_detection_on_warning' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 1),
		'flap_detection_on_unknown' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 1),
		'flap_detection_on_critical' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 1),
		'low_flap_threshold' => array('type' => 'float', 'null' => true, 'default' => null),
		'high_flap_threshold' => array('type' => 'float', 'null' => true, 'default' => null),
		'process_performance_data' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 6),
		'freshness_checks_enabled' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 8),
		'freshness_threshold' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 6),
		'passive_checks_enabled' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 6),
		'event_handler_enabled' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 6),
		'active_checks_enabled' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 6),
		'notifications_enabled' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 6),
		'notes' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'priority' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 2),
		'tags' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'own_contacts' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 1),
		'own_contactgroups' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 1),
		'own_customvariables' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 1),
		'service_url' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'service_type' => array('type' => 'integer', 'null' => false, 'default' => 1),
		'disabled' => array('type' => 'integer', 'null' => true, 'default' => 0, 'length' => 1),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'uuid' => array('column' => 'uuid', 'unique' => 1),
			'export' => array('column' => array('uuid', 'host_id', 'disabled'), 'unique' => 0),
			'host_id' => array('column' => array('host_id', 'disabled'), 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $users = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'primary'),
		'usergroup_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'status' => array('type' => 'integer', 'null' => false, 'default' => '1', 'length' => 3),
		'email' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'password' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 45, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'firstname' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'lastname' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'position' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'company' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'phone' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'linkedin_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 45, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'timezone' => array('type' => 'string', 'null' => true, 'default' => 'Europe/Berlin', 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'dateformat' => array('type' => 'string', 'null' => true, 'default' => '%H:%M:%S - %d.%m.%Y', 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'image' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'onetimetoken' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'samaccountname' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 128, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'showstatsinmenu' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'dashboard_tab_rotation' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 10),
		'paginatorlength' => array('type' => 'integer', 'null' => false, 'default' => '25', 'length' => 4),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	public $systemsettings = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'key' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'value' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'info' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8', 'length' => 1500,),
		'section' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $hostdependencies = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'uuid' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 37, 'key' => 'unique', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'container_id' => array('type' => 'integer', 'null' => true, 'default' => null),
		'inherits_parent' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1),
		'timeperiod_id' => array('type' => 'integer', 'null' => true, 'default' => null),
		'execution_fail_on_up' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 1),
		'execution_fail_on_down' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 1),
		'execution_fail_on_unreachable' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 1),
		'execution_fail_on_pending' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 1),
		'execution_none' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 1),
		'notification_fail_on_up' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 1),
		'notification_fail_on_down' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 1),
		'notification_fail_on_unreachable' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 1),
		'notification_fail_on_pending' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 1),
		'notification_none' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 1),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $hosts_to_hostdependencies = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'host_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'hostdependency_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'dependent' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'host_id' => array('column' => array('host_id', 'dependent'), 'unique' => 0),
			'hostdependency_id' => array('column' => array('hostdependency_id', 'dependent'), 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $hostgroups_to_hostdependencies = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'hostgroup_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'hostdependency_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'dependent' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'hostgroup_id' => array('column' => array('hostgroup_id', 'dependent'), 'unique' => 0),
			'hostdependency_id' => array('column' => array('hostdependency_id', 'dependent'), 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $servicedependencies = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'uuid' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 37, 'key' => 'unique', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'container_id' => array('type' => 'integer', 'null' => true, 'default' => null),
		'inherits_parent' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1),
		'timeperiod_id' => array('type' => 'integer', 'null' => true, 'default' => null),
		'execution_fail_on_ok' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 1),
		'execution_fail_on_warning' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 1),
		'execution_fail_on_unknown' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 1),
		'execution_fail_on_critical' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 1),
		'execution_fail_on_pending' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 1),
		'execution_none' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 1),
		'notification_fail_on_ok' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 1),
		'notification_fail_on_warning' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 1),
		'notification_fail_on_unknown' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 1),
		'notification_fail_on_critical' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 1),
		'notification_fail_on_pending' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 1),
		'notification_none' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 1),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $services_to_servicedependencies = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'service_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'servicedependency_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'dependent' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'service_id' => array('column' => array('service_id', 'dependent'), 'unique' => 0),
			'servicedependency_id' => array('column' => array('servicedependency_id', 'dependent'), 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $servicegroups_to_servicedependencies = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'servicegroup_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'servicedependency_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'dependent' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'servicegroup_id' => array('column' => array('servicegroup_id', 'dependent'), 'unique' => 0),
			'servicedependency_id' => array('column' => array('servicedependency_id', 'dependent'), 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $systemdowntimes = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'objecttype_id' => array('type' => 'integer', 'default' => null),
		'object_id' => array('type' => 'integer', 'default' => null),
		'downtimetype_id' => array('type' => 'integer', 'default' => 0),
		'weekdays' => array('type' => 'string', 'default' => null),
		'day_of_month' => array('type' => 'string', 'default' => null),
		'from_time' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'to_time' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'comment' => array('type' => 'string', 'default' => null),
		'author' => array('type' => 'string', 'default' => null),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $cronschedules = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'cronjob_id' => array('type' => 'integer', 'default' => null),
		'is_running' => array('type' => 'integer', 'default' => null),
		'start_time' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'end_time' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $cronjobs = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'task' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'plugin' => array('type' => 'string', 'null' => false, 'default' => 'Core', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'interval' => array('type' => 'integer', 'default' => null),
		//'enabled' => array('type' => 'boolean', 'length' => 1, 'default' => 1, 'null' => false),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $users_to_containers = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'container_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'permission_level' => array('type' => 'integer', 'null' => false, 'default' => 1, 'length' => 1),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'user_id' => array('column' => 'user_id', 'unique' => 0),
			'container_id' => array('column' => 'container_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);


	public $satellites = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'length' => 255, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'address' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 128, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'container_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $deleted_services = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'uuid' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 37, 'key' => 'unique', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'host_uuid' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 37, 'key' => 'unique', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'servicetemplate_id' => array('type' => 'integer', 'null' => false, 'default' => null),		'host_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'description' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'deleted_perfdata' => array('type' => 'integer', 'null' => true, 'default' => 0, 'length' => 1),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'uuid' => array('column' => 'uuid', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $deleted_hosts = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'uuid' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 37, 'key' => 'unique', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'hosttemplate_id' => array('type' => 'integer', 'null' => false, 'default' => null),		'host_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'description' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'deleted_perfdata' => array('type' => 'integer', 'null' => true, 'default' => 0, 'length' => 1),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'uuid' => array('column' => 'uuid', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $registers = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'license' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 37, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'accepted' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'apt' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $calendars = [
		'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
		'name' => ['type' => 'string', 'null' => false],
		'description' => ['type' => 'string', 'null' => false, 'default' => ''],
		'container_id' => ['type' => 'integer', 'null' => false], // ID from the tentant of the user.
		'created' => ['type' => 'datetime', 'null' => false, 'default' => null], // CakePHP will update this field automatically (if not set with data).
		'modified' => ['type' => 'datetime', 'null' => false, 'default' => null], // CakePHP will update this field automatically (if not set with data).
		'indexes' => [
			'PRIMARY' => ['column' => 'id', 'unique' => 1],
			'UNIQUE_NAME' => ['column' => ['container_id','name'], 'unique' => 1],
		],
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	];

	public $calendar_holidays = [
		'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
		'calendar_id' => ['type' => 'integer', 'null' => false],
		'name' => ['type' => 'string', 'null' => false],
		'default_holiday' => ['type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1],
		'date' => ['type' => 'date', 'null' => false], // The date of the holiday.
		'indexes' => [
			'PRIMARY' => ['column' => 'id', 'unique' => 1],
		],
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	];

	public $graphgen_tmpls = [
		'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
		'name' => ['type' => 'string', 'null' => false, 'length' => 128],
		'relative_time' => ['type' => 'integer', 'null' => false],
		'indexes' => [
			'PRIMARY' => ['column' => 'id', 'unique' => 1],
		],
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	];

	public $graphgen_tmpl_confs = [
		'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
		'graphgen_tmpl_id' => ['type' => 'integer', 'null' => false],
		'service_id' => ['type' => 'integer', 'null' => false],
		'data_sources' => ['type' => 'string', 'length' => 256, 'null' => false],
		'indexes' => [
			'PRIMARY' => ['column' => 'id', 'unique' => 1],
		],
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	];

	public $graph_collections = [
		'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
		'name' => ['type' => 'string', 'null' => false],
		'description' => ['type' => 'string', 'null' => false],
		'indexes' => [
			'PRIMARY' => ['column' => 'id', 'unique' => 1],
			'UNIQUE_NAME' => ['column' => ['id','name'], 'unique' => 1],
		],
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	];

	public $graph_tmpl_to_graph_collection = [
		'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
		'graphgen_tmpl_id' => ['type' => 'integer', 'null' => false],
		'graph_collection_id' => ['type' => 'integer', 'null' => false],
		'indexes' => [
			'PRIMARY' => ['column' => 'id', 'unique' => 1],
		],
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	];

	public $widgets = [
		'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
		'dashboard_tab_id' => ['type' => 'integer', 'null' => false],
		'type_id' => ['type' => 'integer', 'null' => false],
		'service_id' => ['type' => 'integer', 'null' => true],
		'host_id' => ['type' => 'integer', 'null' => true],
		'map_id' => ['type' => 'integer', 'null' => true],
		'graph_id' => ['type' => 'integer', 'null' => true],
		'row' => ['type' => 'integer', 'null' => false],
		'col' => ['type' => 'integer', 'null' => false],
		'width' => ['type' => 'integer', 'null' => false],
		'height' => ['type' => 'integer', 'null' => false],
		'title' => ['type' => 'string', 'null' => false], // The title of the widget.
		'color' => ['type' => 'string', 'null' => false], // Color of the widgetbar.
		'created' => ['type' => 'datetime', 'null' => false, 'default' => null],
		'modified' => ['type' => 'datetime', 'null' => false, 'default' => null],
		'indexes' => [
			'PRIMARY' => ['column' => 'id', 'unique' => 1],
		],
	];

	public $dashboard_tabs = [
		'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
		'user_id' => ['type' => 'integer', 'null' => false], // ID from the user.
		'position' => ['type' => 'integer', 'null' => false], //Position
		'name' => ['type' => 'string', 'null' => false], // The name of the tab.
		'shared' => ['type' => 'boolean', 'null' => false, 'default' => '0'], // The name of the tab.
		'source_tab_id' => ['type' => 'integer', 'null' => true], // The name of the tab.
		'check_for_updates' => ['type' => 'integer', 'null' => true, 'default' => null],
		'source_last_modified' => ['type' => 'datetime', 'null' => true, 'default' => null],
		'created' => ['type' => 'datetime', 'null' => false, 'default' => null],
		'modified' => ['type' => 'datetime', 'null' => false, 'default' => null],
		'indexes' => [
			'PRIMARY' => ['column' => 'id', 'unique' => 1],
		],
	];

	public $widget_tachos = [
		'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
		'widget_id' => ['type' => 'integer', 'null' => false],
		'min' => ['type' => 'integer', 'null' => false],
		'max' => ['type' => 'integer', 'null' => false],
		'warn' => ['type' => 'integer', 'null' => false],
		'crit' => ['type' => 'integer', 'null' => false],
		'data_source' => ['type' => 'string', 'null' => false],
		'indexes' => [
			'PRIMARY' => ['column' => 'id', 'unique' => 1],
			'KEY' => ['column' => 'widget_id', 'unique' => 1],
		],
	];

	public $widget_service_status_lists = [
		'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
		'widget_id' => ['type' => 'integer', 'null' => false],
		'animation' => ['type' => 'string', 'null' => false],
		'animation_interval' => ['type' => 'integer', 'null' => false],
		'show_ok' => ['type' => 'boolean', 'null' => false, 'default' => '1'],
		'show_warning' => ['type' => 'boolean', 'null' => false, 'default' => '1'],
		'show_critical' => ['type' => 'boolean', 'null' => false, 'default' => '1'],
		'show_unknown' => ['type' => 'boolean', 'null' => false, 'default' => '1'],
		'show_acknowledged' => ['type' => 'boolean', 'null' => false, 'default' => '0'],
		'show_downtime' => ['type' => 'boolean', 'null' => false, 'default' => '0'],
		'indexes' => [
			'PRIMARY' => ['column' => 'id', 'unique' => 1],
			'KEY' => ['column' => 'widget_id', 'unique' => 1],
		],
	];

	public $widget_host_status_lists = [
		'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
		'widget_id' => ['type' => 'integer', 'null' => false],
		'animation' => ['type' => 'string', 'null' => false],
		'animation_interval' => ['type' => 'integer', 'null' => false],
		'show_up' => ['type' => 'boolean', 'null' => false, 'default' => '1'],
		'show_down' => ['type' => 'boolean', 'null' => false, 'default' => '1'],
		'show_unreachable' => ['type' => 'boolean', 'null' => false, 'default' => '1'],
		'show_acknowledged' => ['type' => 'boolean', 'null' => false, 'default' => '0'],
		'show_downtime' => ['type' => 'boolean', 'null' => false, 'default' => '0'],
		'indexes' => [
			'PRIMARY' => ['column' => 'id', 'unique' => 1],
			'KEY' => ['column' => 'widget_id', 'unique' => 1],
		],
	];

	public $widget_notices = [
		'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
		'widget_id' => ['type' => 'integer', 'null' => false],
		'note' => ['type' => 'text', 'null' => false],
		'indexes' => [
			'PRIMARY' => ['column' => 'id', 'unique' => 1],
			'KEY' => ['column' => 'widget_id', 'unique' => 1],
		],
	];

	public $automaps = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'container_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 6),
		'description' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'host_regex' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'service_regex' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'show_ok' => array('type' => 'boolean', 'null' => false, 'default' => '1'),
		'show_warning' => array('type' => 'boolean', 'null' => false, 'default' => '1'),
		'show_critical' => array('type' => 'boolean', 'null' => false, 'default' => '1'),
		'show_unknown' => array('type' => 'boolean', 'null' => false, 'default' => '1'),
		'show_acknowledged' => array('type' => 'boolean', 'null' => false, 'default' => '1'),
		'show_downtime' => array('type' => 'boolean', 'null' => false, 'default' => '1'),
		'show_label' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'font_size' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $acos = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'primary'),
		'parent_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10),
		'model' => array('type' => 'string', 'null' => true),
		'foreign_key' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10),
		'alias' => array('type' => 'string', 'null' => true),
		'lft' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10),
		'rght' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $aros_acos = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'primary'),
		'aro_id' => array('type' => 'integer', 'null' => false, 'length' => 10, 'key' => 'index'),
		'aco_id' => array('type' => 'integer', 'null' => false, 'length' => 10),
		'_create' => array('type' => 'string', 'null' => false, 'default' => '0', 'length' => 2),
		'_read' => array('type' => 'string', 'null' => false, 'default' => '0', 'length' => 2),
		'_update' => array('type' => 'string', 'null' => false, 'default' => '0', 'length' => 2),
		'_delete' => array('type' => 'string', 'null' => false, 'default' => '0', 'length' => 2),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'ARO_ACO_KEY' => array('column' => array('aro_id', 'aco_id'), 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $aros = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'primary'),
		'parent_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10),
		'model' => array('type' => 'string', 'null' => true),
		'foreign_key' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10),
		'alias' => array('type' => 'string', 'null' => true),
		'lft' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10),
		'rght' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $usergroups = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'length' => 255, 'charset' => 'utf8'),
		'description' => array('type' => 'string', 'default' => null, 'length' => 255, 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

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
