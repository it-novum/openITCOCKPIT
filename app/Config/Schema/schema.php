<?php 
class AppSchema extends CakeSchema {

	public $connection = 'nagios';

	public function before($event = array()) {
		return true;
	}

	public function after($event = array()) {
	}

	public $acknowledgements = array(
		'acknowledgement_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'entry_time' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00', 'key' => 'index'),
		'entry_time_usec' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'acknowledgement_type' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'object_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'state' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'author_name' => array('type' => 'string', 'null' => false, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'comment_data' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'is_sticky' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'persistent_comment' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'notify_contacts' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'indexes' => array(
			'PRIMARY' => array('column' => 'acknowledgement_id', 'unique' => 1),
			'entry_time' => array('column' => 'entry_time', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM')
	);

	public $commands = array(
		'command_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'),
		'config_type' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'object_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'index'),
		'command_line' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 511, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'command_id', 'unique' => 1),
			'instance_id' => array('column' => array('instance_id', 'object_id', 'config_type'), 'unique' => 1),
			'object_id' => array('column' => 'object_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM')
	);

	public $commenthistory = array(
		'commenthistory_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'),
		'entry_time' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'entry_time_usec' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'comment_type' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'entry_type' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'object_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'comment_time' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'internal_comment_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'author_name' => array('type' => 'string', 'null' => false, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'comment_data' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'is_persistent' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'comment_source' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'expires' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'expiration_time' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'deletion_time' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'deletion_time_usec' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'commenthistory_id', 'unique' => 1),
			'instance_id' => array('column' => array('instance_id', 'comment_time', 'internal_comment_id'), 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM')
	);

	public $comments = array(
		'comment_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'),
		'entry_time' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'entry_time_usec' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'comment_type' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'entry_type' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'object_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'comment_time' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'internal_comment_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'author_name' => array('type' => 'string', 'null' => false, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'comment_data' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'is_persistent' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'comment_source' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'expires' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'expiration_time' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'comment_id', 'unique' => 1),
			'instance_id' => array('column' => array('instance_id', 'comment_time', 'internal_comment_id'), 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM')
	);

	public $configfiles = array(
		'configfile_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'),
		'configfile_type' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'configfile_path' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'configfile_id', 'unique' => 1),
			'instance_id' => array('column' => array('instance_id', 'configfile_type', 'configfile_path'), 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM')
	);

	public $configfilevariables = array(
		'configfilevariable_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'configfile_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'varname' => array('type' => 'string', 'null' => false, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'varvalue' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'configfilevariable_id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM')
	);

	public $conninfo = array(
		'conninfo_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'agent_name' => array('type' => 'string', 'null' => false, 'length' => 32, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'agent_version' => array('type' => 'string', 'null' => false, 'length' => 8, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'disposition' => array('type' => 'string', 'null' => false, 'length' => 16, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'connect_source' => array('type' => 'string', 'null' => false, 'length' => 16, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'connect_type' => array('type' => 'string', 'null' => false, 'length' => 16, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'connect_time' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'disconnect_time' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'last_checkin_time' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'data_start_time' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'data_end_time' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'bytes_processed' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'lines_processed' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'entries_processed' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'conninfo_id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM')
	);

	public $contact_addresses = array(
		'contact_address_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'contact_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'index'),
		'address_number' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'address' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'contact_address_id', 'unique' => 1),
			'contact_id' => array('column' => array('contact_id', 'address_number'), 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM')
	);

	public $contact_notificationcommands = array(
		'contact_notificationcommand_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'contact_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'index'),
		'notification_type' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'command_object_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'command_args' => array('type' => 'string', 'null' => false, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'contact_notificationcommand_id', 'unique' => 1),
			'contact_id' => array('column' => array('contact_id', 'notification_type', 'command_object_id'), 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM')
	);

	public $contactgroup_members = array(
		'contactgroup_member_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'contactgroup_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'index'),
		'contact_object_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'contactgroup_member_id', 'unique' => 1),
			'instance_id' => array('column' => array('contactgroup_id', 'contact_object_id'), 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM')
	);

	public $contactgroups = array(
		'contactgroup_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'),
		'config_type' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'contactgroup_object_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'alias' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'contactgroup_id', 'unique' => 1),
			'instance_id' => array('column' => array('instance_id', 'config_type', 'contactgroup_object_id'), 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM')
	);

	public $contactnotificationmethods = array(
		'contactnotificationmethod_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'),
		'contactnotification_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'start_time' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00', 'key' => 'index'),
		'start_time_usec' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'end_time' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'end_time_usec' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'command_object_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'command_args' => array('type' => 'string', 'null' => false, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'contactnotificationmethod_id', 'unique' => 1),
			'instance_id' => array('column' => array('instance_id', 'contactnotification_id', 'start_time', 'start_time_usec'), 'unique' => 1),
			'start_time' => array('column' => 'start_time', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM')
	);

	public $contactnotifications = array(
		'contactnotification_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'),
		'notification_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'contact_object_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'start_time' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00', 'key' => 'index'),
		'start_time_usec' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'end_time' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'end_time_usec' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'contactnotification_id', 'unique' => 1),
			'instance_id' => array('column' => array('instance_id', 'contact_object_id', 'start_time', 'start_time_usec'), 'unique' => 1),
			'start_time' => array('column' => 'start_time', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM')
	);

	public $contacts = array(
		'contact_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'),
		'config_type' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'contact_object_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'alias' => array('type' => 'string', 'null' => false, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'email_address' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'pager_address' => array('type' => 'string', 'null' => false, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'host_timeperiod_object_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'service_timeperiod_object_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'host_notifications_enabled' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'service_notifications_enabled' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'can_submit_commands' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'notify_service_recovery' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'notify_service_warning' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'notify_service_unknown' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'notify_service_critical' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'notify_service_flapping' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'notify_service_downtime' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'notify_host_recovery' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'notify_host_down' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'notify_host_unreachable' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'notify_host_flapping' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'notify_host_downtime' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'minimum_importance' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'contact_id', 'unique' => 1),
			'instance_id' => array('column' => array('instance_id', 'config_type', 'contact_object_id'), 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM')
	);

	public $contactstatus = array(
		'contactstatus_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'contact_object_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'unique'),
		'status_update_time' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'host_notifications_enabled' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'service_notifications_enabled' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'last_host_notification' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'last_service_notification' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'modified_attributes' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'modified_host_attributes' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'modified_service_attributes' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'contactstatus_id', 'unique' => 1),
			'contact_object_id' => array('column' => 'contact_object_id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM')
	);

	public $customvariables = array(
		'customvariable_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'object_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'index'),
		'config_type' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'has_been_modified' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'varname' => array('type' => 'string', 'null' => false, 'key' => 'index', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'varvalue' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'customvariable_id', 'unique' => 1),
			'object_id_2' => array('column' => array('object_id', 'config_type', 'varname'), 'unique' => 1),
			'varname' => array('column' => 'varname', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM')
	);

	public $customvariablestatus = array(
		'customvariablestatus_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'object_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'index'),
		'status_update_time' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'has_been_modified' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'varname' => array('type' => 'string', 'null' => false, 'key' => 'index', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'varvalue' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'customvariablestatus_id', 'unique' => 1),
			'object_id_2' => array('column' => array('object_id', 'varname'), 'unique' => 1),
			'varname' => array('column' => 'varname', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM')
	);

	public $dbversion = array(
		'name' => array('type' => 'string', 'null' => false, 'length' => 10, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'version' => array('type' => 'string', 'null' => false, 'length' => 10, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'indexes' => array(
			
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM')
	);

	public $downtimehistory = array(
		'downtimehistory_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'),
		'downtime_type' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'object_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'entry_time' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'author_name' => array('type' => 'string', 'null' => false, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'comment_data' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'internal_downtime_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'triggered_by_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'is_fixed' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'duration' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'scheduled_start_time' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'scheduled_end_time' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'was_started' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'actual_start_time' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'actual_start_time_usec' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'actual_end_time' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'actual_end_time_usec' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'was_cancelled' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'indexes' => array(
			'PRIMARY' => array('column' => 'downtimehistory_id', 'unique' => 1),
			'instance_id' => array('column' => array('instance_id', 'object_id', 'entry_time', 'internal_downtime_id'), 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM')
	);

	public $eventhandlers = array(
		'eventhandler_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'),
		'eventhandler_type' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'object_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'state' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'state_type' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'start_time' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'start_time_usec' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'end_time' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'end_time_usec' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'command_object_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'command_args' => array('type' => 'string', 'null' => false, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'command_line' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'timeout' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'early_timeout' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'execution_time' => array('type' => 'float', 'null' => false, 'default' => '0'),
		'return_code' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'output' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'long_output' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'eventhandler_id', 'unique' => 1),
			'instance_id' => array('column' => array('instance_id', 'object_id', 'start_time', 'start_time_usec'), 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM')
	);

	public $externalcommands = array(
		'externalcommand_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'entry_time' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'command_type' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'command_name' => array('type' => 'string', 'null' => false, 'length' => 128, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'command_args' => array('type' => 'string', 'null' => false, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'externalcommand_id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM')
	);

	public $flappinghistory = array(
		'flappinghistory_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'event_time' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'event_time_usec' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'event_type' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'reason_type' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'flapping_type' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'object_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'percent_state_change' => array('type' => 'float', 'null' => false, 'default' => '0'),
		'low_threshold' => array('type' => 'float', 'null' => false, 'default' => '0'),
		'high_threshold' => array('type' => 'float', 'null' => false, 'default' => '0'),
		'comment_time' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'internal_comment_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'flappinghistory_id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM')
	);

	public $host_contactgroups = array(
		'host_contactgroup_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'host_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'index'),
		'contactgroup_object_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'host_contactgroup_id', 'unique' => 1),
			'instance_id' => array('column' => array('host_id', 'contactgroup_object_id'), 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM')
	);

	public $host_contacts = array(
		'host_contact_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'),
		'host_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'contact_object_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'host_contact_id', 'unique' => 1),
			'instance_id' => array('column' => array('instance_id', 'host_id', 'contact_object_id'), 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM')
	);

	public $host_parenthosts = array(
		'host_parenthost_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'host_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'index'),
		'parent_host_object_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'host_parenthost_id', 'unique' => 1),
			'instance_id' => array('column' => array('host_id', 'parent_host_object_id'), 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM')
	);

	public $hostchecks = array(
		'hostcheck_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'),
		'host_object_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'check_type' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'is_raw_check' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'current_check_attempt' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'max_check_attempts' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'state' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'state_type' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'start_time' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00', 'key' => 'index'),
		'start_time_usec' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'end_time' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'end_time_usec' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'command_object_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'command_args' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'command_line' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'timeout' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'early_timeout' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'execution_time' => array('type' => 'float', 'null' => false, 'default' => '0'),
		'latency' => array('type' => 'float', 'null' => false, 'default' => '0'),
		'return_code' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'output' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'long_output' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'perfdata' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'hostcheck_id', 'unique' => 1),
			'instance_id' => array('column' => array('instance_id', 'host_object_id', 'start_time', 'start_time_usec'), 'unique' => 1),
			'start_time' => array('column' => 'start_time', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM')
	);

	public $hostdependencies = array(
		'hostdependency_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'),
		'config_type' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'host_object_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'dependent_host_object_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'dependency_type' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'inherits_parent' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'timeperiod_object_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'fail_on_up' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'fail_on_down' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'fail_on_unreachable' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'indexes' => array(
			'PRIMARY' => array('column' => 'hostdependency_id', 'unique' => 1),
			'instance_id' => array('column' => array('instance_id', 'config_type', 'host_object_id', 'dependent_host_object_id', 'dependency_type', 'inherits_parent', 'fail_on_up', 'fail_on_down', 'fail_on_unreachable'), 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM')
	);

	public $hostescalation_contactgroups = array(
		'hostescalation_contactgroup_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'hostescalation_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'index'),
		'contactgroup_object_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'hostescalation_contactgroup_id', 'unique' => 1),
			'instance_id' => array('column' => array('hostescalation_id', 'contactgroup_object_id'), 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM')
	);

	public $hostescalation_contacts = array(
		'hostescalation_contact_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'),
		'hostescalation_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'contact_object_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'hostescalation_contact_id', 'unique' => 1),
			'instance_id' => array('column' => array('instance_id', 'hostescalation_id', 'contact_object_id'), 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM')
	);

	public $hostescalations = array(
		'hostescalation_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'),
		'config_type' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'host_object_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'timeperiod_object_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'first_notification' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'last_notification' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'notification_interval' => array('type' => 'float', 'null' => false, 'default' => '0'),
		'escalate_on_recovery' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'escalate_on_down' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'escalate_on_unreachable' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'indexes' => array(
			'PRIMARY' => array('column' => 'hostescalation_id', 'unique' => 1),
			'instance_id' => array('column' => array('instance_id', 'config_type', 'host_object_id', 'timeperiod_object_id', 'first_notification', 'last_notification'), 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM')
	);

	public $hostgroup_members = array(
		'hostgroup_member_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'hostgroup_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'index'),
		'host_object_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'hostgroup_member_id', 'unique' => 1),
			'instance_id' => array('column' => array('hostgroup_id', 'host_object_id'), 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM')
	);

	public $hostgroups = array(
		'hostgroup_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'),
		'config_type' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'hostgroup_object_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'alias' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'hostgroup_id', 'unique' => 1),
			'instance_id' => array('column' => array('instance_id', 'hostgroup_object_id'), 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM')
	);

	public $hosts = array(
		'host_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'),
		'config_type' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'host_object_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'index'),
		'alias' => array('type' => 'string', 'null' => false, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'display_name' => array('type' => 'string', 'null' => false, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'address' => array('type' => 'string', 'null' => false, 'length' => 128, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'check_command_object_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'check_command_args' => array('type' => 'string', 'null' => false, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'eventhandler_command_object_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'eventhandler_command_args' => array('type' => 'string', 'null' => false, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'notification_timeperiod_object_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'check_timeperiod_object_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'failure_prediction_options' => array('type' => 'string', 'null' => false, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'check_interval' => array('type' => 'float', 'null' => false, 'default' => '0'),
		'retry_interval' => array('type' => 'float', 'null' => false, 'default' => '0'),
		'max_check_attempts' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'first_notification_delay' => array('type' => 'float', 'null' => false, 'default' => '0'),
		'notification_interval' => array('type' => 'float', 'null' => false, 'default' => '0'),
		'notify_on_down' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'notify_on_unreachable' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'notify_on_recovery' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'notify_on_flapping' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'notify_on_downtime' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'stalk_on_up' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'stalk_on_down' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'stalk_on_unreachable' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'flap_detection_enabled' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'flap_detection_on_up' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'flap_detection_on_down' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'flap_detection_on_unreachable' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'low_flap_threshold' => array('type' => 'float', 'null' => false, 'default' => '0'),
		'high_flap_threshold' => array('type' => 'float', 'null' => false, 'default' => '0'),
		'process_performance_data' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'freshness_checks_enabled' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'freshness_threshold' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 8),
		'passive_checks_enabled' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'event_handler_enabled' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'active_checks_enabled' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'retain_status_information' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'retain_nonstatus_information' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'notifications_enabled' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'obsess_over_host' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'failure_prediction_enabled' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'notes' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'notes_url' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'action_url' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'icon_image' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'icon_image_alt' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'vrml_image' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'statusmap_image' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'have_2d_coords' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'x_2d' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'y_2d' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'have_3d_coords' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'x_3d' => array('type' => 'float', 'null' => false, 'default' => '0'),
		'y_3d' => array('type' => 'float', 'null' => false, 'default' => '0'),
		'z_3d' => array('type' => 'float', 'null' => false, 'default' => '0'),
		'importance' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'host_id', 'unique' => 1),
			'instance_id' => array('column' => array('instance_id', 'config_type', 'host_object_id'), 'unique' => 1),
			'host_object_id' => array('column' => 'host_object_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM')
	);

	public $hoststatus = array(
		'hoststatus_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'),
		'host_object_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'unique'),
		'status_update_time' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00', 'key' => 'index'),
		'output' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'long_output' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'perfdata' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'current_state' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'),
		'has_been_checked' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'should_be_scheduled' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'current_check_attempt' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'max_check_attempts' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'last_check' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'next_check' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'check_type' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'),
		'last_state_change' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00', 'key' => 'index'),
		'last_hard_state_change' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'last_hard_state' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'last_time_up' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'last_time_down' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'last_time_unreachable' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'state_type' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'),
		'last_notification' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'next_notification' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'no_more_notifications' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'notifications_enabled' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'),
		'problem_has_been_acknowledged' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'),
		'acknowledgement_type' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'current_notification_number' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'passive_checks_enabled' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'),
		'active_checks_enabled' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'),
		'event_handler_enabled' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'),
		'flap_detection_enabled' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'),
		'is_flapping' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'),
		'percent_state_change' => array('type' => 'float', 'null' => false, 'default' => '0', 'key' => 'index'),
		'latency' => array('type' => 'float', 'null' => false, 'default' => '0', 'key' => 'index'),
		'execution_time' => array('type' => 'float', 'null' => false, 'default' => '0', 'key' => 'index'),
		'scheduled_downtime_depth' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'),
		'failure_prediction_enabled' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'process_performance_data' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'obsess_over_host' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'modified_host_attributes' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'event_handler' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'check_command' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'normal_check_interval' => array('type' => 'float', 'null' => false, 'default' => '0'),
		'retry_check_interval' => array('type' => 'float', 'null' => false, 'default' => '0'),
		'check_timeperiod_object_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'hoststatus_id', 'unique' => 1),
			'object_id' => array('column' => 'host_object_id', 'unique' => 1),
			'instance_id' => array('column' => 'instance_id', 'unique' => 0),
			'status_update_time' => array('column' => 'status_update_time', 'unique' => 0),
			'current_state' => array('column' => 'current_state', 'unique' => 0),
			'check_type' => array('column' => 'check_type', 'unique' => 0),
			'state_type' => array('column' => 'state_type', 'unique' => 0),
			'last_state_change' => array('column' => 'last_state_change', 'unique' => 0),
			'notifications_enabled' => array('column' => 'notifications_enabled', 'unique' => 0),
			'problem_has_been_acknowledged' => array('column' => 'problem_has_been_acknowledged', 'unique' => 0),
			'active_checks_enabled' => array('column' => 'active_checks_enabled', 'unique' => 0),
			'passive_checks_enabled' => array('column' => 'passive_checks_enabled', 'unique' => 0),
			'event_handler_enabled' => array('column' => 'event_handler_enabled', 'unique' => 0),
			'flap_detection_enabled' => array('column' => 'flap_detection_enabled', 'unique' => 0),
			'is_flapping' => array('column' => 'is_flapping', 'unique' => 0),
			'percent_state_change' => array('column' => 'percent_state_change', 'unique' => 0),
			'latency' => array('column' => 'latency', 'unique' => 0),
			'execution_time' => array('column' => 'execution_time', 'unique' => 0),
			'scheduled_downtime_depth' => array('column' => 'scheduled_downtime_depth', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM')
	);

	public $instances = array(
		'instance_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 6, 'key' => 'primary'),
		'instance_name' => array('type' => 'string', 'null' => false, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'instance_description' => array('type' => 'string', 'null' => false, 'length' => 128, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'instance_id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM')
	);

	public $logentries = array(
		'logentry_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'index'),
		'logentry_time' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00', 'key' => 'index'),
		'entry_time' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'entry_time_usec' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'logentry_type' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'logentry_data' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'realtime_data' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'inferred_data_extracted' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'indexes' => array(
			'PRIMARY' => array('column' => 'logentry_id', 'unique' => 1),
			'instance_id' => array('column' => array('instance_id', 'logentry_time', 'entry_time', 'entry_time_usec'), 'unique' => 1),
			'logentry_time' => array('column' => 'logentry_time', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM')
	);

	public $notifications = array(
		'notification_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'),
		'notification_type' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'notification_reason' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'object_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'index'),
		'start_time' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00', 'key' => 'index'),
		'start_time_usec' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'end_time' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'end_time_usec' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'state' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'output' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'long_output' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'escalated' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'contacts_notified' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'indexes' => array(
			'PRIMARY' => array('column' => 'notification_id', 'unique' => 1),
			'instance_id' => array('column' => array('instance_id', 'object_id', 'start_time', 'start_time_usec'), 'unique' => 1),
			'top10' => array('column' => array('object_id', 'start_time', 'contacts_notified'), 'unique' => 0),
			'start_time' => array('column' => 'start_time', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM')
	);

	public $objects = array(
		'object_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'objecttype_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'),
		'name1' => array('type' => 'string', 'null' => false, 'length' => 128, 'key' => 'index', 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'name2' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 128, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'is_active' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'indexes' => array(
			'PRIMARY' => array('column' => 'object_id', 'unique' => 1),
			'objecttype_id' => array('column' => array('objecttype_id', 'name1', 'name2'), 'unique' => 0),
			'achmet' => array('column' => array('name1', 'name2'), 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM')
	);

	public $processevents = array(
		'processevent_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'event_type' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'event_time' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'event_time_usec' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'process_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'program_name' => array('type' => 'string', 'null' => false, 'length' => 16, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'program_version' => array('type' => 'string', 'null' => false, 'length' => 20, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'program_date' => array('type' => 'string', 'null' => false, 'length' => 10, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'processevent_id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM')
	);

	public $programstatus = array(
		'programstatus_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'unique'),
		'status_update_time' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'program_start_time' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'program_end_time' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'is_currently_running' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'process_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'daemon_mode' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'last_command_check' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'last_log_rotation' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'notifications_enabled' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'active_service_checks_enabled' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'passive_service_checks_enabled' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'active_host_checks_enabled' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'passive_host_checks_enabled' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'event_handlers_enabled' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'flap_detection_enabled' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'failure_prediction_enabled' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'process_performance_data' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'obsess_over_hosts' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'obsess_over_services' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'modified_host_attributes' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'modified_service_attributes' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'global_host_event_handler' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'global_service_event_handler' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'programstatus_id', 'unique' => 1),
			'instance_id' => array('column' => 'instance_id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM')
	);

	public $runtimevariables = array(
		'runtimevariable_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'),
		'varname' => array('type' => 'string', 'null' => false, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'varvalue' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'runtimevariable_id', 'unique' => 1),
			'instance_id' => array('column' => array('instance_id', 'varname'), 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM')
	);

	public $scheduleddowntime = array(
		'scheduleddowntime_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'),
		'downtime_type' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'object_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'entry_time' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'author_name' => array('type' => 'string', 'null' => false, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'comment_data' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'internal_downtime_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'triggered_by_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'is_fixed' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'duration' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'scheduled_start_time' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'scheduled_end_time' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'was_started' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'actual_start_time' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'actual_start_time_usec' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'scheduleddowntime_id', 'unique' => 1),
			'instance_id' => array('column' => array('instance_id', 'object_id', 'entry_time', 'internal_downtime_id'), 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM')
	);

	public $service_contactgroups = array(
		'service_contactgroup_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'service_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'index'),
		'contactgroup_object_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'service_contactgroup_id', 'unique' => 1),
			'instance_id' => array('column' => array('service_id', 'contactgroup_object_id'), 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM')
	);

	public $service_contacts = array(
		'service_contact_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'),
		'service_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'contact_object_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'service_contact_id', 'unique' => 1),
			'instance_id' => array('column' => array('instance_id', 'service_id', 'contact_object_id'), 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM')
	);

	public $service_parentservices = array(
		'service_parentservice_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'service_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'index'),
		'parent_service_object_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'service_parentservice_id', 'unique' => 1),
			'instance_id' => array('column' => array('service_id', 'parent_service_object_id'), 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM')
	);

	public $servicechecks = array(
		'servicecheck_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'),
		'service_object_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'index'),
		'check_type' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'current_check_attempt' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'max_check_attempts' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'state' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'state_type' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'start_time' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00', 'key' => 'index'),
		'start_time_usec' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'end_time' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'end_time_usec' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'command_object_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'command_args' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'command_line' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'timeout' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'early_timeout' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'execution_time' => array('type' => 'float', 'null' => false, 'default' => '0'),
		'latency' => array('type' => 'float', 'null' => false, 'default' => '0'),
		'return_code' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'output' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'long_output' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'perfdata' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'servicecheck_id', 'unique' => 1),
			'start_time' => array('column' => 'start_time', 'unique' => 0),
			'instance_id' => array('column' => 'instance_id', 'unique' => 0),
			'service_object_id' => array('column' => 'service_object_id', 'unique' => 0),
			'start_time_2' => array('column' => 'start_time', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM')
	);

	public $servicedependencies = array(
		'servicedependency_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'),
		'config_type' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'service_object_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'dependent_service_object_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'dependency_type' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'inherits_parent' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'timeperiod_object_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'fail_on_ok' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'fail_on_warning' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'fail_on_unknown' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'fail_on_critical' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'indexes' => array(
			'PRIMARY' => array('column' => 'servicedependency_id', 'unique' => 1),
			'instance_id' => array('column' => array('instance_id', 'config_type', 'service_object_id', 'dependent_service_object_id', 'dependency_type', 'inherits_parent', 'fail_on_ok', 'fail_on_warning', 'fail_on_unknown', 'fail_on_critical'), 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM')
	);

	public $serviceescalation_contactgroups = array(
		'serviceescalation_contactgroup_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'serviceescalation_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'index'),
		'contactgroup_object_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'serviceescalation_contactgroup_id', 'unique' => 1),
			'instance_id' => array('column' => array('serviceescalation_id', 'contactgroup_object_id'), 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM')
	);

	public $serviceescalation_contacts = array(
		'serviceescalation_contact_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'),
		'serviceescalation_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'contact_object_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'serviceescalation_contact_id', 'unique' => 1),
			'instance_id' => array('column' => array('instance_id', 'serviceescalation_id', 'contact_object_id'), 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM')
	);

	public $serviceescalations = array(
		'serviceescalation_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'),
		'config_type' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'service_object_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'timeperiod_object_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'first_notification' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'last_notification' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'notification_interval' => array('type' => 'float', 'null' => false, 'default' => '0'),
		'escalate_on_recovery' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'escalate_on_warning' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'escalate_on_unknown' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'escalate_on_critical' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'indexes' => array(
			'PRIMARY' => array('column' => 'serviceescalation_id', 'unique' => 1),
			'instance_id' => array('column' => array('instance_id', 'config_type', 'service_object_id', 'timeperiod_object_id', 'first_notification', 'last_notification'), 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM')
	);

	public $servicegroup_members = array(
		'servicegroup_member_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'servicegroup_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'index'),
		'service_object_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'servicegroup_member_id', 'unique' => 1),
			'instance_id' => array('column' => array('servicegroup_id', 'service_object_id'), 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM')
	);

	public $servicegroups = array(
		'servicegroup_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'),
		'config_type' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'servicegroup_object_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'alias' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'servicegroup_id', 'unique' => 1),
			'instance_id' => array('column' => array('instance_id', 'config_type', 'servicegroup_object_id'), 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM')
	);

	public $services = array(
		'service_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'),
		'config_type' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'host_object_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'service_object_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'index'),
		'display_name' => array('type' => 'string', 'null' => false, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'check_command_object_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'check_command_args' => array('type' => 'string', 'null' => false, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'eventhandler_command_object_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'eventhandler_command_args' => array('type' => 'string', 'null' => false, 'length' => 1000, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'notification_timeperiod_object_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'check_timeperiod_object_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'failure_prediction_options' => array('type' => 'string', 'null' => false, 'length' => 64, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'check_interval' => array('type' => 'float', 'null' => false, 'default' => '0'),
		'retry_interval' => array('type' => 'float', 'null' => false, 'default' => '0'),
		'max_check_attempts' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'first_notification_delay' => array('type' => 'float', 'null' => false, 'default' => '0'),
		'notification_interval' => array('type' => 'float', 'null' => false, 'default' => '0'),
		'notify_on_warning' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'notify_on_unknown' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'notify_on_critical' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'notify_on_recovery' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'notify_on_flapping' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'notify_on_downtime' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'stalk_on_ok' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'stalk_on_warning' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'stalk_on_unknown' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'stalk_on_critical' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'is_volatile' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'flap_detection_enabled' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'flap_detection_on_ok' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'flap_detection_on_warning' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'flap_detection_on_unknown' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'flap_detection_on_critical' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'low_flap_threshold' => array('type' => 'float', 'null' => false, 'default' => '0'),
		'high_flap_threshold' => array('type' => 'float', 'null' => false, 'default' => '0'),
		'process_performance_data' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'freshness_checks_enabled' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 8),
		'freshness_threshold' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'passive_checks_enabled' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'event_handler_enabled' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'active_checks_enabled' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'retain_status_information' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'retain_nonstatus_information' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'notifications_enabled' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'obsess_over_service' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'failure_prediction_enabled' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'notes' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'notes_url' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'action_url' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'icon_image' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'icon_image_alt' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'importance' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'service_id', 'unique' => 1),
			'instance_id' => array('column' => array('instance_id', 'config_type', 'service_object_id'), 'unique' => 1),
			'service_object_id' => array('column' => 'service_object_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM')
	);

	public $servicestatus = array(
		'servicestatus_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'),
		'service_object_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'unique'),
		'status_update_time' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00', 'key' => 'index'),
		'output' => array('type' => 'string', 'null' => false, 'length' => 512, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'long_output' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'perfdata' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'current_state' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'),
		'has_been_checked' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'should_be_scheduled' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'current_check_attempt' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'max_check_attempts' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'last_check' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'next_check' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'check_type' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'),
		'last_state_change' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00', 'key' => 'index'),
		'last_hard_state_change' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'last_hard_state' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'last_time_ok' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'last_time_warning' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'last_time_unknown' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'last_time_critical' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'state_type' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'),
		'last_notification' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'next_notification' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'no_more_notifications' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'notifications_enabled' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'),
		'problem_has_been_acknowledged' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'),
		'acknowledgement_type' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'current_notification_number' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'passive_checks_enabled' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'),
		'active_checks_enabled' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'),
		'event_handler_enabled' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'),
		'flap_detection_enabled' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'),
		'is_flapping' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'),
		'percent_state_change' => array('type' => 'float', 'null' => false, 'default' => '0', 'key' => 'index'),
		'latency' => array('type' => 'float', 'null' => false, 'default' => '0', 'key' => 'index'),
		'execution_time' => array('type' => 'float', 'null' => false, 'default' => '0', 'key' => 'index'),
		'scheduled_downtime_depth' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'),
		'failure_prediction_enabled' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'process_performance_data' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'obsess_over_service' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'modified_service_attributes' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'event_handler' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'check_command' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'normal_check_interval' => array('type' => 'float', 'null' => false, 'default' => '0'),
		'retry_check_interval' => array('type' => 'float', 'null' => false, 'default' => '0'),
		'check_timeperiod_object_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'servicestatus_id', 'unique' => 1),
			'object_id' => array('column' => 'service_object_id', 'unique' => 1),
			'instance_id' => array('column' => 'instance_id', 'unique' => 0),
			'status_update_time' => array('column' => 'status_update_time', 'unique' => 0),
			'current_state' => array('column' => 'current_state', 'unique' => 0),
			'check_type' => array('column' => 'check_type', 'unique' => 0),
			'state_type' => array('column' => 'state_type', 'unique' => 0),
			'last_state_change' => array('column' => 'last_state_change', 'unique' => 0),
			'notifications_enabled' => array('column' => 'notifications_enabled', 'unique' => 0),
			'problem_has_been_acknowledged' => array('column' => 'problem_has_been_acknowledged', 'unique' => 0),
			'active_checks_enabled' => array('column' => 'active_checks_enabled', 'unique' => 0),
			'passive_checks_enabled' => array('column' => 'passive_checks_enabled', 'unique' => 0),
			'event_handler_enabled' => array('column' => 'event_handler_enabled', 'unique' => 0),
			'flap_detection_enabled' => array('column' => 'flap_detection_enabled', 'unique' => 0),
			'is_flapping' => array('column' => 'is_flapping', 'unique' => 0),
			'percent_state_change' => array('column' => 'percent_state_change', 'unique' => 0),
			'latency' => array('column' => 'latency', 'unique' => 0),
			'execution_time' => array('column' => 'execution_time', 'unique' => 0),
			'scheduled_downtime_depth' => array('column' => 'scheduled_downtime_depth', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM')
	);

	public $statehistory = array(
		'statehistory_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'state_time' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'state_time_usec' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'object_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'state_change' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'state' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'state_type' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'current_check_attempt' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'max_check_attempts' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'last_state' => array('type' => 'integer', 'null' => false, 'default' => '-1', 'length' => 6),
		'last_hard_state' => array('type' => 'integer', 'null' => false, 'default' => '-1', 'length' => 6),
		'output' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'long_output' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'statehistory_id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM')
	);

	public $systemcommands = array(
		'systemcommand_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'),
		'start_time' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'start_time_usec' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'end_time' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'end_time_usec' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'command_line' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'timeout' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'early_timeout' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'execution_time' => array('type' => 'float', 'null' => false, 'default' => '0'),
		'return_code' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'output' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'long_output' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'systemcommand_id', 'unique' => 1),
			'instance_id' => array('column' => 'instance_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM')
	);

	public $timedeventqueue = array(
		'timedeventqueue_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'),
		'event_type' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'),
		'queued_time' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'queued_time_usec' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'scheduled_time' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00', 'key' => 'index'),
		'recurring_event' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'object_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'index'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'timedeventqueue_id', 'unique' => 1),
			'instance_id' => array('column' => 'instance_id', 'unique' => 0),
			'event_type' => array('column' => 'event_type', 'unique' => 0),
			'scheduled_time' => array('column' => 'scheduled_time', 'unique' => 0),
			'object_id' => array('column' => 'object_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM')
	);

	public $timedevents = array(
		'timedevent_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'),
		'event_type' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'),
		'queued_time' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'queued_time_usec' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'event_time' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'event_time_usec' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'scheduled_time' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00', 'key' => 'index'),
		'recurring_event' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'object_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'index'),
		'deletion_time' => array('type' => 'datetime', 'null' => false, 'default' => '0000-00-00 00:00:00'),
		'deletion_time_usec' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'timedevent_id', 'unique' => 1),
			'instance_id' => array('column' => 'instance_id', 'unique' => 0),
			'event_type' => array('column' => 'event_type', 'unique' => 0),
			'scheduled_time' => array('column' => 'scheduled_time', 'unique' => 0),
			'object_id' => array('column' => 'object_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM')
	);

	public $timeperiod_timeranges = array(
		'timeperiod_timerange_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'timeperiod_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'index'),
		'day' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'start_sec' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'end_sec' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'timeperiod_timerange_id', 'unique' => 1),
			'instance_id' => array('column' => array('timeperiod_id', 'day', 'start_sec', 'end_sec'), 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM')
	);

	public $timeperiods = array(
		'timeperiod_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'instance_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6, 'key' => 'index'),
		'config_type' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 6),
		'timeperiod_object_id' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'alias' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_swedish_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'timeperiod_id', 'unique' => 1),
			'instance_id' => array('column' => array('instance_id', 'config_type', 'timeperiod_object_id'), 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'MyISAM')
	);

}
