<?php 
class AppSchema extends CakeSchema {

	public function before($event = array()) {
		return true;
	}

	public function after($event = array()) {
	}

	public $changelogs = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'controller' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'action' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'data' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
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
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
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
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $contactgroups_to_hosts = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'contactgroup_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'host_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $contactgroups_to_hosttemplates = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'contactgroup_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'hosttemplate_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $contactgroups_to_services = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'contactgroup_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'service_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $contactgroups_to_servicetemplates = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'contactgroup_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'servicetemplate_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
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
		'can_submit_commands' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1),
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

	public $contacts_to_contactgroups = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'contact_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'contactgroup_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $contacts_to_containers = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'contact_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'container_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $contacts_to_hostcommands = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'contact_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'command_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $contacts_to_hostescalations = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'contact_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'hostescalation_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $contacts_to_hosts = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'contact_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'host_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $contacts_to_hosttemplates = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'contact_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'hosttemplate_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $contacts_to_servicecommands = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'contact_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'command_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $contacts_to_services = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'contact_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'service_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $contacts_to_servicetemplates = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'contact_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'servicetemplate_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
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
		'object_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'objecttype_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'action' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $hostcommandargumentvalues = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'commandargument_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'host_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'value' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 500, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $hostescalations = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
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
		'uuid' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'unique'),
		'container_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'description' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
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
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
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
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'uuid' => array('column' => 'uuid', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $hosts_to_containers = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'host_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'container_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $hosts_to_hostescalations = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'host_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'hostescalation_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'excluded' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 4),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $hosts_to_hostgroups = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'host_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'hostgroup_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $hosttemplatecommandargumentvalues = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'commandargument_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'hosttemplate_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'value' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 500, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
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
		'freshness_threshold' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 8),
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
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'uuid' => array('column' => 'uuid', 'unique' => 1)
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
		'value' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 500, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $services = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'uuid' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 37, 'key' => 'unique', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'servicetemplate_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'host_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'description' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'command_id' => array('type' => 'integer', 'null' => true, 'default' => null),
		'check_command_args' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'eventhandler_command_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
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
		'is_volatile' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1),
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
		'tags' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'own_contacts' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 1),
		'own_contactgroups' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 1),
		'own_customvariables' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 1),
		'service_url' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
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
		'value' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 500, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $servicetemplates = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'uuid' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 37, 'key' => 'unique', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'container_id' => array('type' => 'integer', 'null' => true, 'default' => null),
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
		'freshness_threshold' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 8),
		'passive_checks_enabled' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1),
		'event_handler_enabled' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1),
		'active_checks_enabled' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1),
		'retain_status_information' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'retain_nonstatus_information' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'notifications_enabled' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'notes' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'tags' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 1500, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'is_volatile' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'check_freshness' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'eventhandler_id' => array('type' => 'integer', 'null' => true, 'default' => null),
		'priority' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 2),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'uuid' => array('column' => 'uuid', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

	public $tenants = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'container_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'description' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'is_active' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 1),
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

	public $users = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'primary'),
		'container_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'status' => array('type' => 'integer', 'null' => false, 'default' => '1', 'length' => 3),
		'role' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 45, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'email' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'password' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 45, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'firstname' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'lastname' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'position' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'company' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'phone' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'linkedin_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 45, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	
	public $systemfailures = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'start_time' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'end_time' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'comment' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'user_id' => array('type' => 'integer'),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
	);

}
